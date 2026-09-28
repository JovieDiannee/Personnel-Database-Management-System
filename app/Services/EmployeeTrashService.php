<?php

namespace App\Services;

use App\Models\EmployeeDeletionRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EmployeeTrashService
{
    /*
    |--------------------------------------------------------------------------
    | Validate remarks
    |--------------------------------------------------------------------------
    */

    private function validateRemarks(string $remarks): string
    {
        $remarks = trim($remarks);

        if ($remarks === '' || mb_strlen($remarks) > 2000) {
            throw ValidationException::withMessages([
                'remarks' => 'Remarks are required and must not exceed 2,000 characters.',
            ]);
        }

        return $remarks;
    }

    /*
    |--------------------------------------------------------------------------
    | Check the acting user's current role
    |--------------------------------------------------------------------------
    */

    private function authorizedActor(User $actor, array $roles): User
    {
        $currentUser = User::find($actor->id);

        abort_unless(
            $currentUser &&
            in_array($currentUser->role, $roles, true),
            403,
            'You are not authorized to perform this action.'
        );

        return $currentUser;
    }

    /*
    |--------------------------------------------------------------------------
    | Protect administrator accounts
    |--------------------------------------------------------------------------
    */

    private function checkEmployee(User $employee): void
    {
        abort_unless(
            in_array($employee->role, ['user', 'admin'], true),
            403,
            'Super admin accounts cannot be moved to Trash Bin.'
        );

        // Preserve your existing protected employee rule.
        $employeeNumber = $employee
            ->basicInformation
            ?->issuedId
            ?->employee_id;

        abort_if(
            (string) $employeeNumber === '1000001',
            403,
            'This employee record is protected.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Move employee to Trash Bin
    |--------------------------------------------------------------------------
    | Called only after authorization and validation.
    | Related employee records are retained.
    */

    private function moveToTrash(User $employee): void
    {
        // Invalidate existing "remember me" cookies.
        $employee->remember_token = Str::random(60);
        $employee->save();

        // Soft delete: sets users.deleted_at.
        $employee->delete();

        // Remove existing sessions when database sessions are configured.
        if (config('session.driver') === 'database') {
            DB::connection(config('session.connection'))
                ->table(config('session.table', 'sessions'))
                ->where('user_id', $employee->id)
                ->delete();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Submit a deletion action
    |--------------------------------------------------------------------------
    | Admin: create a pending request.
    | Super admin: move directly to Trash Bin.
    */

    public function submit(
        User $actor,
        int $employeeId,
        string $remarks
    ): void {
        $remarks = $this->validateRemarks($remarks);

        DB::transaction(function () use ($actor, $employeeId, $remarks) {
            $actor = $this->authorizedActor(
                $actor,
                ['admin', 'super_admin']
            );

            $employee = User::query()
                ->lockForUpdate()
                ->findOrFail($employeeId);

            // Only user and admin accounts are eligible.
            abort_unless(
                in_array($employee->role, ['user', 'admin'], true),
                403,
                'Super admin accounts cannot be requested for deletion or moved to Trash Bin.'
            );

            // Preserve the additional protected-employee checks.
            $this->checkEmployee($employee);

            if ($actor->role === 'admin') {
                // Admins cannot request deletion of their own account.
                abort_if(
                    (string) $employee->id === (string) $actor->id,
                    403,
                    'You cannot request deletion of your own account.'
                );

                $adminSchoolId = $actor
                    ->employmentStatus
                    ?->school_db_id;

                $employeeSchoolId = $employee
                    ->employmentStatus
                    ?->school_db_id;

                // Admins may target other users/admins in their school only.
                abort_unless(
                    $adminSchoolId &&
                    (string) $adminSchoolId === (string) $employeeSchoolId,
                    403,
                    'You can only request deletion of accounts within your assigned school.'
                );
            }

            $pendingRequest = EmployeeDeletionRequest::query()
                ->where('employee_id', $employee->id)
                ->where('status', 'pending')
                ->lockForUpdate()
                ->first();

            if ($pendingRequest) {
                throw ValidationException::withMessages([
                    'remarks' => 'This employee already has a pending deletion request. Review that request first.',
                ]);
            }

            $directDeletion = $actor->role === 'super_admin';

            EmployeeDeletionRequest::create([
                'employee_id' => $employee->id,
                'requested_by' => $actor->id,
                'request_remarks' => $remarks,
                'source' => $directDeletion ? 'direct' : 'request',
                'status' => $directDeletion ? 'approved' : 'pending',

                'pending_employee_id' => $directDeletion
                    ? null
                    : $employee->id,

                'reviewed_by' => $directDeletion
                    ? $actor->id
                    : null,

                'reviewed_at' => $directDeletion
                    ? now()
                    : null,
            ]);

            // Admin requests stay pending; only super admins delete directly.
            if ($directDeletion) {
                $this->moveToTrash($employee);
            }
        }, 3);
    }

    /*
    |--------------------------------------------------------------------------
    | Approve or disapprove a request
    |--------------------------------------------------------------------------
    | Super admin only.
    */

    public function review(
        User $actor,
        int $requestId,
        bool $approve,
        string $remarks
    ): void {
        $remarks = $this->validateRemarks($remarks);

        DB::transaction(function () use (
            $actor,
            $requestId,
            $approve,
            $remarks
        ) {
            // Only super admins can review deletion requests.
            $actor = $this->authorizedActor($actor, ['super_admin']);

            $deletionRequest = EmployeeDeletionRequest::findOrFail(
                $requestId
            );

            // Lock the employee first, then the request.
            $employee = User::withTrashed()
                ->lockForUpdate()
                ->findOrFail($deletionRequest->employee_id);

            $deletionRequest = EmployeeDeletionRequest::query()
                ->lockForUpdate()
                ->findOrFail($requestId);

            if ($deletionRequest->status !== 'pending') {
                throw ValidationException::withMessages([
                    'remarks' => 'This request has already been reviewed.',
                ]);
            }

            if ($approve) {
                // Block approval of existing self-deletion requests.
                if (
                    (string) $deletionRequest->requested_by ===
                    (string) $employee->id
                ) {
                    throw ValidationException::withMessages([
                        'remarks' => 'Self-deletion requests cannot be approved. '
                            . 'Please disapprove this request.',
                    ]);
                }

                // Only regular users and admins can be moved to Trash Bin.
                if (!in_array($employee->role, ['user', 'admin'], true)) {
                    throw ValidationException::withMessages([
                        'remarks' => 'Super admin accounts cannot be deleted. '
                            . 'Please disapprove this request.',
                    ]);
                }

                // Preserve the additional protected-employee checks.
                $this->checkEmployee($employee);

                if ($employee->trashed()) {
                    throw ValidationException::withMessages([
                        'remarks' => 'This employee is already in Trash Bin.',
                    ]);
                }

                // Recheck the requester’s current role and school assignment.
                $requester = User::find(
                    $deletionRequest->requested_by
                );

                $requesterSchoolId = $requester
                    ?->employmentStatus
                    ?->school_db_id;

                $employeeSchoolId = $employee
                    ->employmentStatus
                    ?->school_db_id;

                if (
                    !$requester ||
                    $requester->role !== 'admin' ||
                    !$requesterSchoolId ||
                    (string) $requesterSchoolId !== (string) $employeeSchoolId
                ) {
                    throw ValidationException::withMessages([
                        'remarks' => 'The requester no longer has access to this employee. '
                            . 'Disapprove this request and submit a new action if needed.',
                    ]);
                }

                // Approval soft-deletes the account and blocks login.
                $this->moveToTrash($employee);
            }

            // Disapproval saves the decision without deleting the employee.
            $deletionRequest->update([
                'status' => $approve ? 'approved' : 'disapproved',
                'pending_employee_id' => null,
                'reviewed_by' => $actor->id,
                'review_remarks' => $remarks,
                'reviewed_at' => now(),
            ]);
        }, 3);
    }

    /*
    |--------------------------------------------------------------------------
    | Restore employee
    |--------------------------------------------------------------------------
    | Super admin only. Preserve the previous account status.
    */

    public function restore(User $actor, int $employeeId): void
    {
        DB::transaction(function () use ($actor, $employeeId) {
            $actor = $this->authorizedActor($actor, ['super_admin']);

            $employee = User::onlyTrashed()
                ->lockForUpdate()
                ->findOrFail($employeeId);

            $this->checkEmployee($employee);

            $deletionRequest = EmployeeDeletionRequest::query()
                ->where('employee_id', $employee->id)
                ->where('status', 'approved')
                ->whereNull('restored_at')
                ->latest('id')
                ->lockForUpdate()
                ->first();

            abort_unless(
                $deletionRequest,
                409,
                'No matching deletion history was found for this employee.'
            );

            $employee->restore();

            $deletionRequest->update([
                'restored_by' => $actor->id,
                'restored_at' => now(),
            ]);
        }, 3);
    }
}