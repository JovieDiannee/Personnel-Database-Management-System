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
        $user = Auth::user();

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
        | Available Plantilla Items
        |--------------------------------------------------------------------------
        |
        | Remove items already assigned to existing personnel.
        | Remove items currently involved in a pending request.
        |
        */

        $usedPlantillaIds = EmploymentStatus::query()
            ->whereNotNull('plantilla_db_id')
            ->pluck('plantilla_db_id');

        $pendingPlantillaIds = AddPersonnelRequest::query()
            ->where('status', 'pending')
            ->whereNotNull('plantilla_db_id')
            ->pluck('plantilla_db_id');

        $plantillas = PlantillaDb::query()
            ->whereNotIn('id', $usedPlantillaIds)
            ->whereNotIn('id', $pendingPlantillaIds);

        /*
        |--------------------------------------------------------------------------
        | Restrict Admin to Plantilla Assigned to Their School
        |--------------------------------------------------------------------------
        |
        | Your plantilla_db uses area_code.
        | Your school_db uses school_id.
        |
        | If area_code contains the School ID for school-level plantilla,
        | this prevents Admin from selecting another school's item.
        |
        */

        if ($user->role === 'admin' && $school) {

            $plantillas->where(function ($query) use ($school) {

                $query->where('area_code', $school->school_id);
            });
        }

        $plantillas = $plantillas
            ->orderBy('position_title')
            ->orderBy('item_number')
            ->get();

        return view(
            'add-personnel-requests.create',
            compact('school', 'plantillas')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Admin - Submit Personnel Request
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $user = Auth::user();

        abort_unless(
            in_array($user->role, ['admin', 'super_admin']),
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
                'nullable',
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

            // Employment
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
        | IMPORTANT:
        | Never trust school_db_id submitted by an Admin.
        |
        */

        if ($user->role === 'admin') {

            $schoolId = $user->employmentStatus?->school_db_id;

            if (!$schoolId) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Your account is not assigned to a school.'
                    );
            }

        } else {

            /*
            |--------------------------------------------------------------------------
            | Temporary Super Admin Behavior
            |--------------------------------------------------------------------------
            |
            | For now, this module is primarily for Admin submissions.
            | We can add Super Admin school selection later.
            |
            */

            $schoolId = $user->employmentStatus?->school_db_id;

            if (!$schoolId) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'No school is assigned to this account.'
                    );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Check Existing Email
        |--------------------------------------------------------------------------
        */

        $existingUser = User::withTrashed()
            ->whereRaw('LOWER(email) = ?', [
                strtolower(trim($validated['email']))
            ])
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
            ->whereRaw('LOWER(email) = ?', [
                strtolower(trim($validated['email']))
            ])
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
        */

        if (!empty($validated['plantilla_db_id'])) {

            $plantilla = PlantillaDb::findOrFail(
                $validated['plantilla_db_id']
            );

            /*
            |--------------------------------------------------------------------------
            | Admin Cannot Use Another School's Plantilla
            |--------------------------------------------------------------------------
            */

            if ($user->role === 'admin') {

                $school = $user->employmentStatus?->school;

                if (
                    !$school ||
                    (string) $plantilla->area_code !== (string) $school->school_id
                ) {

                    abort(
                        403,
                        'You cannot assign a plantilla item from another school.'
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Check Existing Assignment
            |--------------------------------------------------------------------------
            */

            $alreadyUsed = EmploymentStatus::query()
                ->where(
                    'plantilla_db_id',
                    $plantilla->id
                )
                ->exists();

            if ($alreadyUsed) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'plantilla_db_id' =>
                            'This plantilla item is already assigned to another personnel.'
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Check Pending Request
            |--------------------------------------------------------------------------
            */

            $alreadyPending = AddPersonnelRequest::query()
                ->where(
                    'plantilla_db_id',
                    $plantilla->id
                )
                ->where('status', 'pending')
                ->exists();

            if ($alreadyPending) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'plantilla_db_id' =>
                            'This plantilla item already has a pending personnel request.'
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Save Request
        |--------------------------------------------------------------------------
        */

        AddPersonnelRequest::create([

            'requested_by' => $user->id,

            // Personal
            'first_name' => trim($validated['first_name']),
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => trim($validated['last_name']),
            'extension_name' => $validated['extension_name'] ?? null,

            'email' => strtolower(
                trim($validated['email'])
            ),

            'sex' => $validated['sex'] ?? null,
            'birth_place' => $validated['birth_place'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'mobile_number' => $validated['mobile_number'] ?? null,
            'specialization' => $validated['specialization'] ?? null,

            // Employment
            'school_db_id' => $schoolId,
            'plantilla_db_id' => $validated['plantilla_db_id'] ?? null,

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

            // Request
            'request_remarks' =>
                $validated['request_remarks'] ?? null,

            'status' => 'pending',
        ]);


        return redirect()
            ->route('add-personnel-requests.index')
            ->with(
                'success',
                'Personnel request submitted successfully. It is now pending Personnel Unit approval.'
            );
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

    public function approve( Request $request, AddPersonnelRequest $personnelRequest) 
    {
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
                | The birth date will be used as the initial password.
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
                | Recheck Plantilla
                |--------------------------------------------------------------------------
                */

                if ($lockedRequest->plantilla_db_id) {

                    $plantillaUsed = EmploymentStatus::query()
                        ->where(
                            'plantilla_db_id',
                            $lockedRequest->plantilla_db_id
                        )
                        ->exists();


                    if ($plantillaUsed) {

                        throw new \Exception(
                            'The selected plantilla item is already assigned to another personnel.'
                        );
                    }
                }


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
                | Example:
                | Birth Date: April 3, 1995
                | Password:   04031995
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

                    'name' => $fullName,

                    'email' => strtolower(
                        trim($lockedRequest->email)
                    ),

                    'password' => Hash::make(
                        $defaultPassword
                    ),

                    'role' => 'user',

                    'status' => 'active',
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

            report($e);


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