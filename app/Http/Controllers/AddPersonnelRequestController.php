<?php

namespace App\Http\Controllers;

use App\Models\AddPersonnelRequest;
use App\Models\BasicInformation;
use App\Models\EmploymentStatus;
use App\Models\PlantillaDb;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AddPersonnelRequestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admin - Request List
    |--------------------------------------------------------------------------
    |
    | Admin can only see requests submitted by their own account.
    |
    */

    public function index(Request $request)
    {
        $user = Auth::user();

        abort_unless(
            in_array($user->role, ['admin', 'super_admin']),
            403
        );

        $search = trim($request->input('search', ''));

        $query = AddPersonnelRequest::with([
                'school',
                'plantilla',
                'requester',
                'reviewer',
            ])
            ->where('requested_by', $user->id)
            ->latest();

        if ($search !== '') {

            $query->where(function ($q) use ($search) {

                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('employment_status', 'like', "%{$search}%")
                    ->orWhere('nature_of_work', 'like', "%{$search}%")

                    ->orWhereHas('plantilla', function ($plantilla) use ($search) {
                        $plantilla
                            ->where('item_number', 'like', "%{$search}%")
                            ->orWhere('position_title', 'like', "%{$search}%");
                    });
            });
        }

        $requests = $query
            ->paginate(15)
            ->withQueryString();

        return view(
            'add-personnel-requests.list',
            compact('requests', 'search')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Admin - Add Personnel Request Form
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | Logged-in User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        */

        abort_unless(
            in_array($user->role, ['admin', 'super_admin']),
            403
        );


        /*
        |--------------------------------------------------------------------------
        | Admin's Assigned School
        |--------------------------------------------------------------------------
        */

        $school = null;

        if ($user->role === 'admin') {

            $school = $user->employmentStatus?->school;

            if (!$school) {

                return redirect()
                    ->route('add-personnel-requests.index')
                    ->with(
                        'error',
                        'Your account is not assigned to a school.'
                    );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Get ALL Plantilla Items
        |--------------------------------------------------------------------------
        |
        | This will display ALL records from plantilla_db.
        | No school filtering.
        | No used-item filtering.
        | No pending-request filtering.
        |
        */

        $plantillas = PlantillaDb::query()
            ->orderBy('item_number', 'asc')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'add-personnel-requests.create',
            compact(
                'school',
                'plantillas'
            )
        );
    }

  
    /*
    |--------------------------------------------------------------------------
    | Admin - Submit Personnel Request
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Logged-in User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        abort_unless(
            $user && in_array($user->role, ['admin', 'super_admin']),
            403
        );


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            // Personal Information
            'first_name' => [
                'required',
                'string',
                'max:100',
            ],

            'middle_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'last_name' => [
                'required',
                'string',
                'max:100',
            ],

            'extension_name' => [
                'nullable',
                'string',
                'max:20',
            ],

            'email' => [
                'required',
                'string',
                'max:255',
            ],

            'sex' => [
                'nullable',
                Rule::in(['Male', 'Female']),
            ],

            'birth_place' => [
                'nullable',
                'string',
                'max:255',
            ],

            'birth_date' => [
                'required',
                'date',
                'before:today',
            ],

            'mobile_number' => [
                'nullable',
                'string',
                'max:30',
            ],

            'specialization' => [
                'nullable',
                'string',
                'max:150',
            ],


            // Employment Information
            'plantilla_db_id' => [
                'nullable',
                'integer',
                'exists:plantilla_db,id',
            ],

            'date_of_original_appointment' => [
                'nullable',
                'date',
            ],

            'date_of_last_promotion' => [
                'nullable',
                'date',
            ],

            'employment_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'warm_body_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'nature_of_work' => [
                'nullable',
                'string',
                'max:100',
            ],

            'source_of_fund' => [
                'nullable',
                'string',
                'max:100',
            ],

            'monthly_salary' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'contract_duration' => [
                'nullable',
                'string',
                'max:100',
            ],


            // Request
            'request_remarks' => [
                'nullable',
                'string',
                'max:2000',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Determine School
        |--------------------------------------------------------------------------
        |
        | School is automatically based on the logged-in Admin.
        |
        */

        $schoolId = $user->employmentStatus?->school_db_id;


        if (!$schoolId) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Your account is not assigned to a school.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Normalize Email
        |--------------------------------------------------------------------------
        */

        $email = strtolower(
            trim($validated['email'])
        );


        /*
        |--------------------------------------------------------------------------
        | Check Existing Email
        |--------------------------------------------------------------------------
        */

        $existingUser = User::withTrashed()
            ->whereRaw(
                'LOWER(email) = ?',
                [$email]
            )
            ->exists();


        if ($existingUser) {

            return back()
                ->withInput()
                ->withErrors([
                    'email' =>
                        'This email address already belongs to an existing personnel.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Check Pending Email Request
        |--------------------------------------------------------------------------
        */

        $pendingEmail = AddPersonnelRequest::query()
            ->whereRaw(
                'LOWER(email) = ?',
                [$email]
            )
            ->where('status', 'pending')
            ->exists();


        if ($pendingEmail) {

            return back()
                ->withInput()
                ->withErrors([
                    'email' =>
                        'A pending personnel request already exists for this email address.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Plantilla
        |--------------------------------------------------------------------------
        |
        | The plantilla only needs to exist.
        |
        | IMPORTANT:
        | The same plantilla item CAN be assigned to multiple personnel.
        |
        */

        if (!empty($validated['plantilla_db_id'])) {

            PlantillaDb::findOrFail(
                $validated['plantilla_db_id']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Save Personnel Request
        |--------------------------------------------------------------------------
        */

        try {

            AddPersonnelRequest::create([

                /*
                |--------------------------------------------------------------------------
                | Request Information
                |--------------------------------------------------------------------------
                */

                'requested_by' =>
                    $user->id,


                /*
                |--------------------------------------------------------------------------
                | Personal Information
                |--------------------------------------------------------------------------
                */

                'first_name' =>
                    trim($validated['first_name']),

                'middle_name' =>
                    !empty($validated['middle_name'])
                        ? trim($validated['middle_name'])
                        : null,

                'last_name' =>
                    trim($validated['last_name']),

                'extension_name' =>
                    !empty($validated['extension_name'])
                        ? trim($validated['extension_name'])
                        : null,

                'email' =>
                    $email,

                'sex' =>
                    $validated['sex'] ?? null,

                'birth_place' =>
                    !empty($validated['birth_place'])
                        ? trim($validated['birth_place'])
                        : null,

                'birth_date' =>
                    $validated['birth_date'] ?? null,

                'mobile_number' =>
                    !empty($validated['mobile_number'])
                        ? trim($validated['mobile_number'])
                        : null,

                'specialization' =>
                    !empty($validated['specialization'])
                        ? trim($validated['specialization'])
                        : null,


                /*
                |--------------------------------------------------------------------------
                | Employment Information
                |--------------------------------------------------------------------------
                */

                'school_db_id' =>
                    $schoolId,

                'plantilla_db_id' =>
                    $validated['plantilla_db_id'] ?? null,

                'date_of_original_appointment' =>
                    $validated['date_of_original_appointment'] ?? null,

                'date_of_last_promotion' =>
                    $validated['date_of_last_promotion'] ?? null,

                'employment_status' =>
                    $validated['employment_status'] ?? null,

                'warm_body_status' =>
                    $validated['warm_body_status'] ?? null,

                'nature_of_work' =>
                    $validated['nature_of_work'] ?? null,

                'source_of_fund' =>
                    $validated['source_of_fund'] ?? null,

                'monthly_salary' =>
                    $validated['monthly_salary'] ?? null,

                'contract_duration' =>
                    $validated['contract_duration'] ?? null,


                /*
                |--------------------------------------------------------------------------
                | Remarks / Status
                |--------------------------------------------------------------------------
                */

                'request_remarks' =>
                    $validated['request_remarks'] ?? null,

                'status' =>
                    'pending',

            ]);


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('add-personnel-requests.index')
                ->with(
                    'success',
                    'Personnel request submitted successfully. It is now pending Personnel Unit approval.'
                );


        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Log Error
            |--------------------------------------------------------------------------
            */

            report($e);


            /*
            |--------------------------------------------------------------------------
            | Return Error
            |--------------------------------------------------------------------------
            */

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to submit the personnel request. Please try again.'
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Super Admin - Personnel Requests
    |--------------------------------------------------------------------------
    */

    public function adminIndex(Request $request)
    {
        $user = Auth::user();

        abort_unless(
            $user && $user->role === 'super_admin',
            403
        );

        $search = trim($request->input('search', ''));
        $status = trim($request->input('status', ''));

        $query = AddPersonnelRequest::with([
            'requester',
            'school',
            'plantilla',
            'reviewer',
            'createdUser',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $query->where(function ($q) use ($search) {

                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")

                    ->orWhereHas('school', function ($school) use ($search) {

                        $school->where(
                            'school_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'school_id',
                            'like',
                            "%{$search}%"
                        );

                    })

                    ->orWhereHas('plantilla', function ($plantilla) use ($search) {

                        $plantilla->where(
                            'item_number',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'position_title',
                            'like',
                            "%{$search}%"
                        );

                    });
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if (
            $status !== '' &&
            in_array($status, [
                'pending',
                'approved',
                'disapproved'
            ])
        ) {

            $query->where('status', $status);
        }


        /*
        |--------------------------------------------------------------------------
        | Pending Requests First
        |--------------------------------------------------------------------------
        */

        $query->orderByRaw("
            CASE
                WHEN status = 'pending' THEN 1
                WHEN status = 'approved' THEN 2
                WHEN status = 'disapproved' THEN 3
                ELSE 4
            END
        ");

        $query->orderBy('created_at', 'desc');


        $requests = $query
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Counters
        |--------------------------------------------------------------------------
        */

        $pendingCount = AddPersonnelRequest::where(
            'status',
            'pending'
        )->count();

        $approvedCount = AddPersonnelRequest::where(
            'status',
            'approved'
        )->count();

        $disapprovedCount = AddPersonnelRequest::where(
            'status',
            'disapproved'
        )->count();


        return view(
            'add-personnel-requests.admin.index',
            compact(
                'requests',
                'search',
                'status',
                'pendingCount',
                'approvedCount',
                'disapprovedCount'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Super Admin - Review Personnel Request
    |--------------------------------------------------------------------------
    */

    public function adminShow(AddPersonnelRequest $personnelRequest)
    {
        $user = Auth::user();

        abort_unless(
            $user && $user->role === 'super_admin',
            403
        );

        $personnelRequest->load([
            'requester',
            'school',
            'plantilla',
            'reviewer',
            'createdUser',
        ]);

        return view(
            'add-personnel-requests.admin.show',
            compact('personnelRequest')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Super Admin - Approve Personnel Request
    |--------------------------------------------------------------------------
    */

    public function approve(
        Request $request,
        AddPersonnelRequest $personnelRequest
    ) {
        /*
        |--------------------------------------------------------------------------
        | Logged-in Super Admin
        |--------------------------------------------------------------------------
        */

        $admin = Auth::user();

        abort_unless(
            $admin && $admin->role === 'super_admin',
            403
        );


        /*
        |--------------------------------------------------------------------------
        | Validate Review Remarks
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'review_remarks' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Request Must Still Be Pending
        |--------------------------------------------------------------------------
        */

        if ($personnelRequest->status !== 'pending') {

            return back()->with(
                'error',
                'This personnel request has already been reviewed.'
            );
        }


        try {

            DB::transaction(function () use (
                $personnelRequest,
                $admin,
                $validated
            ) {

                /*
                |--------------------------------------------------------------------------
                | Lock Request
                |--------------------------------------------------------------------------
                |
                | Prevent two Super Admins from approving the same request
                | at the same time.
                |
                */

                $lockedRequest = AddPersonnelRequest::query()
                    ->where('id', $personnelRequest->id)
                    ->lockForUpdate()
                    ->firstOrFail();


                /*
                |--------------------------------------------------------------------------
                | Recheck Status
                |--------------------------------------------------------------------------
                */

                if ($lockedRequest->status !== 'pending') {

                    throw new \Exception(
                        'This personnel request has already been reviewed.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Birth Date Is Required
                |--------------------------------------------------------------------------
                |
                | Birth date is used as the initial password.
                |
                | Format:
                | MMDDYYYY
                |
                | Example:
                | April 3, 1995 = 04031995
                |
                */

                if (!$lockedRequest->birth_date) {

                    throw new \Exception(
                        'Birth date is required before this personnel request can be approved.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Recheck Email
                |--------------------------------------------------------------------------
                |
                | The email must remain unique because every personnel
                | receives their own PDMS user account.
                |
                */

                $emailExists = User::withTrashed()
                    ->whereRaw(
                        'LOWER(email) = ?',
                        [
                            strtolower(
                                trim($lockedRequest->email)
                            )
                        ]
                    )
                    ->exists();


                if ($emailExists) {

                    throw new \Exception(
                        'The email address already belongs to an existing PDMS account.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Plantilla Assignment
                |--------------------------------------------------------------------------
                |
                | No duplicate plantilla restriction.
                |
                | One plantilla_db_id may be assigned to multiple employees.
                |
                */


                /*
                |--------------------------------------------------------------------------
                | Build Complete Name
                |--------------------------------------------------------------------------
                */

                $nameParts = [
                    $lockedRequest->first_name,
                    $lockedRequest->middle_name,
                    $lockedRequest->last_name,
                    $lockedRequest->extension_name,
                ];


                $fullName = collect($nameParts)
                    ->filter(function ($value) {
                        return filled($value);
                    })
                    ->implode(' ');


                /*
                |--------------------------------------------------------------------------
                | Generate Default Password From Birth Date
                |--------------------------------------------------------------------------
                |
                | Format:
                | MMDDYYYY
                |
                */

                $defaultPassword =
                    $lockedRequest->birth_date->format('mdY');


                /*
                |--------------------------------------------------------------------------
                | Create Personnel User Account
                |--------------------------------------------------------------------------
                */

                $user = User::create([

                    'name' =>
                        $fullName,

                    'email' =>
                        strtolower(
                            trim($lockedRequest->email)
                        ),

                    'password' =>
                        Hash::make(
                            $defaultPassword
                        ),

                    'role' =>
                        'user',

                    'status' =>
                        'active',

                ]);


                /*
                |--------------------------------------------------------------------------
                | Create Basic Information
                |--------------------------------------------------------------------------
                */

                BasicInformation::create([

                    'users_id' =>
                        $user->id,

                    'first_name' =>
                        $lockedRequest->first_name,

                    'middle_name' =>
                        $lockedRequest->middle_name,

                    'last_name' =>
                        $lockedRequest->last_name,

                    'extension_name' =>
                        $lockedRequest->extension_name,

                    'sex' =>
                        $lockedRequest->sex,

                    'birth_place' =>
                        $lockedRequest->birth_place,

                    'birth_date' =>
                        $lockedRequest->birth_date,

                    'mobile_number' =>
                        $lockedRequest->mobile_number,

                    'specialization' =>
                        $lockedRequest->specialization,

                ]);


                /*
                |--------------------------------------------------------------------------
                | Create Employment Status
                |--------------------------------------------------------------------------
                |
                | Multiple employees may now have the same plantilla_db_id.
                |
                */

                EmploymentStatus::create([

                    'users_id' =>
                        $user->id,

                    'plantilla_db_id' =>
                        $lockedRequest->plantilla_db_id,

                    'school_db_id' =>
                        $lockedRequest->school_db_id,

                    'date_of_original_appointment' =>
                        $lockedRequest->date_of_original_appointment,

                    'date_of_last_promotion' =>
                        $lockedRequest->date_of_last_promotion,

                    'employment_status' =>
                        $lockedRequest->employment_status,

                    'warm_body_status' =>
                        $lockedRequest->warm_body_status,

                    'nature_of_work' =>
                        $lockedRequest->nature_of_work,

                    'source_of_fund' =>
                        $lockedRequest->source_of_fund,

                    'monthly_salary' =>
                        $lockedRequest->monthly_salary,

                    'contract_duration' =>
                        $lockedRequest->contract_duration,

                ]);


                /*
                |--------------------------------------------------------------------------
                | Mark Request As Approved
                |--------------------------------------------------------------------------
                */

                $lockedRequest->update([

                    'status' =>
                        'approved',

                    'review_remarks' =>
                        $validated['review_remarks'] ?? null,

                    'reviewed_by' =>
                        $admin->id,

                    'reviewed_at' =>
                        now(),

                    'created_user_id' =>
                        $user->id,

                ]);

            });


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('admin.personnel-requests.index')
                ->with(
                    'success',
                    'Personnel request approved successfully. The personnel account has been created. The initial password is the personnel\'s birth date in MMDDYYYY format.'
                );


        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Log Error
            |--------------------------------------------------------------------------
            */

            report($e);


            /*
            |--------------------------------------------------------------------------
            | Return Error
            |--------------------------------------------------------------------------
            */

            return back()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Super Admin - Disapprove Personnel Request
    |--------------------------------------------------------------------------
    */

    public function disapprove(Request $request,AddPersonnelRequest $personnelRequest) 
    {
        /*
        |--------------------------------------------------------------------------
        | Logged-in User
        |--------------------------------------------------------------------------
        */

        $admin = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        |
        | Only Super Admin can disapprove personnel requests.
        |
        */

        abort_unless(
            $admin && $admin->role === 'super_admin',
            403
        );


        /*
        |--------------------------------------------------------------------------
        | Validate Review Remarks
        |--------------------------------------------------------------------------
        |
        | Remarks are required when a request is disapproved.
        |
        */

        $validated = $request->validate([
            'review_remarks' => [
                'required',
                'string',
                'min:3',
                'max:2000',
            ],
        ], [
            'review_remarks.required' =>
                'Please provide a reason for disapproving this personnel request.',

            'review_remarks.min' =>
                'The disapproval reason must contain at least 3 characters.',
        ]);


        try {

            /*
            |--------------------------------------------------------------------------
            | Database Transaction
            |--------------------------------------------------------------------------
            */

            DB::transaction(function () use (
                $personnelRequest,
                $admin,
                $validated
            ) {

                /*
                |--------------------------------------------------------------------------
                | Lock Personnel Request
                |--------------------------------------------------------------------------
                |
                | This prevents another Super Admin from reviewing the same
                | request at the exact same time.
                |
                */

                $lockedRequest = AddPersonnelRequest::query()
                    ->where('id', $personnelRequest->id)
                    ->lockForUpdate()
                    ->firstOrFail();


                /*
                |--------------------------------------------------------------------------
                | Check Current Status
                |--------------------------------------------------------------------------
                |
                | Only pending requests can be disapproved.
                |
                */

                if ($lockedRequest->status !== 'pending') {

                    throw new \Exception(
                        'This personnel request has already been reviewed.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Disapprove Request
                |--------------------------------------------------------------------------
                */

                $lockedRequest->update([

                    'status' => 'disapproved',

                    'review_remarks' =>
                        trim($validated['review_remarks']),

                    'reviewed_by' =>
                        $admin->id,

                    'reviewed_at' =>
                        now(),

                    'created_user_id' =>
                        null,
                ]);
            });


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('admin.personnel-requests.index')
                ->with(
                    'success',
                    'Personnel request has been disapproved successfully.'
                );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Log Error
            |--------------------------------------------------------------------------
            */

            report($e);


            /*
            |--------------------------------------------------------------------------
            | Return Error
            |--------------------------------------------------------------------------
            */

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }


}