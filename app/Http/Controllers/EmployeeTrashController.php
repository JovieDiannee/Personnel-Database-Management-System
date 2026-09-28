<?php

namespace App\Http\Controllers;

use App\Models\EmployeeDeletionRequest;
use App\Models\User;
use App\Services\EmployeeTrashService;
use Illuminate\Http\Request;

class EmployeeTrashController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Delete Employee page
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $actor = $request->user();

        abort_unless(
            $actor &&
            in_array($actor->role, ['admin', 'super_admin'], true),
            403
        );

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:200'],
        ]);

        $search = trim($validated['search'] ?? '');
        $isSuperAdmin = $actor->role === 'super_admin';
        $schoolId = $actor->employmentStatus?->school_db_id;

        /*
        |--------------------------------------------------------------------------
        | Eligible employees
        |--------------------------------------------------------------------------
        | Super admin: user and admin accounts.
        | Admin: user and admin accounts within their school, except themselves.
        | Super admin accounts are always excluded.
        */

        $query = User::query()
            ->whereIn('role', ['user', 'admin'])
            ->whereHas('basicInformation')
            ->whereDoesntHave(
                'basicInformation.issuedId',
                function ($query) {
                    $query->where('employee_id', '1000001');
                }
            )
            ->with([
                'basicInformation',
                'employmentStatus.school',
            ]);

        if (!$isSuperAdmin) {
            // Hide the logged-in admin's own account.
            $query->where('users.id', '!=', $actor->id);

            // Restrict eligible accounts to the admin's assigned school.
            if ($schoolId) {
                $query->whereHas(
                    'employmentStatus',
                    function ($query) use ($schoolId) {
                        $query->where('school_db_id', $schoolId);
                    }
                );
            } else {
                // No assigned school means no eligible accounts.
                $query->whereRaw('1 = 0');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        | Group conditions to preserve role, school, and self-exclusion filters.
        */

        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas(
                        'basicInformation',
                        function ($basicQuery) use ($search) {
                            $basicQuery->where(function ($nameQuery) use ($search) {
                                $nameQuery
                                    ->where('first_name', 'like', "%{$search}%")
                                    ->orWhere('middle_name', 'like', "%{$search}%")
                                    ->orWhere('last_name', 'like', "%{$search}%");
                            });
                        }
                    );
            });
        }

        $employees = $query
            ->orderBy('name')
            ->orderBy('id')
            ->paginate(15, ['*'], 'employees_page')
            ->withQueryString();

        // Identify employees who already have pending requests.
        $pendingIds = EmployeeDeletionRequest::query()
            ->where('status', 'pending')
            ->whereIn('employee_id', $employees->pluck('id'))
            ->pluck('employee_id')
            ->all();

        /*
        |--------------------------------------------------------------------------
        | Request history
        |--------------------------------------------------------------------------
        */

        $history = EmployeeDeletionRequest::query()
            ->with([
                'employee',
                'requester',
                'reviewer',
                'restorer',
            ]);

        // Admins see only requests they submitted.
        if (!$isSuperAdmin) {
            $history->where('requested_by', $actor->id);
        }

        $requests = $history
            ->orderByRaw(
                "CASE WHEN status = 'pending' THEN 0 ELSE 1 END"
            )
            ->latest('id')
            ->paginate(15, ['*'], 'requests_page')
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Trash Bin — super admin only
        |--------------------------------------------------------------------------
        */

        $trashed = null;

        if ($isSuperAdmin) {
            $trashed = User::onlyTrashed()
                ->whereIn('role', ['user', 'admin'])
                ->with('employmentStatus.school')
                ->latest('deleted_at')
                ->orderByDesc('id')
                ->paginate(15, ['*'], 'trash_page')
                ->withQueryString();
        }

        return view(
            'danger-zone.delete-employee',
            compact(
                'employees',
                'requests',
                'trashed',
                'pendingIds',
                'search',
                'isSuperAdmin',
                'schoolId'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Admin: submit request
    | Super admin: move directly to Trash Bin
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        int $employee,
        EmployeeTrashService $service
    ) {
        $actor = $request->user();

        abort_unless(
            $actor &&
            in_array($actor->role, ['admin', 'super_admin'], true),
            403
        );

        $validated = $request->validate([
            'remarks' => ['required', 'string', 'max:2000'],
        ]);

        $service->submit(
            $actor,
            $employee,
            $validated['remarks']
        );

        $message = $actor->role === 'super_admin'
            ? 'Employee moved to Trash Bin. Login access is blocked.'
            : 'Deletion request submitted. The employee remains active while awaiting approval.';

        return redirect()
            ->route('danger-zone.delete-employee')
            ->with('success', $message);
    }

    /*
    |--------------------------------------------------------------------------
    | Super admin: approve or disapprove
    |--------------------------------------------------------------------------
    */

    public function review(
        Request $request,
        int $deletionRequest,
        EmployeeTrashService $service
    ) {
        $actor = $request->user();

        abort_unless(
            $actor && $actor->role === 'super_admin',
            403
        );

        $validated = $request->validate([
            'decision' => ['required', 'in:approve,disapprove'],
            'remarks' => ['required', 'string', 'max:2000'],
        ]);

        $approve = $validated['decision'] === 'approve';

        $service->review(
            $actor,
            $deletionRequest,
            $approve,
            $validated['remarks']
        );

        $message = $approve
            ? 'Request approved. Employee moved to Trash Bin.'
            : 'Request disapproved. Employee remains active.';

        return redirect()
            ->route('danger-zone.delete-employee')
            ->with('success', $message);
    }

    /*
    |--------------------------------------------------------------------------
    | Super admin: restore employee
    |--------------------------------------------------------------------------
    */

    public function restore(
        Request $request,
        int $employee,
        EmployeeTrashService $service
    ) {
        $actor = $request->user();

        abort_unless(
            $actor && $actor->role === 'super_admin',
            403
        );

        $service->restore($actor, $employee);

        return redirect()
            ->route('danger-zone.delete-employee')
            ->with(
                'success',
                'Employee restored. Previous account status and related records were retained.'
            );
    }
}