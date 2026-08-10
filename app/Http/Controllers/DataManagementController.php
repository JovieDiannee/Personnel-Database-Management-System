<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PersonnelInformationImport;
use App\Imports\PlantillaImport;
use App\Imports\SchoolImport;
use App\Imports\EmploymentStatusImport;
use App\Imports\MedicalAllowanceImport;
use App\Imports\EnrollmentImport;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\MedicalAllowance;
use App\Models\Enrollment;
use App\Models\SchoolDb;
use App\Models\SchoolYear;
use App\Models\GradeLevel;
use Carbon\Carbon;

class DataManagementController extends Controller
{
    public function index()
    {
        return view('data-management.index');
    }

    /*   
    |   END  OF INDEX FUNCTIONS
    |
    |--------------------------------------------------------------------------
    |  
    |   START OF EMPLOYMENT STATUS RECORDS FUNCTIONS
    */

    public function personnel()
    {
        $personnel = \App\Models\BasicInformation::with('user')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(20);
        return view('data-management.personnel', compact('personnel'));
    }

    // public function viewPersonnel()
    // {
    //     $personnel = \App\Models\BasicInformation::with('user')
    //         ->orderBy('last_name')
    //         ->orderBy('first_name')
    //         ->paginate(20);

    //     return view('data-management.personnel-list', compact('personnel'));
    // }

