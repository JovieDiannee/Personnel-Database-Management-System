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
use App\Models\SchoolDb;
use Carbon\Carbon;
use App\Models\OfficeUnit;

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

        abort_unless(
            $user && in_array($user->role, ['admin', 'super_admin']),
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
        | Specializations
        |--------------------------------------------------------------------------
        */

        $specializations = [

            'Early Childhood Education',
            'English',
            'Filipino',
            'General Education',
            'Information and Communication Technology (ICT)',
            'Mathematics',
            'Music, Arts, PE, and Health (MAPEH)',

            'Science - Biology',
            'Science - Chemistry',
            'Science - General Science',
            'Science - Physical Science',
            'Science - Physics',

            'Social Studies / Social Sciences',
            'Special Education (SPED)',
            'Technology and Livelihood Education (TLE)',

            'TVL - Agricultural Crops Production',
            'TVL - Agroentrepreneurship',
            'TVL - Animal Production',
            'TVL - Bartending',
            'TVL - Beauty Care Services',
            'TVL - Bookkeeping',
            'TVL - Bread and Pastry Production',
            'TVL - Caregiving',
            'TVL - Carpentry',
            'TVL - Computer Systems Servicing',
            'TVL - Contact Center Services',
            'TVL - Cookery',
            'TVL - Creative Web Design',
            'TVL - Dressmaking',
            'TVL - Driving',
            'TVL - Electrical Installation and Maintenance',
            'TVL - Electronic Products Assembly and Servicing',
            'TVL - Events Management Services',
            'TVL - Food and Beverage Services',
            'TVL - Food Processing',
            'TVL - Front Office Services',
            'TVL - Hairdressing',
            'TVL - Household Services',
            'TVL - Housekeeping',
            'TVL - Landscape Installation and Maintenance',
            'TVL - Masonry',
            'TVL - Organic Agriculture Production',
            'TVL - Plumbing',
            'TVL - RAC Servicing (DomRAC)',
            'TVL - Rice Machinery Operations',
            'TVL - Security Services',
            'TVL - Shielded Metal Arc Welding',
            'TVL - Technical Drafting',
            'TVL - Technology and Livelihood Education (TLE)',
            'TVL - Tour Guiding Services',
            'TVL - Wellness Massage',

            'Values Education',
            'Guidance and Counseling',
            'Accountancy',
            'Architecture',
            'Statistics',
            'Civil Engineering',
            'Computer Engineering',
            'Electrical Engineering',
            'Electronics Engineering',
            'Mechanical Engineering',
            'Agricultural Engineering',
            'Human Resource Management',
            'Legal Management',
            'Management Accounting',
            'Marketing Management',
            'Nursing',
            'Office Administration',
            'Public Administration',
            'Business Administration',
            'Psychology',
            'Criminology',
            'Commerce',

            'Other',
            'Not Applicable',

        ];


        /*
        |--------------------------------------------------------------------------
        | Employment Status
        |--------------------------------------------------------------------------
        */

        $employmentStatuses = [

            'Permanent',
            'Provisional',
            'Temporary',
            'Contractual',
            'Casual',
            'Contract of Service',
            'Job Order',
            'LGU Deployed',

        ];


        /*
        |--------------------------------------------------------------------------
        | Warm Body Status
        |--------------------------------------------------------------------------
        */

        $warmBodyStatuses = [

            'Original',
            'Borrowed',
            'Detailed',
            'TIC',
            'ALS',
            'SNED',
            'Vacant (Retired)',
            'Vacant (Resigned)',
            'Vacant (Others)',

        ];


        /*
        |--------------------------------------------------------------------------
        | Nature of Work
        |--------------------------------------------------------------------------
        */

        $natureOfWorks = [

            'District Supervisor',
            'Teaching Services',
            'School Administration',
            'Administrative Support',
            'Clerical Services',
            'Driving Services',
            'Engineering Services',
            'Health and Allied Services',
            'IT Services',
            'Janitorial Services',
            'Legal Services',
            'Security Services',
            'Technical Services',
            'Labor Services',
            'Executive or Management Services',
            'Others',

        ];


        /*
        |--------------------------------------------------------------------------
        | Source of Fund
        |--------------------------------------------------------------------------
        */

        $sourceOfFunds = [

            'Plantilla',
            'MOOE/GMS',
            'LGU Funds',
            'LGU SEFs',
            'Program Support Funds',

        ];


        /*
        |--------------------------------------------------------------------------
        | Return Form
        |--------------------------------------------------------------------------
        |
        | Plantilla records are NOT loaded here.
        | They are searched using AJAX.
        |
        */

        return view(
            'add-personnel-requests.create',
            compact(
                'school',
                'specializations',
                'employmentStatuses',
                'warmBodyStatuses',
                'natureOfWorks',
                'sourceOfFunds'
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
        | Allowed Specializations
        |--------------------------------------------------------------------------
        */

        $specializations = [

            'Early Childhood Education',
            'English',
            'Filipino',
            'General Education',
            'Information and Communication Technology (ICT)',
            'Mathematics',
            'Music, Arts, PE, and Health (MAPEH)',

            'Science - Biology',
            'Science - Chemistry',
            'Science - General Science',
            'Science - Physical Science',
            'Science - Physics',

            'Social Studies / Social Sciences',
            'Special Education (SPED)',
            'Technology and Livelihood Education (TLE)',

            'TVL - Agricultural Crops Production',
            'TVL - Agroentrepreneurship',
            'TVL - Animal Production',
            'TVL - Bartending',
            'TVL - Beauty Care Services',
            'TVL - Bookkeeping',
            'TVL - Bread and Pastry Production',
            'TVL - Caregiving',
            'TVL - Carpentry',
            'TVL - Computer Systems Servicing',
            'TVL - Contact Center Services',
            'TVL - Cookery',
            'TVL - Creative Web Design',
            'TVL - Dressmaking',
            'TVL - Driving',
            'TVL - Electrical Installation and Maintenance',
            'TVL - Electronic Products Assembly and Servicing',
            'TVL - Events Management Services',
            'TVL - Food and Beverage Services',
            'TVL - Food Processing',
            'TVL - Front Office Services',
            'TVL - Hairdressing',
            'TVL - Household Services',
            'TVL - Housekeeping',
            'TVL - Landscape Installation and Maintenance',
            'TVL - Masonry',
            'TVL - Organic Agriculture Production',
            'TVL - Plumbing',
            'TVL - RAC Servicing (DomRAC)',
            'TVL - Rice Machinery Operations',
            'TVL - Security Services',
            'TVL - Shielded Metal Arc Welding',
            'TVL - Technical Drafting',
            'TVL - Technology and Livelihood Education (TLE)',
            'TVL - Tour Guiding Services',
            'TVL - Wellness Massage',

            'Values Education',
            'Guidance and Counseling',
            'Accountancy',
            'Architecture',
            'Statistics',
            'Civil Engineering',
            'Computer Engineering',
            'Electrical Engineering',
            'Electronics Engineering',
            'Mechanical Engineering',
            'Agricultural Engineering',
            'Human Resource Management',
            'Legal Management',
            'Management Accounting',
            'Marketing Management',
            'Nursing',
            'Office Administration',
            'Public Administration',
            'Business Administration',
            'Psychology',
            'Criminology',
            'Commerce',

            'Other',
            'Not Applicable',

        ];


        /*
        |--------------------------------------------------------------------------
        | Allowed Employment Status
        |--------------------------------------------------------------------------
        */

        $employmentStatuses = [

            'Permanent',
            'Provisional',
            'Temporary',
            'Contractual',
            'Casual',
            'Contract of Service',
            'Job Order',
            'LGU Deployed',

        ];


        /*
        |--------------------------------------------------------------------------
        | Allowed Warm Body Status
        |--------------------------------------------------------------------------
        */

        $warmBodyStatuses = [

            'Original',
            'Borrowed',
            'Detailed',
            'TIC',
            'ALS',
            'SNED',
            'Vacant (Retired)',
            'Vacant (Resigned)',
            'Vacant (Others)',

        ];


        /*
        |--------------------------------------------------------------------------
        | Allowed Nature of Work
        |--------------------------------------------------------------------------
        */

        $natureOfWorks = [

            'District Supervisor',
            'Teaching Services',
            'School Administration',
            'Administrative Support',
            'Clerical Services',
            'Driving Services',
            'Engineering Services',
            'Health and Allied Services',
            'IT Services',
            'Janitorial Services',
            'Legal Services',
            'Security Services',
            'Technical Services',
            'Labor Services',
            'Executive or Management Services',
            'Others',

        ];


        /*
        |--------------------------------------------------------------------------
        | Allowed Source of Fund
        |--------------------------------------------------------------------------
        */

        $sourceOfFunds = [

            'Plantilla',
            'MOOE/GMS',
            'LGU Funds',
            'LGU SEFs',
            'Program Support Funds',

        ];


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(

            [

                /*
                |--------------------------------------------------------------------------
                | Personal Information
                |--------------------------------------------------------------------------
                */

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
                    Rule::in([
                        'Male',
                        'Female',
                    ]),
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
                    Rule::in($specializations),
                ],


                /*
                |--------------------------------------------------------------------------
                | Employment Information
                |--------------------------------------------------------------------------
                */

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
                    Rule::in($employmentStatuses),
                ],

                'warm_body_status' => [
                    'nullable',
                    Rule::in($warmBodyStatuses),
                ],

                'nature_of_work' => [
                    'nullable',
                    Rule::in($natureOfWorks),
                ],


                /*
                |--------------------------------------------------------------------------
                | Source of Fund
                |--------------------------------------------------------------------------
                |
                | Always required.
                |
                */

                'source_of_fund' => [
                    'required',
                    Rule::in($sourceOfFunds),
                ],


                /*
                |--------------------------------------------------------------------------
                | Plantilla
                |--------------------------------------------------------------------------
                |
                | Required ONLY when Source of Fund = Plantilla.
                |
                | One Plantilla can still be assigned to multiple employees.
                |
                */

                'plantilla_db_id' => [

                    Rule::requiredIf(
                        fn () =>
                            $request->input('source_of_fund') === 'Plantilla'
                    ),

                    'nullable',
                    'integer',
                    'exists:plantilla_db,id',

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


                /*
                |--------------------------------------------------------------------------
                | Remarks
                |--------------------------------------------------------------------------
                */

                'request_remarks' => [
                    'nullable',
                    'string',
                    'max:2000',
                ],

            ],

            [

                /*
                |--------------------------------------------------------------------------
                | Custom Validation Messages
                |--------------------------------------------------------------------------
                */

                'source_of_fund.required' =>
                    'Please select the Source of Fund.',

                'plantilla_db_id.required' =>
                    'Please select a Plantilla Item when the Source of Fund is Plantilla.',

                'plantilla_db_id.exists' =>
                    'The selected Plantilla Item is invalid.',

            ]

        );


        /*
        |--------------------------------------------------------------------------
        | Determine Admin's School
        |--------------------------------------------------------------------------
        */

        $schoolId =
            $user->employmentStatus?->school_db_id;


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
        | Existing User Email
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
        | Existing Pending Request
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
        | Save Personnel Request
        |--------------------------------------------------------------------------
        */

        try {

            AddPersonnelRequest::create([

                /*
                |--------------------------------------------------------------------------
                | Requester
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
                    $validated['birth_date'],

                'mobile_number' =>
                    !empty($validated['mobile_number'])
                        ? trim($validated['mobile_number'])
                        : null,

                'specialization' =>
                    $validated['specialization'] ?? null,


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
                    $validated['source_of_fund'],

                'monthly_salary' =>
                    $validated['monthly_salary'] ?? null,

                'contract_duration' =>
                    !empty($validated['contract_duration'])
                        ? trim($validated['contract_duration'])
                        : null,


                /*
                |--------------------------------------------------------------------------
                | Request Information
                |--------------------------------------------------------------------------
                */

                'request_remarks' =>
                    !empty($validated['request_remarks'])
                        ? trim($validated['request_remarks'])
                        : null,

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

            report($e);


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

    public function searchPlantilla(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Security
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        abort_unless(
            $user && in_array($user->role, ['admin', 'super_admin']),
            403
        );


        /*
        |--------------------------------------------------------------------------
        | Search Keyword
        |--------------------------------------------------------------------------
        */

        $search = trim(
            $request->input('q', '')
        );


        /*
        |--------------------------------------------------------------------------
        | Require Search Input
        |--------------------------------------------------------------------------
        |
        | Don't return 21,000 records when the dropdown is opened.
        |
        */

        if (mb_strlen($search) < 2) {

            return response()->json([]);
        }


        /*
        |--------------------------------------------------------------------------
        | Search Plantilla
        |--------------------------------------------------------------------------
        |
        | Search by:
        | - Item Number
        | - Position Title
        | - Salary Grade
        |
        | Only return the first 30 matches.
        |
        */

        $plantillas = PlantillaDb::query()

            ->select([
                'id',
                'item_number',
                'position_title',
                'salary_grade',
            ])

            ->where(function ($query) use ($search) {

                $query
                    ->where(
                        'item_number',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'position_title',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'salary_grade',
                        'like',
                        "%{$search}%"
                    );

            })

            ->orderBy('item_number')

            ->limit(30)

            ->get()

            ->map(function ($plantilla) {

                $text = $plantilla->item_number;

                if ($plantilla->position_title) {

                    $text .=
                        ' - ' .
                        $plantilla->position_title;
                }


                return [

                    'value' =>
                        $plantilla->id,

                    'text' =>
                        $text,

                ];

            });


        return response()->json(
            $plantillas
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Super Admin - Create Personnel
    |--------------------------------------------------------------------------
    */

    public function createPersonnel()
    {
        /*
        |--------------------------------------------------------------------------
        | Super Admin Only
        |--------------------------------------------------------------------------
        */

        $admin = Auth::user();

        abort_unless(
            $admin && $admin->role === 'super_admin',
            403
        );


        /*
        |--------------------------------------------------------------------------
        | Schools
        |--------------------------------------------------------------------------
        |
        | Only the school records are loaded here.
        |
        | Plantilla records are NOT loaded because there are more than
        | 21,000 records. Plantilla searching will be handled through AJAX.
        |
        */

        $schools = SchoolDb::query()
            ->select([
                'id',
                'school_id',
                'school_name',
                'school_district',
            ])
            ->orderBy('school_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Office Units
        |--------------------------------------------------------------------------
        |
        | Load active Division Office units.
        | The office group and parent unit are included so the Super Admin
        | can easily identify the correct office assignment.
        |
        */

        $officeUnits = OfficeUnit::query()
            ->with([
                'officeGroup:id,code,name',
                'parent:id,name',
            ])
            ->where('is_active', true)
            ->orderBy('office_group_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Specializations
        |--------------------------------------------------------------------------
        */

        $specializations = [

            'Early Childhood Education',
            'English',
            'Filipino',
            'General Education',
            'Information and Communication Technology (ICT)',
            'Mathematics',
            'Music, Arts, PE, and Health (MAPEH)',

            'Science - Biology',
            'Science - Chemistry',
            'Science - General Science',
            'Science - Physical Science',
            'Science - Physics',

            'Social Studies / Social Sciences',
            'Special Education (SPED)',
            'Technology and Livelihood Education (TLE)',

            'TVL - Agricultural Crops Production',
            'TVL - Agroentrepreneurship',
            'TVL - Animal Production',
            'TVL - Bartending',
            'TVL - Beauty Care Services',
            'TVL - Bookkeeping',
            'TVL - Bread and Pastry Production',
            'TVL - Caregiving',
            'TVL - Carpentry',
            'TVL - Computer Systems Servicing',
            'TVL - Contact Center Services',
            'TVL - Cookery',
            'TVL - Creative Web Design',
            'TVL - Dressmaking',
            'TVL - Driving',
            'TVL - Electrical Installation and Maintenance',
            'TVL - Electronic Products Assembly and Servicing',
            'TVL - Events Management Services',
            'TVL - Food and Beverage Services',
            'TVL - Food Processing',
            'TVL - Front Office Services',
            'TVL - Hairdressing',
            'TVL - Household Services',
            'TVL - Housekeeping',
            'TVL - Landscape Installation and Maintenance',
            'TVL - Masonry',
            'TVL - Organic Agriculture Production',
            'TVL - Plumbing',
            'TVL - RAC Servicing (DomRAC)',
            'TVL - Rice Machinery Operations',
            'TVL - Security Services',
            'TVL - Shielded Metal Arc Welding',
            'TVL - Technical Drafting',
            'TVL - Technology and Livelihood Education (TLE)',
            'TVL - Tour Guiding Services',
            'TVL - Wellness Massage',

            'Values Education',
            'Guidance and Counseling',

            'Accountancy',
            'Architecture',
            'Statistics',

            'Civil Engineering',
            'Computer Engineering',
            'Electrical Engineering',
            'Electronics Engineering',
            'Mechanical Engineering',
            'Agricultural Engineering',

            'Human Resource Management',
            'Legal Management',
            'Management Accounting',
            'Marketing Management',

            'Nursing',
            'Office Administration',
            'Public Administration',
            'Business Administration',
            'Psychology',
            'Criminology',
            'Commerce',

            'Other',
            'Not Applicable',

        ];


        /*
        |--------------------------------------------------------------------------
        | Employment Status
        |--------------------------------------------------------------------------
        */

        $employmentStatuses = [

            'Permanent',
            'Provisional',
            'Temporary',
            'Contractual',
            'Casual',
            'Contract of Service',
            'Job Order',
            'LGU Deployed',

        ];


        /*
        |--------------------------------------------------------------------------
        | Warm Body Status
        |--------------------------------------------------------------------------
        */

        $warmBodyStatuses = [

            'Original',
            'Borrowed',
            'Detailed',
            'TIC',
            'ALS',
            'SNED',
            'Vacant (Retired)',
            'Vacant (Resigned)',
            'Vacant (Others)',

        ];


        /*
        |--------------------------------------------------------------------------
        | Nature of Work
        |--------------------------------------------------------------------------
        */

        $natureOfWorks = [

            'District Supervisor',
            'Teaching Services',
            'School Administration',
            'Administrative Support',
            'Clerical Services',
            'Driving Services',
            'Engineering Services',
            'Health and Allied Services',
            'IT Services',
            'Janitorial Services',
            'Legal Services',
            'Security Services',
            'Technical Services',
            'Labor Services',
            'Executive or Management Services',
            'Others',

        ];


        /*
        |--------------------------------------------------------------------------
        | Source of Fund
        |--------------------------------------------------------------------------
        */

        $sourceOfFunds = [

            'Plantilla',
            'MOOE/GMS',
            'LGU Funds',
            'LGU SEFs',
            'Program Support Funds',

        ];


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'add-personnel-requests.admin.create',
            compact(
                'schools',
                'officeUnits',
                'specializations',
                'employmentStatuses',
                'warmBodyStatuses',
                'natureOfWorks',
                'sourceOfFunds'
            )
        );
    }
 
    /*
    |--------------------------------------------------------------------------
    | Super Admin - Store Personnel
    |--------------------------------------------------------------------------
    */

    public function storePersonnel(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Super Admin Only
        |--------------------------------------------------------------------------
        */

        $admin = Auth::user();

        abort_unless(
            $admin && $admin->role === 'super_admin',
            403
        );


        /*
        |--------------------------------------------------------------------------
        | Allowed Specializations
        |--------------------------------------------------------------------------
        */

        $specializations = [

            'Early Childhood Education',
            'English',
            'Filipino',
            'General Education',
            'Information and Communication Technology (ICT)',
            'Mathematics',
            'Music, Arts, PE, and Health (MAPEH)',

            'Science - Biology',
            'Science - Chemistry',
            'Science - General Science',
            'Science - Physical Science',
            'Science - Physics',

            'Social Studies / Social Sciences',
            'Special Education (SPED)',
            'Technology and Livelihood Education (TLE)',

            'TVL - Agricultural Crops Production',
            'TVL - Agroentrepreneurship',
            'TVL - Animal Production',
            'TVL - Bartending',
            'TVL - Beauty Care Services',
            'TVL - Bookkeeping',
            'TVL - Bread and Pastry Production',
            'TVL - Caregiving',
            'TVL - Carpentry',
            'TVL - Computer Systems Servicing',
            'TVL - Contact Center Services',
            'TVL - Cookery',
            'TVL - Creative Web Design',
            'TVL - Dressmaking',
            'TVL - Driving',
            'TVL - Electrical Installation and Maintenance',
            'TVL - Electronic Products Assembly and Servicing',
            'TVL - Events Management Services',
            'TVL - Food and Beverage Services',
            'TVL - Food Processing',
            'TVL - Front Office Services',
            'TVL - Hairdressing',
            'TVL - Household Services',
            'TVL - Housekeeping',
            'TVL - Landscape Installation and Maintenance',
            'TVL - Masonry',
            'TVL - Organic Agriculture Production',
            'TVL - Plumbing',
            'TVL - RAC Servicing (DomRAC)',
            'TVL - Rice Machinery Operations',
            'TVL - Security Services',
            'TVL - Shielded Metal Arc Welding',
            'TVL - Technical Drafting',
            'TVL - Technology and Livelihood Education (TLE)',
            'TVL - Tour Guiding Services',
            'TVL - Wellness Massage',

            'Values Education',
            'Guidance and Counseling',

            'Accountancy',
            'Architecture',
            'Statistics',

            'Civil Engineering',
            'Computer Engineering',
            'Electrical Engineering',
            'Electronics Engineering',
            'Mechanical Engineering',
            'Agricultural Engineering',

            'Human Resource Management',
            'Legal Management',
            'Management Accounting',
            'Marketing Management',

            'Nursing',
            'Office Administration',
            'Public Administration',
            'Business Administration',
            'Psychology',
            'Criminology',
            'Commerce',

            'Other',
            'Not Applicable',

        ];


        /*
        |--------------------------------------------------------------------------
        | Employment Status
        |--------------------------------------------------------------------------
        */

        $employmentStatuses = [

            'Permanent',
            'Provisional',
            'Temporary',
            'Contractual',
            'Casual',
            'Contract of Service',
            'Job Order',
            'LGU Deployed',

        ];


        /*
        |--------------------------------------------------------------------------
        | Warm Body Status
        |--------------------------------------------------------------------------
        */

        $warmBodyStatuses = [

            'Original',
            'Borrowed',
            'Detailed',
            'TIC',
            'ALS',
            'SNED',
            'Vacant (Retired)',
            'Vacant (Resigned)',
            'Vacant (Others)',

        ];


        /*
        |--------------------------------------------------------------------------
        | Nature of Work
        |--------------------------------------------------------------------------
        */

        $natureOfWorks = [

            'District Supervisor',
            'Teaching Services',
            'School Administration',
            'Administrative Support',
            'Clerical Services',
            'Driving Services',
            'Engineering Services',
            'Health and Allied Services',
            'IT Services',
            'Janitorial Services',
            'Legal Services',
            'Security Services',
            'Technical Services',
            'Labor Services',
            'Executive or Management Services',
            'Others',

        ];


        /*
        |--------------------------------------------------------------------------
        | Source of Fund
        |--------------------------------------------------------------------------
        */

        $sourceOfFunds = [

            'Plantilla',
            'MOOE/GMS',
            'LGU Funds',
            'LGU SEFs',
            'Program Support Funds',

        ];


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(

            [

                /*
                |--------------------------------------------------------------------------
                | Personal Information
                |--------------------------------------------------------------------------
                */

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

                /*
                |--------------------------------------------------------------------------
                | Email
                |--------------------------------------------------------------------------
                |
                | Kept as string validation instead of Laravel's email validation
                | because your system may accept international characters such
                | as ñ in addresses.
                |
                */

                'email' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'sex' => [
                    'nullable',
                    Rule::in([
                        'Male',
                        'Female',
                    ]),
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
                    Rule::in($specializations),
                ],


                /*
                |--------------------------------------------------------------------------
                | Personnel Assignment
                |--------------------------------------------------------------------------
                |
                | assignment_type = school
                |     school_db_id required
                |     office_unit_id must be NULL
                |
                | assignment_type = office
                |     office_unit_id required
                |     school_db_id must be NULL
                |
                */

                'assignment_type' => [
                    'required',
                    Rule::in([
                        'school',
                        'office',
                    ]),
                ],


                'school_db_id' => [

                    Rule::requiredIf(
                        fn () =>
                            $request->input('assignment_type') === 'school'
                    ),

                    'nullable',
                    'integer',
                    'exists:school_db,id',
                ],


                'office_unit_id' => [

                    Rule::requiredIf(
                        fn () =>
                            $request->input('assignment_type') === 'office'
                    ),

                    'nullable',
                    'integer',
                    'exists:office_units,id',
                ],


                /*
                |--------------------------------------------------------------------------
                | Source of Fund
                |--------------------------------------------------------------------------
                |
                | Source of Fund is always required.
                |
                */

                'source_of_fund' => [
                    'required',
                    Rule::in($sourceOfFunds),
                ],


                /*
                |--------------------------------------------------------------------------
                | Plantilla Item
                |--------------------------------------------------------------------------
                |
                | Required ONLY if:
                |
                | Source of Fund = Plantilla
                |
                | There is intentionally NO unique rule here.
                | One plantilla item can be assigned to multiple employees.
                |
                */

                'plantilla_db_id' => [

                    Rule::requiredIf(
                        fn () =>
                            $request->input('source_of_fund') === 'Plantilla'
                    ),

                    'nullable',
                    'integer',
                    'exists:plantilla_db,id',

                ],


                /*
                |--------------------------------------------------------------------------
                | Employment Information
                |--------------------------------------------------------------------------
                */

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
                    Rule::in($employmentStatuses),
                ],

                'warm_body_status' => [
                    'nullable',
                    Rule::in($warmBodyStatuses),
                ],

                'nature_of_work' => [
                    'nullable',
                    Rule::in($natureOfWorks),
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

            ],

            [

                /*
                |--------------------------------------------------------------------------
                | Custom Validation Messages
                |--------------------------------------------------------------------------
                */

                'first_name.required' =>
                    'First Name is required.',

                'last_name.required' =>
                    'Last Name is required.',

                'email.required' =>
                    'Email Address is required.',

                'birth_date.required' =>
                    'Birth Date is required.',

                'assignment_type.required' =>
                    'Please select an assignment type.',

                'assignment_type.in' =>
                    'The selected assignment type is invalid.',


                'school_db_id.required' =>
                    'Please select a school for school-based personnel.',

                'school_db_id.exists' =>
                    'The selected school is invalid.',


                'office_unit_id.required' =>
                    'Please select an Office Unit for Division Office personnel.',

                'office_unit_id.exists' =>
                    'The selected Office Unit is invalid.',

                'source_of_fund.required' =>
                    'Please select the Source of Fund.',

                'source_of_fund.in' =>
                    'The selected Source of Fund is invalid.',

                'plantilla_db_id.required' =>
                    'Please select a Plantilla Item when the Source of Fund is Plantilla.',

                'plantilla_db_id.exists' =>
                    'The selected Plantilla Item is invalid.',

            ]

        );

        /*
        |--------------------------------------------------------------------------
        | Normalize Assignment
        |--------------------------------------------------------------------------
        */

        if ($validated['assignment_type'] === 'school') {

            $validated['office_unit_id'] = null;

        } else {

            $validated['school_db_id'] = null;
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
        |
        | Include soft-deleted accounts.
        |
        */

        $emailExists = User::withTrashed()
            ->whereRaw(
                'LOWER(email) = ?',
                [$email]
            )
            ->exists();


        if ($emailExists) {

            return back()
                ->withInput()
                ->withErrors([

                    'email' =>
                        'This email address already belongs to an existing PDMS account.',

                ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Create Personnel
        |--------------------------------------------------------------------------
        */

        try {

            DB::transaction(function () use (
                $validated,
                $email,
                $admin
            ) {


                /*
                |--------------------------------------------------------------------------
                | Complete Name
                |--------------------------------------------------------------------------
                */

                $fullName = collect([

                    $validated['first_name'],

                    $validated['middle_name'] ?? null,

                    $validated['last_name'],

                    $validated['extension_name'] ?? null,

                ])
                ->filter(
                    fn ($value) =>
                        filled($value)
                )
                ->map(
                    fn ($value) =>
                        trim($value)
                )
                ->implode(' ');


                /*
                |--------------------------------------------------------------------------
                | Initial Password
                |--------------------------------------------------------------------------
                |
                | Format:
                |
                | MMDDYYYY
                |
                | Example:
                |
                | Birth Date: January 15, 1995
                | Password:   01151995
                |
                */

                $birthDate = Carbon::parse(
                    $validated['birth_date']
                );


                $defaultPassword =
                    $birthDate->format('mdY');


                /*
                |--------------------------------------------------------------------------
                | Create User Account
                |--------------------------------------------------------------------------
                */

                $personnelUser = User::create([

                    'name' =>
                        $fullName,

                    'email' =>
                        $email,

                    'password' =>
                        Hash::make($defaultPassword),

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
                        $personnelUser->id,

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

                    'sex' =>
                        $validated['sex'] ?? null,

                    'birth_place' =>
                        !empty($validated['birth_place'])
                            ? trim($validated['birth_place'])
                            : null,

                    'birth_date' =>
                        $validated['birth_date'],

                    'mobile_number' =>
                        !empty($validated['mobile_number'])
                            ? trim($validated['mobile_number'])
                            : null,

                    'specialization' =>
                        $validated['specialization'] ?? null,

                ]);


                /*
                |--------------------------------------------------------------------------
                | Create Employment Status
                |--------------------------------------------------------------------------
                |
                | There is no check to determine whether another employee already
                | uses the same plantilla_db_id.
                |
                | Therefore:
                |
                | One Plantilla Item -> Multiple Personnel
                |
                */

                EmploymentStatus::create([

                'users_id' =>
                    $personnelUser->id,

                'plantilla_db_id' =>
                    $validated['plantilla_db_id'] ?? null,


                /*
                |--------------------------------------------------------------------------
                | Assignment
                |--------------------------------------------------------------------------
                */

                'school_db_id' =>
                    $validated['assignment_type'] === 'school'
                        ? ($validated['school_db_id'] ?? null)
                        : null,

                'office_unit_id' =>
                    $validated['assignment_type'] === 'office'
                        ? ($validated['office_unit_id'] ?? null)
                        : null,


                /*
                |--------------------------------------------------------------------------
                | Employment Information
                |--------------------------------------------------------------------------
                */

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
                    $validated['source_of_fund'],

                'monthly_salary' =>
                    $validated['monthly_salary'] ?? null,

                'contract_duration' =>
                    !empty($validated['contract_duration'])
                        ? trim($validated['contract_duration'])
                        : null,

            ]);


                /*
                |--------------------------------------------------------------------------
                | Save Super Admin Action History
                |--------------------------------------------------------------------------
                |
                | We use AddPersonnelRequest as the history record.
                |
                | requested_by  = Super Admin who added the employee
                | created_user  = Employee account that was created
                | reviewed_by   = Same Super Admin
                |
                | The status is immediately APPROVED because personnel created
                | directly by the Super Admin does not require another approval.
                |
                */

                AddPersonnelRequest::create([

                    /*
                    |--------------------------------------------------------------------------
                    | Audit Information
                    |--------------------------------------------------------------------------
                    */

                    'requested_by' =>
                        $admin->id,

                    'created_user_id' =>
                        $personnelUser->id,


                    /*
                    |--------------------------------------------------------------------------
                    | Personal Information Snapshot
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
                        $validated['birth_date'],

                    'mobile_number' =>
                        !empty($validated['mobile_number'])
                            ? trim($validated['mobile_number'])
                            : null,

                    'specialization' =>
                        $validated['specialization'] ?? null,


                    /*
                    |--------------------------------------------------------------------------
                    | Employment Snapshot
                    |--------------------------------------------------------------------------
                    */

                    'school_db_id' =>
                        $validated['school_db_id'],

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
                        $validated['source_of_fund'],

                    'monthly_salary' =>
                        $validated['monthly_salary'] ?? null,

                    'contract_duration' =>
                        !empty($validated['contract_duration'])
                            ? trim($validated['contract_duration'])
                            : null,


                    /*
                    |--------------------------------------------------------------------------
                    | Immediately Approved
                    |--------------------------------------------------------------------------
                    */

                    'status' =>
                        'approved',

                    'reviewed_by' =>
                        $admin->id,

                    'reviewed_at' =>
                        now(),

                    'review_remarks' =>
                        'Personnel directly added by Super Admin.',

                ]);

            });


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('data-management.employment-status')
                ->with(
                    'success',
                    'Personnel added successfully. The account was created immediately without approval.'
                );

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to add personnel: ' . $e->getMessage()
                );
        }
    }


}