    public function importPersonnel(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
                'max:10240',
            ],
        ]);

        $collections = Excel::toCollection(
            new PersonnelInformationImport,
            $request->file('file')
        );

        $rows = $collections->first();

        if ($rows->isEmpty()) {
            return back()
                ->withErrors([
                    'file' => 'The uploaded Excel file contains no records.'
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Required Excel Columns
        |--------------------------------------------------------------------------
        */

        $requiredColumns = [
            'email',
            'first_name',
            'middle_name',
            'last_name',
            'extension_name',
            'sex',
            'birth_place',
            'birth_date',
            'civil_status',
            'religion',
            'citizenship',
            'mode_of_citizenship',
            'height_m',
            'weight_kg',
            'blood_type',
            'mobile_number',
            'telephone_number',
            'specialization',
            // Address
            'address_type',
            'street',
            'brgy',
            'subd_village',
            'municipality_city',
            'province',
            'zip_postal',
            // Government IDs
            'umid_no',
            'gsis_no',
            'philsys_no',
            'pagibig_no',
            'tin_no',
            'philhealth_no',
            'employee_id',
        ];

        $firstRow = $rows->first();

        $missingColumns = [];

        foreach ($requiredColumns as $column) {
            if (!array_key_exists($column, $firstRow->toArray())) {
                $missingColumns[] = $column;
            }
        }

        if (!empty($missingColumns)) {
            return back()
                ->withErrors([
                    'file' => 'The Excel file is missing the following columns: '
                        . implode(', ', $missingColumns)
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Individual Rows
        |--------------------------------------------------------------------------
        */

        $previewRows = [];
        $errors = [];

        foreach ($rows as $index => $row) {

            $excelRow = $index + 2;

            $email = trim((string) ($row['email'] ?? ''));
            $firstName = trim((string) ($row['first_name'] ?? ''));
            $lastName = trim((string) ($row['last_name'] ?? ''));

            if ($email === '') {
                $errors[] = "Row {$excelRow}: Email is required.";
            }

            if ($firstName === '') {
                $errors[] = "Row {$excelRow}: First name is required.";
            }

            if ($lastName === '') {
                $errors[] = "Row {$excelRow}: Last name is required.";
            }

            if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Row {$excelRow}: Invalid email address.";
            }

            $birthDate = $this->parseBirthDate($row['birth_date'] ?? null);

            $previewRows[] = [
                'excel_row' => $excelRow,
                'email' => $email,
                'first_name' => $firstName,
                'middle_name' => $row['middle_name'] ?? null,
                'last_name' => $lastName,
                'extension_name' => $row['extension_name'] ?? null,
                'sex' => $row['sex'] ?? null,
                'birth_place' => $row['birth_place'] ?? null,
                'birth_date' => $birthDate,
                'civil_status' => $row['civil_status'] ?? null,
                'religion' => $row['religion'] ?? null,
                'citizenship' => $row['citizenship'] ?? null,
                'mode_of_citizenship' => $row['mode_of_citizenship'] ?? null,
                'height_m' => $row['height_m'] ?? null,
                'weight_kg' => $row['weight_kg'] ?? null,
                'blood_type' => $row['blood_type'] ?? null,
                'mobile_number' => $row['mobile_number'] ?? null,
                'telephone_number' => $row['telephone_number'] ?? null,
                'specialization' => $row['specialization'] ?? null,
                // Address
                'address_type' => $row['address_type'] ?? null,
                'street' => $row['street'] ?? null,
                'brgy' => $row['brgy'] ?? null,
                'subd_village' => $row['subd_village'] ?? null,
                'municipality_city' => $row['municipality_city'] ?? null,
                'province' => $row['province'] ?? null,
                'zip_postal' => $row['zip_postal'] ?? null,
                // Government IDs
                'umid_no' => $row['umid_no'] ?? null,
                'gsis_no' => $row['gsis_no'] ?? null,
                'philsys_no' => $row['philsys_no'] ?? null,
                'pagibig_no' => $row['pagibig_no'] ?? null,
                'tin_no' => $row['tin_no'] ?? null,
                'philhealth_no' => $row['philhealth_no'] ?? null,
                'employee_id' => $row['employee_id'] ?? null,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Store Import Records in Session
        |--------------------------------------------------------------------------
        |
        | These records will be used when the user clicks
        | "Confirm Import".
        |
        */

        session([
            'personnel_import_records' => $previewRows,
            'personnel_import_errors' => $errors,
        ]);

        return redirect()->route(
            'data-management.personnel.import.preview'
        );
    }

    public function personnelImportPreview()
    {
        $rows = session('personnel_import_records', []);

        $errors = session('personnel_import_errors', []);

        if (empty($rows)) {

            return redirect()
                ->route('data-management.personnel')
                ->withErrors([
                    'file' => 'No personnel import data is available for preview.'
                ]);
        }

        return view(
            'data-management.personnel-preview',
            [
                'rows' => $rows,
                'errors' => $errors,
            ]
        );
    }

    private function parseBirthDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        // Excel serial date
        if (is_numeric($value)) {
            try {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        // dd/mm/yyyy
        try {
            return \Carbon\Carbon::createFromFormat('d/m/Y', trim($value))
                ->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Store Information in the Database
    |--------------------------------------------------------------------------
    */

    public function confirmPersonnelImport(Request $request)
    {
        $records = session('personnel_import_records');

        if (!$records || count($records) === 0) {

            return redirect()
                ->route('data-management.personnel')
                ->with('error', 'No personnel records available for import.');
        }

        $imported = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        foreach ($records as $index => $record) {

            $excelRow = $record['excel_row'] ?? ($index + 2);

            try {

                /*
                |--------------------------------------------------------------------------
                | EMAIL
                |--------------------------------------------------------------------------
                */

                $email = trim((string) ($record['email'] ?? ''));

                if ($email === '') {

                    $skipped++;

                    $errors[] = [
                        'row' => $excelRow,
                        'message' => 'Email address is missing.'
                    ];

                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | BIRTH DATE
                |--------------------------------------------------------------------------
                */

                $birthDate = $record['birth_date'] ?? null;

                if (empty($birthDate)) {

                    $skipped++;

                    $errors[] = [
                        'row' => $excelRow,
                        'message' => 'Birth date is missing.'
                    ];

                    continue;
                }


                try {

                    $birthDateObject = Carbon::parse($birthDate);

                } catch (\Throwable $e) {

                    $skipped++;

                    $errors[] = [
                        'row' => $excelRow,
                        'message' => 'Invalid birth date: ' . $birthDate
                    ];

                    continue;
                }


                $formattedBirthDate = $birthDateObject->format('Y-m-d');

                /*
                |--------------------------------------------------------------------------
                | DEFAULT PASSWORD
                |
                | Example:
                |
                | 1990-08-21
                |
                | 08211990
                |--------------------------------------------------------------------------
                */

                $defaultPassword = $birthDateObject->format('mdY');


                /*
                |--------------------------------------------------------------------------
                | FULL NAME
                |--------------------------------------------------------------------------
                */

                $fullName = trim(
                    ($record['first_name'] ?? '') . ' ' .
                    ($record['middle_name'] ?? '') . ' ' .
                    ($record['last_name'] ?? '') . ' ' .
                    ($record['extension_name'] ?? '')
                );


                /*
                |--------------------------------------------------------------------------
                | FIND USER
                |--------------------------------------------------------------------------
                */

                $user = User::where('email', $email)->first();


                /*
                |--------------------------------------------------------------------------
                | USER
                |--------------------------------------------------------------------------
                */

                if (!$user) {

                    /*
                    |------------------------------------------------------------------
                    | CREATE NEW USER
                    |------------------------------------------------------------------
                    */

                    $user = User::create([
                        'name' => $fullName,
                        'email' => $email,
                        'password' => Hash::make($defaultPassword),
                    ]);

                    /*
                    |------------------------------------------------------------------
                    | Set default role
                    |------------------------------------------------------------------
                    */

                    $user->role = 'user';
                    $user->save();

                    $imported++;

                } else {

                    /*
                    |------------------------------------------------------------------
                    | EXISTING USER
                    |
                    | DO NOT CHANGE:
                    | - email
                    | - password
                    | - role
                    |------------------------------------------------------------------
                    */

                    $updated++;
                }


                /*
                |--------------------------------------------------------------------------
                | BASIC INFORMATION
                |--------------------------------------------------------------------------
                */

                $basicInformation = DB::table('basic_information')
                    ->where('users_id', $user->id)
                    ->first();


                if ($basicInformation) {

                    /*
                    |------------------------------------------------------------------
                    | UPDATE BASIC INFORMATION
                    |------------------------------------------------------------------
                    */

                    DB::table('basic_information')
                        ->where('id', $basicInformation->id)
                        ->update([

                            'first_name' => $record['first_name'] ?? null,
                            'middle_name' => $record['middle_name'] ?? null,
                            'last_name' => $record['last_name'] ?? null,
                            'extension_name' => $record['extension_name'] ?? null,

                            'sex' => $record['sex'] ?? null,
                            'birth_place' => $record['birth_place'] ?? null,
                            'birth_date' => $formattedBirthDate,

                            'civil_status' => $record['civil_status'] ?? null,
                            'religion' => $record['religion'] ?? null,
                            'citizenship' => $record['citizenship'] ?? null,
                            'mode_of_citizenship' => $record['mode_of_citizenship'] ?? null,

                            'height_m' => $record['height_m'] ?? null,
                            'weight_kg' => $record['weight_kg'] ?? null,
                            'blood_type' => $record['blood_type'] ?? null,

                            'mobile_number' => $record['mobile_number'] ?? null,
                            'telephone_number' => $record['telephone_number'] ?? null,

                            'specialization' => $record['specialization'] ?? null,

                            'updated_at' => now(),

                        ]);

                    $basicInformationId = $basicInformation->id;

                } else {

                    /*
                    |------------------------------------------------------------------
                    | CREATE BASIC INFORMATION
                    |------------------------------------------------------------------
                    */

                    $basicInformationId = DB::table('basic_information')
                        ->insertGetId([

                            'users_id' => $user->id,

                            'first_name' => $record['first_name'] ?? null,
                            'middle_name' => $record['middle_name'] ?? null,
                            'last_name' => $record['last_name'] ?? null,
                            'extension_name' => $record['extension_name'] ?? null,

                            'sex' => $record['sex'] ?? null,
                            'birth_place' => $record['birth_place'] ?? null,
                            'birth_date' => $formattedBirthDate,

                            'civil_status' => $record['civil_status'] ?? null,
                            'religion' => $record['religion'] ?? null,
                            'citizenship' => $record['citizenship'] ?? null,
                            'mode_of_citizenship' => $record['mode_of_citizenship'] ?? null,

                            'height_m' => $record['height_m'] ?? null,
                            'weight_kg' => $record['weight_kg'] ?? null,
                            'blood_type' => $record['blood_type'] ?? null,

                            'mobile_number' => $record['mobile_number'] ?? null,
                            'telephone_number' => $record['telephone_number'] ?? null,

                            'specialization' => $record['specialization'] ?? null,

                            'created_at' => now(),
                            'updated_at' => now(),

                        ]);
                }


                /*
                |--------------------------------------------------------------------------
                | ADDRESS
                |--------------------------------------------------------------------------
                */

                $address = DB::table('address')
                    ->where('basic_information_id', $basicInformationId)
                    ->first();


                $addressData = [

                    'type' => $record['address_type'] ?? null,
                    'street' => $record['street'] ?? null,
                    'brgy' => $record['brgy'] ?? null,
                    'subd_village' => $record['subd_village'] ?? null,
                    'municipality_city' => $record['municipality_city'] ?? null,
                    'province' => $record['province'] ?? null,
                    'zip_postal' => $record['zip_postal'] ?? null,

                    'updated_at' => now(),

                ];


                if ($address) {

                    DB::table('address')
                        ->where('id', $address->id)
                        ->update($addressData);

                } else {

                    DB::table('address')
                        ->insert([

                            'basic_information_id' => $basicInformationId,

                            'type' => $record['address_type'] ?? null,
                            'street' => $record['street'] ?? null,
                            'brgy' => $record['brgy'] ?? null,
                            'subd_village' => $record['subd_village'] ?? null,
                            'municipality_city' => $record['municipality_city'] ?? null,
                            'province' => $record['province'] ?? null,
                            'zip_postal' => $record['zip_postal'] ?? null,

                            'created_at' => now(),
                            'updated_at' => now(),

                        ]);
                }


                /*
                |--------------------------------------------------------------------------
                | ISSUED IDs
                |--------------------------------------------------------------------------
                */

                $issuedId = DB::table('issued_id')
                    ->where('basic_information_id', $basicInformationId)
                    ->first();


                $issuedIdData = [

                    'umid_no' => $record['umid_no'] ?? null,
                    'gsis_no' => $record['gsis_no'] ?? null,
                    'philsys_no' => $record['philsys_no'] ?? null,
                    'pagibig_no' => $record['pagibig_no'] ?? null,
                    'tin_no' => $record['tin_no'] ?? null,
                    'philhealth_no' => $record['philhealth_no'] ?? null,
                    'employee_id' => $record['employee_id'] ?? null,

                    'updated_at' => now(),

                ];


                if ($issuedId) {

                    DB::table('issued_id')
                        ->where('id', $issuedId->id)
                        ->update($issuedIdData);

                } else {

                    DB::table('issued_id')
                        ->insert([

                            'basic_information_id' => $basicInformationId,

                            'umid_no' => $record['umid_no'] ?? null,
                            'gsis_no' => $record['gsis_no'] ?? null,
                            'philsys_no' => $record['philsys_no'] ?? null,
                            'pagibig_no' => $record['pagibig_no'] ?? null,
                            'tin_no' => $record['tin_no'] ?? null,
                            'philhealth_no' => $record['philhealth_no'] ?? null,
                            'employee_id' => $record['employee_id'] ?? null,

                            'created_at' => now(),
                            'updated_at' => now(),

                        ]);
                }


            } catch (\Throwable $e) {

                /*
                |--------------------------------------------------------------------------
                | IMPORTANT:
                | SHOW THE REAL DATABASE ERROR
                |--------------------------------------------------------------------------
                */

                $skipped++;

                $errors[] = [

                    'row' => $excelRow,

                    'message' => $e->getMessage(),

                ];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | CLEAR SESSION
        |--------------------------------------------------------------------------
        */

        session()->forget('personnel_import_records');


        /*
        |--------------------------------------------------------------------------
        | RETURN RESULT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('data-management.personnel')
            ->with('import_result', [

                'imported' => $imported,

                'updated' => $updated,

                'skipped' => $skipped,

                'errors' => $errors,

            ]);
    }



    
    /*   
    |   END  OF PERSONNEL INFORMATION FUNCTIONS
    |
    |--------------------------------------------------------------------------
    |  
    |   START OF EMPLOYMENT STATUS RECORDS FUNCTIONS
    */


    public function employmentStatus(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        $search = trim(
            $request->input('search', '')
        );


        /*
        |--------------------------------------------------------------------------
        | Employment Status Records
        |--------------------------------------------------------------------------
        */

        $employmentStatuses = \App\Models\EmploymentStatus::with([
            'user.basicInformation',
            'plantilla',
            'school',
        ])

        /*
        |--------------------------------------------------------------------------
        | Search Personnel
        |--------------------------------------------------------------------------
        */

        ->when($search !== '', function ($query) use ($search) {

            $query->where(function ($q) use ($search) {

                /*
                | Search by email
                */

                $q->whereHas('user', function ($userQuery) use ($search) {

                    $userQuery->where(
                        'email',
                        'like',
                        "%{$search}%"
                    );

                });


                /*
                | Search by personnel name
                */

                $q->orWhereHas('user.basicInformation', function ($basicQuery) use ($search) {

                    $basicQuery
                        ->where('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");

                });


                /*
                | Search by plantilla item number
                */

                $q->orWhereHas('plantilla', function ($plantillaQuery) use ($search) {

                    $plantillaQuery
                        ->where(
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


                /*
                | Search by school
                */

                $q->orWhereHas('school', function ($schoolQuery) use ($search) {

                    $schoolQuery
                        ->where(
                            'school_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'school_id',
                            'like',
                            "%{$search}%"
                        );

                });


                /*
                | Search by employment status
                */

                $q->orWhere(
                    'employment_status',
                    'like',
                    "%{$search}%"
                );

            });

        })


        /*
        |--------------------------------------------------------------------------
        | Sort
        |--------------------------------------------------------------------------
        */

        ->latest('updated_at')


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        ->paginate(10)


        /*
        |--------------------------------------------------------------------------
        | Preserve Search
        |--------------------------------------------------------------------------
        */

        ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'data-management.employment-status',
            compact(
                'employmentStatuses',
                'search'
            )
        );
    }

    public function importEmploymentStatus(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
                'max:10240',
            ],
        ]);

        $collections = Excel::toCollection(
            new EmploymentStatusImport,
            $request->file('file')
        );

        $rows = $collections->first();

        if (!$rows || $rows->isEmpty()) {
            return back()
                ->withErrors([
                    'file' => 'The uploaded Excel file contains no records.'
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Required Excel Columns
        |--------------------------------------------------------------------------
        */

        $requiredColumns = [
            'email',
            'item_number',
            'school_id',
            'date_of_original_appointment',
            'date_of_last_promotion',
            'employment_status',
            'warm_body_status',
            'nature_of_work',
            'source_of_fund',
            'monthly_salary',
            'contract_duration',
        ];

        $firstRow = $rows->first()->toArray();

        $missingColumns = [];

        foreach ($requiredColumns as $column) {

            if (!array_key_exists($column, $firstRow)) {
                $missingColumns[] = $column;
            }
        }

        if (!empty($missingColumns)) {

            return back()
                ->withErrors([
                    'file' =>
                        'The Excel file is missing the following columns: '
                        . implode(', ', $missingColumns)
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Prepare Preview
        |--------------------------------------------------------------------------
        */

        $previewRows = [];
        $errors = [];

        foreach ($rows as $index => $row) {

            $excelRow = $index + 2;

            /*
            |--------------------------------------------------------------------------
            | Email
            |--------------------------------------------------------------------------
            */

            $email = trim(
                (string) ($row['email'] ?? '')
            );

            if ($email === '') {

                $errors[] = [
                    'row' => $excelRow,
                    'message' => 'Email address is required.'
                ];

                continue;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $errors[] = [
                    'row' => $excelRow,
                    'message' => "Invalid email address: {$email}"
                ];

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Find User
            |--------------------------------------------------------------------------
            */

            $user = DB::table('users')
                ->where('email', $email)
                ->first();

            if (!$user) {

                $errors[] = [
                    'row' => $excelRow,
                    'message' =>
                        "Email {$email} does not exist in the users table."
                ];

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Plantilla Item Number
            |--------------------------------------------------------------------------
            */

            $plantillaItemNumber = trim(
                (string) ($row['item_number'] ?? '')
            );

            $plantillaId = null;

            if ($plantillaItemNumber !== '') {

                $plantilla = DB::table('plantilla_db')
                    ->where(
                        'item_number',
                        $plantillaItemNumber
                    )
                    ->first();

                if (!$plantilla) {

                    $errors[] = [
                        'row' => $excelRow,
                        'message' =>
                            "Plantilla item number {$plantillaItemNumber} does not exist."
                    ];

                    continue;
                }

                $plantillaId = $plantilla->id;
            }

            /*
            |--------------------------------------------------------------------------
            | School
            |--------------------------------------------------------------------------
            */

            $schoolCode = trim(
                (string) ($row['school_id'] ?? '')
            );

            $schoolDbId = null;

            if ($schoolCode !== '') {

                $school = DB::table('school_db')
                    ->where(
                        'school_id',
                        $schoolCode
                    )
                    ->first();

                if (!$school) {

                    $errors[] = [
                        'row' => $excelRow,
                        'message' =>
                            "School ID {$schoolCode} does not exist in the school database."
                    ];

                    continue;
                }

                $schoolDbId = $school->id;
            }

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            $originalAppointment = null;

            if (!empty($row['date_of_original_appointment'])) {

                try {

                    $originalAppointment = $this->parseImportDate(
                        $row['date_of_original_appointment']
                    );

                } catch (\Throwable $e) {

                    $errors[] = [
                        'row' => $excelRow,
                        'message' =>
                            'Invalid date of original appointment.'
                    ];

                    continue;
                }
            }

            $lastPromotion = null;

            if (!empty($row['date_of_last_promotion'])) {

                try {

                    $lastPromotion = $this->parseImportDate(
                        $row['date_of_last_promotion']
                    );

                } catch (\Throwable $e) {

                    $errors[] = [
                        'row' => $excelRow,
                        'message' =>
                            'Invalid date of last promotion.'
                    ];

                    continue;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Existing Employment Record
            |--------------------------------------------------------------------------
            */

            $existingEmployment = DB::table('employment_status')
                ->where('users_id', $user->id)
                ->first();

            /*
            |--------------------------------------------------------------------------
            | Preview Row
            |--------------------------------------------------------------------------
            */

            $previewRows[] = [

                'excel_row' => $excelRow,

                'user_id' => $user->id,

                'name' => $user->name,

                'email' => $email,

                'item_number' =>
                    $plantillaItemNumber,

                'plantilla_db_id' =>
                    $plantillaId,

                'school_id' =>
                    $schoolCode,

                'school_db_id' =>
                    $schoolDbId,

                'date_of_original_appointment' =>
                    $originalAppointment,

                'date_of_last_promotion' =>
                    $lastPromotion,

                'employment_status' =>
                    trim((string) (
                        $row['employment_status'] ?? ''
                    )),

                'warm_body_status' =>
                    trim((string) (
                        $row['warm_body_status'] ?? ''
                    )),

                'nature_of_work' =>
                    trim((string) (
                        $row['nature_of_work'] ?? ''
                    )),

                'source_of_fund' =>
                    trim((string) (
                        $row['source_of_fund'] ?? ''
                    )),

                'monthly_salary' =>
                    $row['monthly_salary'] ?? null,

                'contract_duration' =>
                    trim((string) (
                        $row['contract_duration'] ?? ''
                    )),

                'action' =>
                    $existingEmployment
                        ? 'Update'
                        : 'New',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Store Preview Data in Session
        |--------------------------------------------------------------------------
        */

        session([
            'employment_status_import_records' =>
                $previewRows,

            'employment_status_import_errors' =>
                $errors,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Go to Preview
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'data-management.employment-status.import.preview'
        );
    }

    private function parseImportDate($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Excel Numeric Date
        |--------------------------------------------------------------------------
        */

        if (is_numeric($value)) {

            return Carbon::createFromDate(
                1899,
                12,
                30
            )
            ->addDays((int) $value)
            ->format('Y-m-d');
        }

        /*
        |--------------------------------------------------------------------------
        | DD/MM/YYYY
        |--------------------------------------------------------------------------
        */

        try {

            return Carbon::createFromFormat(
                'd/m/Y',
                trim((string) $value)
            )->format('Y-m-d');

        } catch (\Throwable $e) {
            //
        }

        /*
        |--------------------------------------------------------------------------
        | Other Common Formats
        |--------------------------------------------------------------------------
        */

        try {

            return Carbon::parse($value)
                ->format('Y-m-d');

        } catch (\Throwable $e) {

            throw new \Exception(
                "Unable to parse date: {$value}"
            );
        }
    }

    public function employmentStatusImportPreview()
    {
        $records = session('employment_status_import_records', []);

        $errors = session('employment_status_import_errors', []);

        return view(
            'data-management.employment-status-preview',
            [
                'rows' => $records,
                'errors' => $errors,
            ]
        );
    }

    public function confirmEmploymentStatusImport(Request $request)
    {
        $records = session('employment_status_import_records');

        if (!$records || count($records) === 0) {

            return redirect()
                ->route('data-management.employment-status')
                ->with('error', 'No employment records available for import.');
        }

        $imported = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        DB::beginTransaction();

        try {

            foreach ($records as $index => $record) {

                try {

                    /*
                    |--------------------------------------------------------------------------
                    | Find User
                    |--------------------------------------------------------------------------
                    */

                    $email = trim(
                        $record['email'] ?? ''
                    );

                    if ($email === '') {

                        $skipped++;

                        $errors[] = [
                            'row' =>
                                $record['excel_row'] ?? ($index + 2),

                            'message' =>
                                'Email address is missing.'
                        ];

                        continue;
                    }

                    $user = DB::table('users')
                        ->where('email', $email)
                        ->first();

                    if (!$user) {

                        $skipped++;

                        $errors[] = [
                            'row' =>
                                $record['excel_row'] ?? ($index + 2),

                            'message' =>
                                "Email {$email} does not exist in the users table."
                        ];

                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Check Existing Employment Record
                    |--------------------------------------------------------------------------
                    */

                    $existing = DB::table('employment_status')
                        ->where('users_id', $user->id)
                        ->first();

                    /*
                    |--------------------------------------------------------------------------
                    | Employment Data
                    |--------------------------------------------------------------------------
                    */

                    $employmentData = [

                        'plantilla_db_id' =>
                            $record['plantilla_db_id'] ?? null,

                        'school_db_id' =>
                            $record['school_db_id'] ?? null,

                        'date_of_original_appointment' =>
                            $record['date_of_original_appointment'] ?? null,

                        'date_of_last_promotion' =>
                            $record['date_of_last_promotion'] ?? null,

                        'employment_status' =>
                            $record['employment_status'] ?? null,

                        'warm_body_status' =>
                            $record['warm_body_status'] ?? null,

                        'nature_of_work' =>
                            $record['nature_of_work'] ?? null,

                        'source_of_fund' =>
                            $record['source_of_fund'] ?? null,

                        'monthly_salary' =>
                            $record['monthly_salary'] !== ''
                                ? $record['monthly_salary']
                                : null,

                        'contract_duration' =>
                            $record['contract_duration'] ?? null,

                        'updated_at' => now(),
                    ];

                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE EXISTING RECORD
                    |--------------------------------------------------------------------------
                    */

                    if ($existing) {

                        DB::table('employment_status')
                            ->where('id', $existing->id)
                            ->update($employmentData);

                        $updated++;

                    }

                    /*
                    |--------------------------------------------------------------------------
                    | CREATE NEW RECORD
                    |--------------------------------------------------------------------------
                    */

                    else {

                        $employmentData['users_id'] =
                            $user->id;

                        $employmentData['created_at'] =
                            now();

                        DB::table('employment_status')
                            ->insert($employmentData);

                        $imported++;
                    }

                } catch (\Throwable $e) {

                    $skipped++;

                    $errors[] = [
                        'row' =>
                            $record['excel_row'] ?? ($index + 2),

                        'message' =>
                            $e->getMessage()
                    ];
                }
            }

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | Remove Temporary Session Data
            |--------------------------------------------------------------------------
            */

            session()->forget(
                'employment_status_import_records'
            );

            session()->forget(
                'employment_status_import_errors'
            );

            /*
            |--------------------------------------------------------------------------
            | Return Result
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('data-management.employment-status')
                ->with('employment_import_result', [

                    'imported' => $imported,

                    'updated' => $updated,

                    'skipped' => $skipped,

                    'errors' => $errors,
                ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return redirect()
                ->route('data-management.employment-status')
                ->with(
                    'error',
                    'Employment Status import failed: '
                    . $e->getMessage()
                );
        }
    }


    /*   
    |   END  OF EMPLOYMENT STATUS RECORDS FUNCTIONS
    |
    |--------------------------------------------------------------------------
    |  
    |   START OF PLANTILLA POSITION RECORDS FUNCTIONS
    */


    public function plantilla()
    {
        $plantillas = DB::table('plantilla_db')
        ->orderBy('position_title')
        ->orderBy('item_number')
        ->paginate(10);

        return view('data-management.plantilla', compact('plantillas'));
    }


    public function importPlantilla(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Uploaded File
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
                'max:10240',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Read Excel File
        |--------------------------------------------------------------------------
        */

        $collections = Excel::toCollection(
            new PlantillaImport,
            $request->file('file')
        );

        $rows = $collections->first();


        /*
        |--------------------------------------------------------------------------
        | Check Empty File
        |--------------------------------------------------------------------------
        */

        if ($rows->isEmpty()) {

            return back()
                ->withErrors([
                    'file' => 'The uploaded Excel file contains no records.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Required Excel Columns
        |--------------------------------------------------------------------------
        */

        $requiredColumns = [
            'item_number',
            'item_from',
            'item_from_school_level',
            'position_title',
            'salary_grade',
            'area_code',
            'area_type',
            'plantilla_level',
            'pppa_attribution',
        ];


        /*
        |--------------------------------------------------------------------------
        | Check Excel Headers
        |--------------------------------------------------------------------------
        */

        $firstRow = $rows->first();

        $firstRowArray = $firstRow->toArray();

        $missingColumns = [];


        foreach ($requiredColumns as $column) {

            if (!array_key_exists($column, $firstRowArray)) {

                $missingColumns[] = $column;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Stop if Columns Are Missing
        |--------------------------------------------------------------------------
        */

        if (!empty($missingColumns)) {

            return back()
                ->withErrors([
                    'file' =>
                        'The Excel file is missing the following columns: '
                        . implode(', ', $missingColumns)
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Prepare Preview Records
        |--------------------------------------------------------------------------
        */

        $previewRows = [];

        $errors = [];


        foreach ($rows as $index => $row) {

            /*
            |--------------------------------------------------------------------------
            | Excel Row Number
            |--------------------------------------------------------------------------
            */

            $excelRow = $index + 2;


            /*
            |--------------------------------------------------------------------------
            | Read Values
            |--------------------------------------------------------------------------
            */

            $itemNumber = trim(
                (string) ($row['item_number'] ?? '')
            );

            $positionTitle = trim(
                (string) ($row['position_title'] ?? '')
            );


            /*
            |--------------------------------------------------------------------------
            | Required Field Validation
            |--------------------------------------------------------------------------
            */

            if ($positionTitle === '') {

                $errors[] = [
                    'row' => $excelRow,
                    'message' => 'Position title is required.'
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | Salary Grade
            |--------------------------------------------------------------------------
            */

            $salaryGrade = $row['salary_grade'] ?? null;

            if (
                $salaryGrade !== null &&
                $salaryGrade !== '' &&
                !is_numeric($salaryGrade)
            ) {

                $errors[] = [
                    'row' => $excelRow,
                    'message' => 'Salary grade must be numeric.'
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | Prepare Preview Data
            |--------------------------------------------------------------------------
            */

            $previewRows[] = [

                'excel_row' => $excelRow,

                'item_number' =>
                    $itemNumber ?: null,

                'item_from' =>
                    trim((string) ($row['item_from'] ?? '')) ?: null,

                'item_from_school_level' =>
                    trim(
                        (string) ($row['item_from_school_level'] ?? '')
                    ) ?: null,

                'position_title' =>
                    $positionTitle,

                'salary_grade' =>
                    $salaryGrade !== ''
                        ? $salaryGrade
                        : null,

                'area_code' =>
                    trim((string) ($row['area_code'] ?? '')) ?: null,

                'area_type' =>
                    trim((string) ($row['area_type'] ?? '')) ?: null,

                'plantilla_level' =>
                    trim(
                        (string) ($row['plantilla_level'] ?? '')
                    ) ?: null,

                'pppa_attribution' =>
                    trim(
                        (string) ($row['pppa_attribution'] ?? '')
                    ) ?: null,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Store Temporary Import Data
        |--------------------------------------------------------------------------
        */

        session([
            'plantilla_import_records' => $previewRows,

            'plantilla_import_errors' => $errors,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Redirect to Preview
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'data-management.plantilla.import.preview'
        );
    }

    public function plantillaImportPreview()
    {
        $records = session('plantilla_import_records', []);

        $errors = session('plantilla_import_errors', []);


        if (empty($records)) {

            return redirect()
                ->route('data-management.plantilla')
                ->with('error', 'No plantilla records available for preview.');
        }


        return view(
            'data-management.plantilla-preview',
            [
                'rows' => $records,
                'errors' => $errors,
            ]
        );
    }

    public function confirmPlantillaImport(Request $request)
    {
        $records = session('plantilla_import_records');


        /*
        |--------------------------------------------------------------------------
        | Check Temporary Import Data
        |--------------------------------------------------------------------------
        */

        if (!$records || count($records) === 0) {

            return redirect()
                ->route('data-management.plantilla')
                ->with(
                    'error',
                    'No plantilla records available for import.'
                );
        }


        $imported = 0;

        $updated = 0;

        $skipped = 0;

        $errors = [];


        DB::beginTransaction();


        try {

            foreach ($records as $index => $record) {

                try {

                    /*
                    |--------------------------------------------------------------------------
                    | Values
                    |--------------------------------------------------------------------------
                    */

                    $itemNumber = trim(
                        (string) ($record['item_number'] ?? '')
                    );

                    $positionTitle = trim(
                        (string) ($record['position_title'] ?? '')
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Position Title Required
                    |--------------------------------------------------------------------------
                    */

                    if ($positionTitle === '') {

                        $skipped++;

                        $errors[] = [
                            'row' =>
                                $record['excel_row']
                                ?? ($index + 2),

                            'message' =>
                                'Position title is required.'
                        ];

                        continue;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Find Existing Record
                    |--------------------------------------------------------------------------
                    |
                    | If item_number exists, update the existing record.
                    |
                    */

                    $existing = null;


                    if ($itemNumber !== '') {

                        $existing = DB::table('plantilla_db')
                            ->where('item_number', $itemNumber)
                            ->first();
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Data
                    |--------------------------------------------------------------------------
                    */

                    $data = [

                        'item_number' =>
                            $itemNumber ?: null,

                        'item_from' =>
                            $record['item_from'] ?? null,

                        'item_from_school_level' =>
                            $record['item_from_school_level']
                            ?? null,

                        'position_title' =>
                            $positionTitle,

                        'salary_grade' =>
                            $record['salary_grade'] ?? null,

                        'area_code' =>
                            $record['area_code'] ?? null,

                        'area_type' =>
                            $record['area_type'] ?? null,

                        'plantilla_level' =>
                            $record['plantilla_level'] ?? null,

                        'pppa_attribution' =>
                            $record['pppa_attribution'] ?? null,

                        'updated_at' => now(),
                    ];


                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE Existing Record
                    |--------------------------------------------------------------------------
                    */

                    if ($existing) {

                        DB::table('plantilla_db')
                            ->where('id', $existing->id)
                            ->update($data);

                        $updated++;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | INSERT New Record
                    |--------------------------------------------------------------------------
                    */

                    else {

                        $data['created_at'] = now();

                        DB::table('plantilla_db')
                            ->insert($data);

                        $imported++;
                    }


                } catch (\Throwable $e) {

                    $skipped++;

                    $errors[] = [
                        'row' =>
                            $record['excel_row']
                            ?? ($index + 2),

                        'message' =>
                            $e->getMessage()
                    ];
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Commit Transaction
            |--------------------------------------------------------------------------
            */

            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | Remove Temporary Session Data
            |--------------------------------------------------------------------------
            */

            session()->forget([
                'plantilla_import_records',
                'plantilla_import_errors',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Import Result
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('data-management.plantilla')
                ->with('import_result', [

                    'imported' => $imported,

                    'updated' => $updated,

                    'skipped' => $skipped,

                    'errors' => $errors,

                ]);


        } catch (\Throwable $e) {

            DB::rollBack();


            return redirect()
                ->route('data-management.plantilla')
                ->with(
                    'error',
                    'Plantilla import failed: '
                    . $e->getMessage()
                );
        }
    }

    /*   
    |   END  OF PLANTILLA POSITION RECORDS FUNCTIONS
    |
    |--------------------------------------------------------------------------
    |  
    |   START OF SCHOOL INFORMATION RECORDS FUNCTIONS
    */


    public function schools()
    {
        $schools = DB::table('school_db')
        ->orderBy('school_name')
        ->paginate(10);

        return view('data-management.schools', compact('schools'));
    }

    public function importSchools(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Uploaded File
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
                'max:10240',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Read Excel File
        |--------------------------------------------------------------------------
        */

        $collections = Excel::toCollection(
            new SchoolImport,
            $request->file('file')
        );

        $rows = $collections->first();


        /*
        |--------------------------------------------------------------------------
        | Check Empty File
        |--------------------------------------------------------------------------
        */

        if ($rows->isEmpty()) {

            return back()
                ->withErrors([
                    'file' => 'The uploaded Excel file contains no records.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Required Excel Columns
        |--------------------------------------------------------------------------
        */

        $requiredColumns = [
            'school_id',
            'school_name',
            'school_area',
            'legislative_district',
            'school_district',
            'school_municipality',
            'school_sector',
            'school_curricular_offering',
        ];


        /*
        |--------------------------------------------------------------------------
        | Check Excel Headers
        |--------------------------------------------------------------------------
        */

        $firstRow = $rows->first();

        $firstRowArray = $firstRow->toArray();

        $missingColumns = [];


        foreach ($requiredColumns as $column) {

            if (!array_key_exists($column, $firstRowArray)) {

                $missingColumns[] = $column;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Missing Columns
        |--------------------------------------------------------------------------
        */

        if (!empty($missingColumns)) {

            return back()
                ->withErrors([
                    'file' =>
                        'The Excel file is missing the following columns: '
                        . implode(', ', $missingColumns)
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Prepare Preview Records
        |--------------------------------------------------------------------------
        */

        $previewRows = [];

        $errors = [];


        foreach ($rows as $index => $row) {

            /*
            |--------------------------------------------------------------------------
            | Excel Row Number
            |--------------------------------------------------------------------------
            */

            $excelRow = $index + 2;


            /*
            |--------------------------------------------------------------------------
            | Read Required Values
            |--------------------------------------------------------------------------
            */

            $schoolId = trim(
                (string) ($row['school_id'] ?? '')
            );

            $schoolName = trim(
                (string) ($row['school_name'] ?? '')
            );


            /*
            |--------------------------------------------------------------------------
            | Validate School ID
            |--------------------------------------------------------------------------
            */

            if ($schoolId === '') {

                $errors[] = [
                    'row' => $excelRow,
                    'message' => 'School ID is required.'
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | Validate School Name
            |--------------------------------------------------------------------------
            */

            if ($schoolName === '') {

                $errors[] = [
                    'row' => $excelRow,
                    'message' => 'School name is required.'
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | Prepare Preview Data
            |--------------------------------------------------------------------------
            */

            $previewRows[] = [

                'excel_row' => $excelRow,

                'school_id' =>
                    $schoolId ?: null,

                'school_name' =>
                    $schoolName,

                'school_area' =>
                    trim(
                        (string) ($row['school_area'] ?? '')
                    ) ?: null,

                'legislative_district' =>
                    trim(
                        (string) ($row['legislative_district'] ?? '')
                    ) ?: null,

                'school_district' =>
                    trim(
                        (string) ($row['school_district'] ?? '')
                    ) ?: null,

                'school_municipality' =>
                    trim(
                        (string) ($row['school_municipality'] ?? '')
                    ) ?: null,

                'school_sector' =>
                    trim(
                        (string) ($row['school_sector'] ?? '')
                    ) ?: null,

                'school_curricular_offering' =>
                    trim(
                        (string) ($row['school_curricular_offering'] ?? '')
                    ) ?: null,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Store Temporary Import Data
        |--------------------------------------------------------------------------
        */

        session([
            'school_import_records' => $previewRows,

            'school_import_errors' => $errors,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Redirect to Preview
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'data-management.schools.import.preview'
        );
    }

    public function schoolImportPreview()
    {
        $records = session('school_import_records', []);

        $errors = session('school_import_errors', []);


        if (empty($records)) {

            return redirect()
                ->route('data-management.schools')
                ->with(
                    'error',
                    'No school records available for preview.'
                );
        }


        return view(
            'data-management.school-preview',
            [
                'rows' => $records,
                'errors' => $errors,
            ]
        );
    }

    public function confirmSchoolImport(Request $request)
    {
        $records = session('school_import_records');


        /*
        |--------------------------------------------------------------------------
        | Check Temporary Import Data
        |--------------------------------------------------------------------------
        */

        if (!$records || count($records) === 0) {

            return redirect()
                ->route('data-management.schools')
                ->with(
                    'error',
                    'No school records available for import.'
                );
        }


        $imported = 0;

        $updated = 0;

        $skipped = 0;

        $errors = [];


        DB::beginTransaction();


        try {

            foreach ($records as $index => $record) {

                try {

                    /*
                    |--------------------------------------------------------------------------
                    | Values
                    |--------------------------------------------------------------------------
                    */

                    $schoolId = trim(
                        (string) ($record['school_id'] ?? '')
                    );

                    $schoolName = trim(
                        (string) ($record['school_name'] ?? '')
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Required Fields
                    |--------------------------------------------------------------------------
                    */

                    if ($schoolId === '') {

                        $skipped++;

                        $errors[] = [
                            'row' =>
                                $record['excel_row']
                                ?? ($index + 2),

                            'message' =>
                                'School ID is required.'
                        ];

                        continue;
                    }


                    if ($schoolName === '') {

                        $skipped++;

                        $errors[] = [
                            'row' =>
                                $record['excel_row']
                                ?? ($index + 2),

                            'message' =>
                                'School name is required.'
                        ];

                        continue;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Find Existing School
                    |--------------------------------------------------------------------------
                    */

                    $existing = DB::table('school_db')
                        ->where('school_id', $schoolId)
                        ->first();


                    /*
                    |--------------------------------------------------------------------------
                    | Prepare Data
                    |--------------------------------------------------------------------------
                    */

                    $data = [

                        'school_id' =>
                            $schoolId,

                        'school_name' =>
                            $schoolName,

                        'school_area' =>
                            $record['school_area'] ?? null,

                        'legislative_district' =>
                            $record['legislative_district']
                            ?? null,

                        'school_district' =>
                            $record['school_district']
                            ?? null,

                        'school_municipality' =>
                            $record['school_municipality']
                            ?? null,

                        'school_sector' =>
                            $record['school_sector']
                            ?? null,

                        'school_curricular_offering' =>
                            $record['school_curricular_offering']
                            ?? null,

                        'updated_at' => now(),
                    ];


                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE Existing School
                    |--------------------------------------------------------------------------
                    */

                    if ($existing) {

                        DB::table('school_db')
                            ->where('id', $existing->id)
                            ->update($data);

                        $updated++;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | INSERT New School
                    |--------------------------------------------------------------------------
                    */

                    else {

                        $data['created_at'] = now();

                        DB::table('school_db')
                            ->insert($data);

                        $imported++;
                    }


                } catch (\Throwable $e) {

                    $skipped++;

                    $errors[] = [
                        'row' =>
                            $record['excel_row']
                            ?? ($index + 2),

                        'message' =>
                            $e->getMessage()
                    ];
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Commit
            |--------------------------------------------------------------------------
            */

            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | Remove Temporary Session
            |--------------------------------------------------------------------------
            */

            session()->forget([
                'school_import_records',
                'school_import_errors',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Return Result
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('data-management.schools')
                ->with('import_result', [

                    'imported' => $imported,

                    'updated' => $updated,

                    'skipped' => $skipped,

                    'errors' => $errors,

                ]);


        } catch (\Throwable $e) {

            DB::rollBack();


            return redirect()
                ->route('data-management.schools')
                ->with(
                    'error',
                    'School import failed: '
                    . $e->getMessage()
                );
        }
    }

    /*   
    |   END  OF SCHOOL INFORMATION RECORDS FUNCTIONS
    |
    |--------------------------------------------------------------------------
    |  
    |   START OF MEDICAL ALLOWANCE RECORDS FUNCTIONS
    */

    public function medicalAllowance(Request $request)
    {
        $search = trim($request->input('search', ''));

        $medicalAllowances = MedicalAllowance::with([
            'user.basicInformation',
            'user.employmentStatus.plantilla',
            'user.employmentStatus.school',
        ])
            ->when($search !== '', function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    /*
                    |--------------------------------------------------------------------------
                    | Search Personnel
                    |--------------------------------------------------------------------------
                    */

                    $q->whereHas('user', function ($userQuery) use ($search) {

                        $userQuery->where(
                            'email',
                            'like',
                            "%{$search}%"
                        );

                    });


                    $q->orWhereHas(
                        'user.basicInformation',
                        function ($basicQuery) use ($search) {

                            $basicQuery->where(function ($nameQuery) use ($search) {

                                $nameQuery
                                    ->where(
                                        'first_name',
                                        'like',
                                        "%{$search}%"
                                    )
                                    ->orWhere(
                                        'middle_name',
                                        'like',
                                        "%{$search}%"
                                    )
                                    ->orWhere(
                                        'last_name',
                                        'like',
                                        "%{$search}%"
                                    );

                            });

                        }
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Search Medical Allowance
                    |--------------------------------------------------------------------------
                    */

                    $q->orWhere(
                        'mode_of_availment',
                        'like',
                        "%{$search}%"
                    );

                    $q->orWhere(
                        'disbursement_status',
                        'like',
                        "%{$search}%"
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Search Employment Status
                    |--------------------------------------------------------------------------
                    */

                    $q->orWhereHas(
                        'user.employmentStatus',
                        function ($employmentQuery) use ($search) {

                            $employmentQuery->where(
                                'employment_status',
                                'like',
                                "%{$search}%"
                            );

                        }
                    );

                });

            })
            ->latest()
            ->paginate(10)
            ->withQueryString();


        return view(
            'data-management.medical-allowance',
            compact(
                'medicalAllowances',
                'search'
            )
        );
    }

    public function importMedicalAllowance(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
                'max:10240',
            ],
        ]);

        $collections = Excel::toCollection(
            new MedicalAllowanceImport,
            $request->file('file')
        );

        $rows = $collections->first();

        if ($rows->isEmpty()) {
            return back()
                ->withErrors([
                    'file' => 'The uploaded Excel file contains no records.'
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Required Excel Columns
        |--------------------------------------------------------------------------
        */

        $requiredColumns = [
            'email',
            'mode_of_availment',
            'disbursement_status',
        ];

        $firstRow = $rows->first();

        $missingColumns = [];

        foreach ($requiredColumns as $column) {

            if (!array_key_exists($column, $firstRow->toArray())) {
                $missingColumns[] = $column;
            }

        }

        if (!empty($missingColumns)) {

            return back()
                ->withErrors([
                    'file' =>
                        'The Excel file is missing the following columns: '
                        . implode(', ', $missingColumns)
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Prepare Preview Records
        |--------------------------------------------------------------------------
        */

        $previewRows = [];
        $errors = [];

        foreach ($rows as $index => $row) {

            $excelRow = $index + 2;

            $email = trim(
                (string) ($row['email'] ?? '')
            );

            $modeOfAvailment = trim(
                (string) ($row['mode_of_availment'] ?? '')
            );

            $disbursementStatus = trim(
                (string) ($row['disbursement_status'] ?? '')
            );

            /*
            |--------------------------------------------------------------------------
            | Validate Email
            |--------------------------------------------------------------------------
            */

            if ($email === '') {

                $errors[] = [
                    'row' => $excelRow,
                    'message' => 'Email is required.'
                ];

            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $errors[] = [
                    'row' => $excelRow,
                    'message' => "Invalid email address: {$email}."
                ];

            }


            /*
            |--------------------------------------------------------------------------
            | Find User
            |--------------------------------------------------------------------------
            */

            $user = null;

            if ($email !== '') {

                $user = User::where('email', $email)->first();

                if (!$user) {

                    $errors[] = [
                        'row' => $excelRow,
                        'message' => "No personnel account found for {$email}."
                    ];

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Preview Row
            |--------------------------------------------------------------------------
            */

            $previewRows[] = [

                'excel_row' => $excelRow,

                'email' => $email,

                'user_id' => $user?->id,

                'name' => $user?->name,

                'mode_of_availment' => $modeOfAvailment,

                'disbursement_status' => $disbursementStatus,

            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Store Temporary Import Data
        |--------------------------------------------------------------------------
        */

        session([
            'medical_allowance_import_records' => $previewRows,
            'medical_allowance_import_errors' => $errors,
        ]);


        return redirect()->route(
            'data-management.medical-allowance.import.preview'
        );
    }   

    public function medicalAllowanceImportPreview()
    {
        $rows = session('medical_allowance_import_records', []);

        $errors = session('medical_allowance_import_errors', []);

        if (empty($rows)) {

            return redirect()
                ->route('data-management.medical-allowance')
                ->with('error', 'No medical allowance records available for preview.');
        }

        return view(
            'data-management.medical-allowance-preview',
            compact('rows', 'errors')
        );
    }

    public function confirmMedicalAllowanceImport(Request $request)
    {
        $records = session('medical_allowance_import_records');

        /*
        |--------------------------------------------------------------------------
        | Check Temporary Import Records
        |--------------------------------------------------------------------------
        */

        if (!$records || count($records) === 0) {

            return redirect()
                ->route('data-management.medical-allowance')
                ->with(
                    'error',
                    'No medical allowance records available for import.'
                );
        }


        $imported = 0;
        $updated = 0;
        $skipped = 0;

        $errors = [];


        DB::beginTransaction();

        try {

            foreach ($records as $index => $record) {

                try {

                    /*
                    |--------------------------------------------------------------------------
                    | Excel Row
                    |--------------------------------------------------------------------------
                    */

                    $excelRow = $record['excel_row'] ?? ($index + 2);


                    /*
                    |--------------------------------------------------------------------------
                    | Email
                    |--------------------------------------------------------------------------
                    */

                    $email = trim(
                        (string) ($record['email'] ?? '')
                    );


                    if ($email === '') {

                        $skipped++;

                        $errors[] = [
                            'row' => $excelRow,
                            'message' => 'Email address is missing.'
                        ];

                        continue;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Find Personnel/User
                    |--------------------------------------------------------------------------
                    */

                    $user = User::where('email', $email)->first();


                    if (!$user) {

                        $skipped++;

                        $errors[] = [
                            'row' => $excelRow,
                            'message' => "Personnel with email {$email} was not found."
                        ];

                        continue;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Find Existing Medical Allowance
                    |--------------------------------------------------------------------------
                    |
                    | One medical allowance record per personnel.
                    |
                    */

                    $medicalAllowance = MedicalAllowance::where(
                        'users_id',
                        $user->id
                    )->first();


                    /*
                    |--------------------------------------------------------------------------
                    | Prepare Data
                    |--------------------------------------------------------------------------
                    */

                    $data = [

                        'users_id' => $user->id,

                        'mode_of_availment' =>
                            !empty($record['mode_of_availment'])
                                ? trim($record['mode_of_availment'])
                                : null,

                        'disbursement_status' =>
                            !empty($record['disbursement_status'])
                                ? trim($record['disbursement_status'])
                                : null,

                    ];


                    /*
                    |--------------------------------------------------------------------------
                    | CREATE NEW RECORD
                    |--------------------------------------------------------------------------
                    */

                    if (!$medicalAllowance) {

                        MedicalAllowance::create($data);

                        $imported++;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE EXISTING RECORD
                    |--------------------------------------------------------------------------
                    */

                    else {

                        $medicalAllowance->update([

                            'mode_of_availment' =>
                                $data['mode_of_availment'],

                            'disbursement_status' =>
                                $data['disbursement_status'],

                        ]);

                        $updated++;
                    }

                } catch (\Throwable $e) {

                    $skipped++;

                    $errors[] = [

                        'row' => $record['excel_row'] ?? ($index + 2),

                        'message' => $e->getMessage(),

                    ];
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Commit Database Changes
            |--------------------------------------------------------------------------
            */

            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | Remove Temporary Import Session
            |--------------------------------------------------------------------------
            */

            session()->forget([
                'medical_allowance_import_records',
                'medical_allowance_import_errors',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Store Import Result
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            | This session name must match the Blade.
            |
            */

            return redirect()
                ->route('data-management.medical-allowance')
                ->with(
                    'medical_allowance_import_result',
                    [

                        'imported' => $imported,

                        'updated' => $updated,

                        'skipped' => $skipped,

                        'errors' => $errors,

                    ]
                );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Rollback
            |--------------------------------------------------------------------------
            */

            DB::rollBack();


            /*
            |--------------------------------------------------------------------------
            | Return Error
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('data-management.medical-allowance')
                ->with(
                    'error',
                    'Medical allowance import failed: ' .
                    $e->getMessage()
                );
        }
    }

    public function medicalAllowanceReport(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        $search = $request->input('search');

        $district = $request->input('district');

        /*
        |--------------------------------------------------------------------------
        | Medical Allowance Summary
        |--------------------------------------------------------------------------
        */

        $query = DB::table('medical_allowance')
            ->join(
                'users',
                'users.id',
                '=',
                'medical_allowance.users_id'
            )
            ->join(
                'employment_status',
                'employment_status.users_id',
                '=',
                'users.id'
            )
            ->join(
                'school_db',
                'school_db.id',
                '=',
                'employment_status.school_db_id'
            )
            ->select(

                'school_db.id as school_db_id',

                'school_db.school_id',

                'school_db.school_name',

                'school_db.school_district',

                'school_db.school_area',

                DB::raw("
                    SUM(
                        CASE
                            WHEN medical_allowance.mode_of_availment
                                = 'Group Availment (HMO)'
                            THEN 1
                            ELSE 0
                        END
                    ) as group_hmo
                "),

                DB::raw("
                    SUM(
                        CASE
                            WHEN medical_allowance.mode_of_availment
                                = 'Individual Availment (HMO)'
                            THEN 1
                            ELSE 0
                        END
                    ) as individual_hmo
                "),

                DB::raw("
                    SUM(
                        CASE
                            WHEN medical_allowance.mode_of_availment
                                = 'Individual Availment (Medical Expenses)'
                            THEN 1
                            ELSE 0
                        END
                    ) as individual_medical
                "),

                DB::raw("
                    COUNT(DISTINCT medical_allowance.users_id)
                    as total_eligible_employee
                ")

            )

            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'school_db.school_id',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'school_db.school_name',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'school_db.school_district',
                        'like',
                        "%{$search}%"
                    );

                });

            })

            ->when($district, function ($query) use ($district) {

                $query->where(
                    'school_db.school_district',
                    $district
                );

            })

            ->groupBy(
                'school_db.id',
                'school_db.school_id',
                'school_db.school_name',
                'school_db.school_district',
                'school_db.school_area'
            )

            ->orderBy(
                'school_db.school_name'
            );


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $reports = $query
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | District Dropdown
        |--------------------------------------------------------------------------
        */

        $districts = DB::table('school_db')
            ->select('school_district')
            ->whereNotNull('school_district')
            ->where('school_district', '!=', '')
            ->distinct()
            ->orderBy('school_district')
            ->pluck('school_district');


        return view(
            'data-management.medical-allowance-report-per-school',
            compact(
                'reports',
                'districts',
                'search',
                'district'
            )
        );
    }


    /*   
    |   END  OF MEDICAL ALLOWANCE RECORDS FUNCTIONS
    |
    |--------------------------------------------------------------------------
    |  
    |   START OF ENROLLMENT RECORDS FUNCTIONS
    */


    public function enrollment(Request $request)
    {
        $search = $request->input('search');

        /*
        |--------------------------------------------------------------------------
        | Get Enrollment Records
        |--------------------------------------------------------------------------
        */

        $enrollments = Enrollment::with([
            'school',
            'schoolYear',
            'gradeLevel',
        ])
        ->when($search, function ($query) use ($search) {

            $query->whereHas('school', function ($q) use ($search) {

                $q->where('school_id', 'like', "%{$search}%")
                ->orWhere('school_name', 'like', "%{$search}%");

            });

        })
        ->orderBy('school_db_id')
        ->orderBy('school_year_id')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Group By School
        |--------------------------------------------------------------------------
        */

        $schools = $enrollments
            ->groupBy('school_db_id')
            ->map(function ($records) {

                $first = $records->first();

                $grades = [];

                foreach ($records as $record) {

                    if (!$record->gradeLevel) {
                        continue;
                    }

                    $grades[$record->gradeLevel->id] = [
                        'name' => $record->gradeLevel->name,
                        'count' => $record->enrollment_count,
                        'sort_order' => $record->gradeLevel->sort_order,
                    ];

                }

                /*
                |--------------------------------------------------------------------------
                | Sort Grades
                |--------------------------------------------------------------------------
                */

                uasort($grades, function ($a, $b) {

                    return $a['sort_order']
                        <=> $b['sort_order'];

                });


                return [
                    'school_id' => $first->school?->school_id,

                    'school_name' => $first->school?->school_name,

                    'school_year' => $first->schoolYear?->school_year,

                    'grades' => $grades,
                ];

            })
            ->values();


        return view(
            'data-management.enrollment',
            compact('schools')
        );
    }

    public function importEnrollment(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
                'max:10240',
            ],
        ]);

        try {

            Excel::import(
                new EnrollmentImport,
                $request->file('file')
            );

            return redirect()
                ->route('data-management.enrollment.import.preview');

        } catch (\Throwable $e) {

            return redirect()
                ->route('data-management.enrollment')
                ->with(
                    'error',
                    'Enrollment import failed: ' . $e->getMessage()
                );
        }
    }

    public function enrollmentImportPreview()
    {
        $rows = session(
            'enrollment_import_records',
            []
        );

        $errors = session(
            'enrollment_import_errors',
            []
        );

        return view(
            'data-management.enrollment-preview',
            compact(
                'rows',
                'errors'
            )
        );
    }

    public function confirmEnrollmentImport(Request $request)
    {
        $records = session(
            'enrollment_import_records'
        );

        if (!$records || count($records) === 0) {

            return redirect()
                ->route('data-management.enrollment')
                ->with(
                    'error',
                    'No enrollment records available for import.'
                );
        }

        $imported = 0;
        $updated = 0;
        $skipped = 0;

        $errors = [];

        DB::beginTransaction();

        try {

            foreach ($records as $index => $record) {

                try {

                    /*
                    |--------------------------------------------------------------------------
                    | Find School
                    |--------------------------------------------------------------------------
                    */

                    $school = SchoolDb::where(
                        'school_id',
                        $record['school_id']
                    )->first();

                    if (!$school) {

                        $skipped++;

                        $errors[] = [
                            'row' => $record['excel_row'] ?? ($index + 2),
                            'message' =>
                                "School ID {$record['school_id']} was not found."
                        ];

                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Find School Year
                    |--------------------------------------------------------------------------
                    */

                    $schoolYear = SchoolYear::where(
                        'school_year',
                        $record['school_year']
                    )->first();

                    if (!$schoolYear) {

                        $skipped++;

                        $errors[] = [
                            'row' => $record['excel_row'] ?? ($index + 2),
                            'message' =>
                                "School Year {$record['school_year']} was not found."
                        ];

                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Find Grade Level
                    |--------------------------------------------------------------------------
                    */

                    $gradeLevel = GradeLevel::where(
                        'name',
                        $record['grade_level']
                    )->first();

                    if (!$gradeLevel) {

                        $skipped++;

                        $errors[] = [
                            'row' => $record['excel_row'] ?? ($index + 2),
                            'message' =>
                                "Grade Level {$record['grade_level']} was not found."
                        ];

                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Find Existing Enrollment
                    |--------------------------------------------------------------------------
                    */

                    $enrollment = Enrollment::where(
                        'school_db_id',
                        $school->id
                    )
                    ->where(
                        'school_year_id',
                        $schoolYear->id
                    )
                    ->where(
                        'grade_level_id',
                        $gradeLevel->id
                    )
                    ->first();

                    /*
                    |--------------------------------------------------------------------------
                    | Create
                    |--------------------------------------------------------------------------
                    */

                    if (!$enrollment) {

                        Enrollment::create([

                            'school_db_id' =>
                                $school->id,

                            'school_year_id' =>
                                $schoolYear->id,

                            'grade_level_id' =>
                                $gradeLevel->id,

                            'enrollment_count' =>
                                $record['enrollment_count'],

                        ]);

                        $imported++;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Update
                    |--------------------------------------------------------------------------
                    */

                    else {

                        $enrollment->update([

                            'enrollment_count' =>
                                $record['enrollment_count'],

                        ]);

                        $updated++;
                    }

                } catch (\Throwable $e) {

                    $skipped++;

                    $errors[] = [

                        'row' =>
                            $record['excel_row'] ?? ($index + 2),

                        'message' =>
                            $e->getMessage(),

                    ];
                }
            }

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | Remove Temporary Session Data
            |--------------------------------------------------------------------------
            */

            session()->forget([
                'enrollment_import_records',
                'enrollment_import_errors',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Import Result
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('data-management.enrollment')
                ->with(
                    'enrollment_import_result',
                    [

                        'imported' => $imported,

                        'updated' => $updated,

                        'skipped' => $skipped,

                        'errors' => $errors,

                    ]
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return redirect()
                ->route('data-management.enrollment')
                ->with(
                    'error',
                    'Enrollment import failed: ' .
                    $e->getMessage()
                );
        }
    }



    /*   
    |   END  OF  ENROLLMENT RECORDS FUNCTIONS
    |
    |--------------------------------------------------------------------------
   
    */
}