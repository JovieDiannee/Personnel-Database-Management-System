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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use App\Models\BasicInformation;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Models\Report;
use App\Models\ReportSubmission;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use App\Models\OfficeGroup;
use App\Models\OfficeUnit;



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
    |   START OF PERSONNEL INFORMATION FUNCTIONS
    */

    public function personnel(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        $search = trim($request->input('search', ''));


        /*
        |--------------------------------------------------------------------------
        | Logged-in User
        |--------------------------------------------------------------------------
        */

        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Base Personnel Query
        |--------------------------------------------------------------------------
        */

        $query = \App\Models\BasicInformation::whereHas('user')
        ->with([
            'user',
            'issuedId',
            'user.employmentStatus.school',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Role-Based Access
        |--------------------------------------------------------------------------
        |
        | Super Admin = View all personnel
        | Admin       = View personnel from the same school only
        |
        */

        if ($user->role === 'admin') {

            /*
            |--------------------------------------------------------------------------
            | Get Admin's School
            |--------------------------------------------------------------------------
            */

            $adminSchoolId = $user->employmentStatus?->school_db_id;


            if ($adminSchoolId) {

                /*
                |--------------------------------------------------------------------------
                | Same School Only
                |--------------------------------------------------------------------------
                */

                $query->whereHas(
                    'user.employmentStatus',
                    function ($employmentQuery) use ($adminSchoolId) {

                        $employmentQuery->where(
                            'school_db_id',
                            $adminSchoolId
                        );

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | Exclude Employee No. 1000001
                |--------------------------------------------------------------------------
                */

                $query->whereDoesntHave(
                    'issuedId',
                    function ($issuedIdQuery) {

                        $issuedIdQuery->where(
                            'employee_id',
                            '1000001'
                        );

                    }
                );

            } else {

                /*
                |--------------------------------------------------------------------------
                | Admin Without School Assignment
                |--------------------------------------------------------------------------
                |
                | Do not allow the Admin to see personnel from other schools.
                |
                */

                $query->whereRaw('1 = 0');

            }
        }


        /*
        |--------------------------------------------------------------------------
        | Search Personnel
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $query->where(function ($q) use ($search) {

                /*
                |--------------------------------------------------------------------------
                | Employee Number
                |--------------------------------------------------------------------------
                */

                $q->whereHas('issuedId', function ($issuedIdQuery) use ($search) {

                    $issuedIdQuery->where(
                        'employee_id',
                        'like',
                        "%{$search}%"
                    );

                })


                /*
                |--------------------------------------------------------------------------
                | Name
                |--------------------------------------------------------------------------
                */

                ->orWhere(
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
                )

                ->orWhere(
                    'extension_name',
                    'like',
                    "%{$search}%"
                )

                ->orWhereRaw(
                    "CONCAT_WS(
                        ' ',
                        first_name,
                        middle_name,
                        last_name,
                        extension_name
                    ) LIKE ?",
                    ["%{$search}%"]
                )


                /*
                |--------------------------------------------------------------------------
                | Mobile Number
                |--------------------------------------------------------------------------
                */

                ->orWhere(
                    'mobile_number',
                    'like',
                    "%{$search}%"
                )


                /*
                |--------------------------------------------------------------------------
                | Email / User Role / User Status
                |--------------------------------------------------------------------------
                */

                ->orWhereHas('user', function ($userQuery) use ($search) {

                    $userQuery->where(function ($userSearch) use ($search) {

                        $userSearch
                            ->where(
                                'email',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'role',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'status',
                                'like',
                                "%{$search}%"
                            );

                    });

                })


                /*
                |--------------------------------------------------------------------------
                | Date Created
                |--------------------------------------------------------------------------
                */

                ->orWhere(
                    'created_at',
                    'like',
                    "%{$search}%"
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Sort and Paginate
        |--------------------------------------------------------------------------
        */

        $personnel = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'data-management.personnel',
            compact(
                'personnel',
                'search'
            )
        );
    }
    
    public function importPersonnel(Request $request)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Only the Super Admin can import personnel records.');
        }

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

            /*
        |--------------------------------------------------------------------------
        | Skip Completely Empty Rows
        |--------------------------------------------------------------------------
        */

        $isEmptyRow = $row->filter(function ($value) {

            return trim((string) $value) !== '';

            })->isEmpty();

            if ($isEmptyRow) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Actual Excel Row Number
            |--------------------------------------------------------------------------
            */

            $excelRow = $index + 5;
            $dataRowNumber = $index + 1;

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

            if (
                $email !== '' &&
                !preg_match(
                    '/^[\p{L}\p{N}._%+\-]+@[\p{L}\p{N}.-]+\.[\p{L}]{2,}$/u',
                    $email
                )
            ) {
                $errors[] = "Row {$excelRow}: Invalid email address.";
            }

            $birthDate = $this->parseBirthDate($row['birth_date'] ?? null);

            $previewRows[] = [
                'excel_row' => $dataRowNumber,
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

                $user = User::withTrashed()
                        ->where('email', $email)
                        ->first();

                    if ($user?->trashed()) {
                        throw new \RuntimeException(
                            'This email belongs to an employee in Trash Bin. '
                            . 'Restore the employee before importing changes.'
                        );
                    }


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

    public function downloadPersonnelBasicInformationTemplate()
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Personnel Basic Information');


        /*
        |--------------------------------------------------------------------------
        | TITLE
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A1:AF1');

        $sheet->setCellValue(
            'A1',
            'PERSONNEL BASIC INFORMATION RECORDS'
        );

        $sheet->getStyle('A1')->applyFromArray([

            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => [
                    'rgb' => 'FFFFFF'
                ],
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '15803D'
                ],
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],

        ]);

        $sheet->getRowDimension(1)->setRowHeight(28);


        /*
        |--------------------------------------------------------------------------
        | INSTRUCTIONS
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A2:AF2');

        $sheet->setCellValue(
            'A2',
            'Please do not modify the column headers. Enter one personnel record per row.'
        );

        $sheet->getStyle('A2')->applyFromArray([

            'font' => [
                'italic' => true,
                'size' => 10,
                'color' => [
                    'rgb' => '666666'
                ],
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],

        ]);

        $sheet->getRowDimension(2)->setRowHeight(22);


        /*
        |--------------------------------------------------------------------------
        | COLUMN HEADERS
        |--------------------------------------------------------------------------
        */

        $headers = [

            'A4'  => 'email',
            'B4'  => 'first_name',
            'C4'  => 'middle_name',
            'D4'  => 'last_name',
            'E4'  => 'extension_name',
            'F4'  => 'sex',
            'G4'  => 'birth_place',
            'H4'  => 'birth_date',
            'I4'  => 'civil_status',
            'J4'  => 'religion',
            'K4'  => 'citizenship',
            'L4'  => 'mode_of_citizenship',
            'M4'  => 'height_m',
            'N4'  => 'weight_kg',
            'O4'  => 'blood_type',
            'P4'  => 'mobile_number',
            'Q4'  => 'telephone_number',
            'R4'  => 'specialization',
            'S4'  => 'address_type',
            'T4'  => 'street',
            'U4'  => 'brgy',
            'V4'  => 'subd_village',
            'W4'  => 'municipality_city',
            'X4'  => 'province',
            'Y4'  => 'zip_postal',
            'Z4'  => 'umid_no',
            'AA4' => 'gsis_no',
            'AB4' => 'philsys_no',
            'AC4' => 'pagibig_no',
            'AD4' => 'tin_no',
            'AE4' => 'philhealth_no',
            'AF4' => 'employee_id',

        ];

        foreach ($headers as $cell => $value) {

            $sheet->setCellValue(
                $cell,
                $value
            );

        }


        /*
        |--------------------------------------------------------------------------
        | HEADER STYLE
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A4:AF4')->applyFromArray([

            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' => 'FFFFFF'
                ],
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '166534'
                ],
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],

            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => 'D1D5DB'
                    ],
                ],
            ],

        ]);

        $sheet->getRowDimension(4)->setRowHeight(45);


        /*
        |--------------------------------------------------------------------------
        | SAMPLE DATA
        |--------------------------------------------------------------------------
        */

        $sampleData = [

            'A5'  => 'example@deped.gov.ph',
            'B5'  => 'Jovie',
            'C5'  => 'Cabrera',
            'D5'  => 'Gayo',
            'E5'  => '',
            'F5'  => 'Male',
            'G5'  => 'Hilongos, Leyte',
            'H5'  => '01/15/1990',
            'I5'  => 'Single',
            'J5'  => 'Roman Catholic',
            'K5'  => 'Filipino',
            'L5'  => 'By Birth',
            'M5'  => '1.50',
            'N5'  => '50',
            'O5'  => 'A+',
            'P5'  => '09171234567',
            'Q5'  => '',

            // Must match one of the specialization dropdown values
            'R5'  => 'Information and Communication Technology (ICT)',

            'S5'  => 'Permanent',
            'T5'  => 'P. Burgos Street',
            'U5'  => 'Talisay',
            'V5'  => '',
            'W5'  => 'Hilongos',
            'X5'  => 'Leyte',
            'Y5'  => '6524',
            'Z5'  => '',
            'AA5' => '',
            'AB5' => '',
            'AC5' => '',
            'AD5' => '',
            'AE5' => '',
            'AF5' => 'EMP-000001',

        ];

        foreach ($sampleData as $cell => $value) {

            $sheet->setCellValue(
                $cell,
                $value
            );

        }


        /*
        |--------------------------------------------------------------------------
        | SAMPLE DATA STYLE
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A5:AF5')->applyFromArray([

            'font' => [
                'color' => [
                    'rgb' => '6B7280'
                ],
                'italic' => true,
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'F9FAFB'
                ],
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | SEX DROPDOWN
        |--------------------------------------------------------------------------
        */

        $sexValidation = new DataValidation();

        $sexValidation
            ->setType(DataValidation::TYPE_LIST)
            ->setErrorStyle(DataValidation::STYLE_STOP)
            ->setAllowBlank(true)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('Invalid Sex')
            ->setError('Please select a valid sex.')
            ->setFormula1(
                '"Male,Female"'
            );


        /*
        |--------------------------------------------------------------------------
        | CIVIL STATUS DROPDOWN
        |--------------------------------------------------------------------------
        */

        $civilStatusValidation = new DataValidation();

        $civilStatusValidation
            ->setType(DataValidation::TYPE_LIST)
            ->setErrorStyle(DataValidation::STYLE_STOP)
            ->setAllowBlank(true)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('Invalid Civil Status')
            ->setError('Please select a valid civil status.')
            ->setFormula1(
                '"Single,Married,Widowed,Separated,Annulled"'
            );


        /*
        |--------------------------------------------------------------------------
        | MODE OF CITIZENSHIP DROPDOWN
        |--------------------------------------------------------------------------
        */

        $citizenshipValidation = new DataValidation();

        $citizenshipValidation
            ->setType(DataValidation::TYPE_LIST)
            ->setErrorStyle(DataValidation::STYLE_STOP)
            ->setAllowBlank(true)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('Invalid Citizenship Mode')
            ->setError('Please select a valid mode of citizenship.')
            ->setFormula1(
                '"By Birth,By Naturalization"'
            );


        /*
        |--------------------------------------------------------------------------
        | BLOOD TYPE DROPDOWN
        |--------------------------------------------------------------------------
        */

        $bloodTypeValidation = new DataValidation();

        $bloodTypeValidation
            ->setType(DataValidation::TYPE_LIST)
            ->setErrorStyle(DataValidation::STYLE_STOP)
            ->setAllowBlank(true)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('Invalid Blood Type')
            ->setError('Please select a valid blood type.')
            ->setFormula1(
                '"A+,A-,B+,B-,AB+,AB-,O+,O-"'
            );


        /*
        |--------------------------------------------------------------------------
        | ADDRESS TYPE DROPDOWN
        |--------------------------------------------------------------------------
        */

        $addressTypeValidation = new DataValidation();

        $addressTypeValidation
            ->setType(DataValidation::TYPE_LIST)
            ->setErrorStyle(DataValidation::STYLE_STOP)
            ->setAllowBlank(true)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('Invalid Address Type')
            ->setError('Please select a valid address type.')
            ->setFormula1(
                '"Permanent,Present"'
            );


        /*
        |--------------------------------------------------------------------------
        | SPECIALIZATION LIST
        |--------------------------------------------------------------------------
        |
        | The specialization list is too long to safely use as an inline
        | Excel validation list. Store it in a hidden worksheet instead.
        |
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
        | CREATE DROPDOWN LIST WORKSHEET
        |--------------------------------------------------------------------------
        */

        $dropdownSheet = $spreadsheet->createSheet();

        $dropdownSheet->setTitle('Dropdown Lists');


        /*
        |--------------------------------------------------------------------------
        | ADD SPECIALIZATIONS TO HIDDEN SHEET
        |--------------------------------------------------------------------------
        */

        foreach ($specializations as $index => $specialization) {

            $dropdownSheet->setCellValue(
                'A' . ($index + 1),
                $specialization
            );

        }


        /*
        |--------------------------------------------------------------------------
        | SPECIALIZATION DROPDOWN
        |--------------------------------------------------------------------------
        */

        $specializationValidation = new DataValidation();

        $specializationValidation
            ->setType(DataValidation::TYPE_LIST)
            ->setErrorStyle(DataValidation::STYLE_STOP)
            ->setAllowBlank(true)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('Invalid Specialization')
            ->setError(
                'Please select a valid specialization from the list.'
            )
            ->setPromptTitle(
                'Specialization'
            )
            ->setPrompt(
                'Select the personnel specialization.'
            )
            ->setFormula1(
                "'Dropdown Lists'!\$A\$1:\$A\$" . count($specializations)
            );


        /*
        |--------------------------------------------------------------------------
        | HIDE DROPDOWN LIST WORKSHEET
        |--------------------------------------------------------------------------
        */

        $dropdownSheet->setSheetState(
            \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN
        );


        /*
        |--------------------------------------------------------------------------
        | RETURN TO MAIN WORKSHEET
        |--------------------------------------------------------------------------
        |
        | createSheet() makes the new worksheet available in the workbook.
        | Explicitly set the personnel sheet as the active sheet.
        |
        */

        $spreadsheet->setActiveSheetIndexByName(
            'Personnel Basic Information'
        );


        /*
        |--------------------------------------------------------------------------
        | APPLY DROPDOWNS
        |--------------------------------------------------------------------------
        |
        | Apply validation to rows 5-1000.
        |
        */

        for ($row = 5; $row <= 1000; $row++) {

            // Sex
            $sheet
                ->getCell("F{$row}")
                ->setDataValidation(
                    clone $sexValidation
                );

            // Civil Status
            $sheet
                ->getCell("I{$row}")
                ->setDataValidation(
                    clone $civilStatusValidation
                );

            // Mode of Citizenship
            $sheet
                ->getCell("L{$row}")
                ->setDataValidation(
                    clone $citizenshipValidation
                );

            // Blood Type
            $sheet
                ->getCell("O{$row}")
                ->setDataValidation(
                    clone $bloodTypeValidation
                );

            // Specialization
            $sheet
                ->getCell("R{$row}")
                ->setDataValidation(
                    clone $specializationValidation
                );

            // Address Type
            $sheet
                ->getCell("S{$row}")
                ->setDataValidation(
                    clone $addressTypeValidation
                );
        }


        /*
        |--------------------------------------------------------------------------
        | BORDERS FOR DATA AREA
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A4:AF1000')->applyFromArray([

            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => 'E5E7EB'
                    ],
                ],
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | COLUMN WIDTHS
        |--------------------------------------------------------------------------
        */

        $widths = [

            'A'  => 32,
            'B'  => 20,
            'C'  => 20,
            'D'  => 20,
            'E'  => 18,
            'F'  => 12,
            'G'  => 25,
            'H'  => 18,
            'I'  => 18,
            'J'  => 22,
            'K'  => 18,
            'L'  => 25,
            'M'  => 15,
            'N'  => 15,
            'O'  => 15,
            'P'  => 20,
            'Q'  => 20,

            // Wider because specialization names are long
            'R'  => 48,

            'S'  => 20,
            'T'  => 25,
            'U'  => 25,
            'V'  => 25,
            'W'  => 25,
            'X'  => 22,
            'Y'  => 15,
            'Z'  => 20,
            'AA' => 20,
            'AB' => 20,
            'AC' => 20,
            'AD' => 20,
            'AE' => 20,
            'AF' => 20,

        ];

        foreach ($widths as $column => $width) {

            $sheet
                ->getColumnDimension($column)
                ->setWidth($width);

        }


        /*
        |--------------------------------------------------------------------------
        | TEXT FORMAT FOR IDENTIFICATION NUMBERS
        |--------------------------------------------------------------------------
        |
        | Prevent Excel from removing leading zeroes.
        |
        */

        $sheet
            ->getStyle('P5:AF1000')
            ->getNumberFormat()
            ->setFormatCode('@');


        /*
        |--------------------------------------------------------------------------
        | FREEZE HEADER
        |--------------------------------------------------------------------------
        */

        $sheet->freezePane('A5');


        /*
        |--------------------------------------------------------------------------
        | AUTO FILTER
        |--------------------------------------------------------------------------
        */

        $sheet->setAutoFilter('A4:AF1000');


        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD
        |--------------------------------------------------------------------------
        */

        $fileName =
            'PDMS_Personnel_Basic_Information_Template.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(
            function () use ($writer) {

                $writer->save('php://output');

            },
            $fileName,
            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }

    public function updateUserAccess(Request $request, $person)
    {
        $validated = $request->validate([

            'role' => [
                'required',
                'in:user,admin,super_admin',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],

            'reset_password' => [
                'nullable',
                'boolean',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Super Admin Must Remain Active
        |--------------------------------------------------------------------------
        */

        if (
            $validated['role'] === 'super_admin' &&
            $validated['status'] === 'inactive'
        ) {
            return back()->with(
                'error',
                'A Super Admin account must remain active.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Find Personnel
        |--------------------------------------------------------------------------
        */

        $personnel = \App\Models\BasicInformation::whereHas('user')->findOrFail($person);


        /*
        |--------------------------------------------------------------------------
        | Find User Account
        |--------------------------------------------------------------------------
        */

        $user = $personnel->user;

        if (! $user) {
            return back()->with(
                'error',
                'This personnel record does not have a user account.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Changing Own Account
        |--------------------------------------------------------------------------
        */

        if ($user->id === auth()->id()) {
            return back()->with(
                'error',
                'You cannot change your own role, account status, or password from this screen.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Protect Existing Super Admin Accounts
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'super_admin') {
            return back()->with(
                'error',
                'Super Admin accounts cannot be changed from this screen.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Update Role and Status
        |--------------------------------------------------------------------------
        */

        $user->role = $validated['role'];

        $user->status = $validated['status'];


        /*
        |--------------------------------------------------------------------------
        | Reset Password
        |--------------------------------------------------------------------------
        */

        if ($request->boolean('reset_password')) {

            $user->password = Hash::make('pdms@123');

        }


        /*
        |--------------------------------------------------------------------------
        | Save Changes
        |--------------------------------------------------------------------------
        */

        $user->save();


        /*
        |--------------------------------------------------------------------------
        | Success Message
        |--------------------------------------------------------------------------
        */

        if ($request->boolean('reset_password')) {

            return back()->with(
                'success',
                'User access updated successfully. The password has been reset to the default password: pdms@123'
            );
        }


        return back()->with(
            'success',
            'User role and account status updated successfully.'
        );
    }

    public function editPersonnel($id)
    {
        $loggedInUser = auth()->user();

        abort_unless($loggedInUser, 401);

        $loggedInEmployeeId = $loggedInUser
            ->basicInformation
            ?->issuedId
            ?->employee_id;

        // if ((string) $loggedInEmployeeId === '1000001') {
        //     abort(403, 'You are not authorized to access this page.');
        // }

        $person = BasicInformation::whereHas('user')->with([
            'user',
            'issuedId',
            'employmentStatus',
        ])->findOrFail($id);

        $addresses = DB::table('address')
            ->where('basic_information_id', $person->id)
            ->orderBy('id')
            ->get();

        return view(
            'data-management.personnel-edit',
            compact('person', 'addresses')
        );
    }

    public function updatePersonnel(Request $request, $id)
    {
        // ACCESS CHECK
        $loggedInUser = $request->user();

        abort_unless($loggedInUser, 401);

        $loggedInEmployeeId = $loggedInUser
            ->basicInformation
            ?->issuedId
            ?->employee_id;

        // if ((string) $loggedInEmployeeId === '1000001') {
        //     abort(403, 'You are not authorized to update personnel.');
        // }

        $person = BasicInformation::whereHas('user')->with([
            'user',
            'issuedId',
        ])->findOrFail($id);

        if (! $person->user) {
            throw ValidationException::withMessages([
                'email' => 'This personnel record has no linked user account.',
            ]);
        }

        // FIELDS PER TABLE
        $basicFields = [
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
        ];

        $issuedIdFields = [
            'umid_no',
            'gsis_no',
            'philsys_no',
            'pagibig_no',
            'tin_no',
            'philhealth_no',
            'employee_id',
        ];

        $addressFields = [
            'type',
            'street',
            'brgy',
            'subd_village',
            'municipality_city',
            'province',
            'zip_postal',
        ];

        // VALIDATION
        $rules = [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique(get_class($person->user), 'email')
                    ->ignore($person->user),
            ],

            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'extension_name' => ['sometimes', 'nullable', 'string', 'max:50'],

            'sex' => [
                'sometimes',
                'nullable',
                Rule::in(['Male', 'Female']),
            ],

            'birth_place' => ['sometimes', 'nullable', 'string', 'max:255'],

            'birth_date' => [
                'sometimes',
                'nullable',
                'date_format:Y-m-d',
                'before_or_equal:today',
            ],

            'civil_status' => [
                'sometimes',
                'nullable',
                Rule::in([
                    'Single',
                    'Married',
                    'Widowed',
                    'Separated',
                    'Annulled',
                ]),
            ],

            'religion' => ['sometimes', 'nullable', 'string', 'max:100'],
            'citizenship' => ['sometimes', 'nullable', 'string', 'max:100'],

            'mode_of_citizenship' => [
                'sometimes',
                'nullable',
                Rule::in(['By Birth', 'By Naturalization']),
            ],

            'height_m' => ['sometimes', 'nullable', 'numeric', 'gt:0'],
            'weight_kg' => ['sometimes', 'nullable', 'numeric', 'gt:0'],

            'blood_type' => [
                'sometimes',
                'nullable',
                Rule::in([
                    'A+', 'A-', 'B+', 'B-',
                    'AB+', 'AB-', 'O+', 'O-', 'Unknown',
                ]),
            ],

            'mobile_number' => ['sometimes', 'nullable', 'string', 'max:50'],
            'telephone_number' => ['sometimes', 'nullable', 'string', 'max:50'],
            'specialization' => ['sometimes', 'nullable', 'string', 'max:255'],

            'addresses' => ['sometimes', 'array'],

            'addresses.*' => [
                'array:id,type,street,brgy,subd_village,municipality_city,province,zip_postal',
            ],

            'addresses.*.id' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('address', 'id')->where(
                    fn ($query) => $query->where(
                        'basic_information_id',
                        $person->id
                    )
                ),
            ],
        ];

        foreach ($issuedIdFields as $field) {
            $rules[$field] = [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ];
        }

        foreach ($addressFields as $field) {
            $rules["addresses.*.$field"] = [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ];
        }

        // Override the generic rule AFTER the loop.
        $rules['addresses.*.type'] = [
            'required',
            'string',
            Rule::in(['permanent', 'residential']),
        ];

        $validated = $request->validate($rules);

        // SAVE ALL CHANGES TOGETHER
        DB::transaction(function () use (
            $person,
            $validated,
            $basicFields,
            $issuedIdFields,
            $addressFields
        ) {
            // BASIC INFORMATION
            $person->fill(Arr::only($validated, $basicFields));
            $person->saveOrFail();

            // USER NAME AND EMAIL
            $user = $person->user;

            $user->name = collect([
                $person->first_name,
                $person->middle_name,
                $person->last_name,
                $person->extension_name,
            ])
                ->filter(fn ($value) => filled($value))
                ->implode(' ');

            $user->email = $validated['email'];
            $user->saveOrFail();

            // GOVERNMENT IDs AND EMPLOYEE ID
            $issuedIdData = Arr::only($validated, $issuedIdFields);

            if ($issuedIdData !== []) {
                $issuedId = $person->issuedId;

                if (
                    ! $issuedId &&
                    collect($issuedIdData)->contains(
                        fn ($value) => filled($value)
                    )
                ) {
                    $issuedId = $person->issuedId()->make();
                }

                if ($issuedId) {
                    $issuedId->fill($issuedIdData);
                    $issuedId->saveOrFail();
                }
            }

            // EXISTING ADDRESSES
            foreach ($validated['addresses'] ?? [] as $index => $addressData) {
                $address = DB::table('address')
                    ->where('basic_information_id', $person->id)
                    ->where('id', $addressData['id'])
                    ->lockForUpdate()
                    ->first();

                if (! $address) {
                    throw ValidationException::withMessages([
                        "addresses.$index.id" =>
                            'This address is no longer available. Reload the page.',
                    ]);
                }

                $values = Arr::only($addressData, $addressFields);
                $values['updated_at'] = now();

                DB::table('address')
                    ->where('basic_information_id', $person->id)
                    ->where('id', $address->id)
                    ->update($values);
            }
        });

        return redirect()
            ->route('data-management.personnel.edit', $person->id)
            ->with(
                'success',
                'Personnel information updated successfully.'
            );
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
        | Search / Tab
        |--------------------------------------------------------------------------
        */

        $search = trim((string) $request->input('search', ''));

        $employeeTab = $request->input('status', 'active');

        if (! in_array($employeeTab, ['active', 'inactive'], true)) {
            $employeeTab = 'active';
        }

        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Vacant Warm Body Statuses
        |--------------------------------------------------------------------------
        */

        $vacantStatuses = [
            'Vacant (Retired)',
            'Vacant (Resigned)',
            'Vacant (Others)',
        ];


        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */

        $query = \App\Models\EmploymentStatus::query()

            ->whereHas('user')

            ->with([

                'user.basicInformation',

                'plantilla',

                'school',

                /*
                |--------------------------------------------------------------------------
                | Division Office Assignment
                |--------------------------------------------------------------------------
                */

                'officeUnit.officeGroup',

                'officeUnit.parent',

            ])

            /*
            |--------------------------------------------------------------------------
            | Exclude Employee ID 1000001
            |--------------------------------------------------------------------------
            */

            ->whereDoesntHave(
                'user.basicInformation.issuedId',
                function ($issuedIdQuery) {

                    $issuedIdQuery->where(
                        'employee_id',
                        '1000001'
                    );
                }
            );


        /*
        |--------------------------------------------------------------------------
        | Active / Inactive Employee Filter
        |--------------------------------------------------------------------------
        |
        | ACTIVE:
        | - NULL Warm Body Status
        | - Any status except the three Vacant statuses
        |
        | INACTIVE:
        | - Vacant (Retired)
        | - Vacant (Resigned)
        | - Vacant (Others)
        |
        */

        if ($employeeTab === 'inactive') {

            $query->whereIn(
                'warm_body_status',
                $vacantStatuses
            );

        } else {

            $query->where(function ($statusQuery) use ($vacantStatuses) {

                $statusQuery

                    ->whereNull('warm_body_status')

                    ->orWhereNotIn(
                        'warm_body_status',
                        $vacantStatuses
                    );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Admin Access Restriction
        |--------------------------------------------------------------------------
        |
        | School Admins can only view personnel assigned to their school.
        |
        */

        if ($user->role === 'admin') {

            $adminSchool =
                $user->employmentStatus?->school;


            if (! $adminSchool) {

                /*
                |--------------------------------------------------------------------------
                | Admin Has No Assigned School
                |--------------------------------------------------------------------------
                */

                $query->whereRaw('1 = 0');

            } else {

                /*
                |--------------------------------------------------------------------------
                | Personnel From Same School Only
                |--------------------------------------------------------------------------
                */

                $query->whereHas(
                    'school',
                    function ($schoolQuery) use ($adminSchool) {

                        $schoolQuery->where(
                            'school_id',
                            $adminSchool->school_id
                        );
                    }
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $query->where(function ($q) use ($search) {


                /*
                |--------------------------------------------------------------------------
                | Personnel Name
                |--------------------------------------------------------------------------
                */

                $q->whereHas(
                    'user.basicInformation',
                    function ($basicQuery) use ($search) {

                        $basicQuery->where(
                            function ($nameQuery) use ($search) {

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
                                    )

                                    ->orWhere(
                                        'extension_name',
                                        'like',
                                        "%{$search}%"
                                    );
                            }
                        );
                    }
                )


                /*
                |--------------------------------------------------------------------------
                | School
                |--------------------------------------------------------------------------
                */

                ->orWhereHas(
                    'school',
                    function ($schoolQuery) use ($search) {

                        $schoolQuery->where(
                            function ($fields) use ($search) {

                                $fields

                                    ->where(
                                        'school_name',
                                        'like',
                                        "%{$search}%"
                                    )

                                    ->orWhere(
                                        'school_id',
                                        'like',
                                        "%{$search}%"
                                    )

                                    ->orWhere(
                                        'school_district',
                                        'like',
                                        "%{$search}%"
                                    );
                            }
                        );
                    }
                )


                /*
                |--------------------------------------------------------------------------
                | Division Office Unit
                |--------------------------------------------------------------------------
                */

                ->orWhereHas(
                    'officeUnit',
                    function ($officeQuery) use ($search) {

                        $officeQuery->where(
                            function ($fields) use ($search) {

                                $fields

                                    ->where(
                                        'name',
                                        'like',
                                        "%{$search}%"
                                    )

                                    ->orWhere(
                                        'code',
                                        'like',
                                        "%{$search}%"
                                    )

                                    ->orWhere(
                                        'short_name',
                                        'like',
                                        "%{$search}%"
                                    )

                                    ->orWhere(
                                        'unit_type',
                                        'like',
                                        "%{$search}%"
                                    );
                            }
                        );
                    }
                )


                /*
                |--------------------------------------------------------------------------
                | Office Group
                |--------------------------------------------------------------------------
                */

                ->orWhereHas(
                    'officeUnit.officeGroup',
                    function ($groupQuery) use ($search) {

                        $groupQuery

                            ->where(
                                'name',
                                'like',
                                "%{$search}%"
                            )

                            ->orWhere(
                                'code',
                                'like',
                                "%{$search}%"
                            );
                    }
                )


                /*
                |--------------------------------------------------------------------------
                | Plantilla
                |--------------------------------------------------------------------------
                */

                ->orWhereHas(
                    'plantilla',
                    function ($plantillaQuery) use ($search) {

                        $plantillaQuery->where(
                            function ($fields) use ($search) {

                                $fields

                                    ->where(
                                        'item_from_school_level',
                                        'like',
                                        "%{$search}%"
                                    )

                                    ->orWhere(
                                        'item_number',
                                        'like',
                                        "%{$search}%"
                                    )

                                    ->orWhere(
                                        'position_title',
                                        'like',
                                        "%{$search}%"
                                    );
                            }
                        );
                    }
                )


                /*
                |--------------------------------------------------------------------------
                | Employment Information
                |--------------------------------------------------------------------------
                */

                ->orWhere(
                    'employment_status',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'warm_body_status',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'nature_of_work',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'date_of_original_appointment',
                    'like',
                    "%{$search}%"
                );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $employmentStatuses = $query

            ->latest('updated_at')

            ->paginate(10)

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
                'search',
                'employeeTab'
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

            /*
            |--------------------------------------------------------------------------
            | Skip Completely Empty Rows
            |--------------------------------------------------------------------------
            */

            $isEmptyRow = $row->filter(function ($value) {
                return trim((string) $value) !== '';
            })->isEmpty();

            if ($isEmptyRow) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Actual Excel Row Number
            |--------------------------------------------------------------------------
            */

            $excelRow = $index + 5;
            $dataRowNumber = $index + 1;

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

            if (
                !preg_match(
                    '/^[\p{L}\p{N}._%+\-]+@[\p{L}\p{N}.-]+\.[\p{L}]{2,}$/u',
                    $email
                )
            ) {
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
                ->whereNull('users.deleted_at')
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

                'excel_row' => $dataRowNumber,

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
        $records = session('employment_status_import_records', []);

        if (empty($records)) {
            return redirect()
                ->route('data-management.employment-status')
                ->with('error', 'No employment records available for import.');
        }

        /*
        |--------------------------------------------------------------------------
        | Check duplicate employees in the uploaded records
        |--------------------------------------------------------------------------
        | Different employees may share the same plantilla item.
        | Each employee should appear only once in this upload.
        */

        $seenEmails = [];
        $duplicateErrors = [];

        foreach ($records as $index => $record) {
            $excelRow = $record['excel_row'] ?? ($index + 2);
            $email = strtolower(trim((string) ($record['email'] ?? '')));

            if ($email === '') {
                continue;
            }

            if (isset($seenEmails[$email])) {
                $previousRow = $seenEmails[$email];

                $duplicateErrors[] = [
                    'row' => $excelRow,
                    'email' => $email,
                    'message' =>
                        "Email {$email} appears more than once. "
                        . "Check rows {$previousRow} and {$excelRow}. "
                        . 'Keep only one row per employee.',
                ];

                continue;
            }

            $seenEmails[$email] = $excelRow;
        }

        if (!empty($duplicateErrors)) {
            return redirect()
                ->route('data-management.employment-status')
                ->with('employment_import_result', [
                    'imported' => 0,
                    'updated' => 0,
                    'skipped' => count($records),
                    'errors' => $duplicateErrors,
                    'duplicate_plantilla' => false,
                ])
                ->with(
                    'error',
                    'Import stopped. Duplicate employee emails were found. '
                    . 'No records were imported or updated.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Prepare counters and optional-value normalizer
        |--------------------------------------------------------------------------
        | Preserve errors from rows rejected during preview.
        */

        $imported = 0;
        $updated = 0;
        $errors = session('employment_status_import_errors', []);
        $skipped = count($errors);

        $nullableValue = static function ($value) {
            $value = trim((string) ($value ?? ''));

            return ($value === '' || $value === '-')
                ? null
                : $value;
        };

        /*
        |--------------------------------------------------------------------------
        | Import each employee
        |--------------------------------------------------------------------------
        | Each row has its own transaction: failed rows are skipped,
        | while successful rows are retained.
        */

        foreach ($records as $index => $record) {
            $excelRow = $record['excel_row'] ?? ($index + 2);
            $email = trim((string) ($record['email'] ?? ''));

            try {
                if ($email === '') {
                    throw new \RuntimeException('Email address is missing.');
                }

                $result = DB::transaction(function () use (
                    $record,
                    $email,
                    $nullableValue
                ) {
                    // Lock the employee while checking and saving their record.
                    $user = DB::table('users')
                        ->whereNull('users.deleted_at')
                        ->where('email', $email)
                        ->lockForUpdate()
                        ->first();

                    if (!$user) {
                        throw new \RuntimeException(
                            "Email {$email} does not exist in the users table."
                        );
                    }

                    $plantillaDbId = $nullableValue(
                        $record['plantilla_db_id'] ?? null
                    );

                    $schoolDbId = $nullableValue(
                        $record['school_db_id'] ?? null
                    );

                    // Only check existence. Shared plantilla IDs are allowed.
                    if (
                        $plantillaDbId !== null
                        && !DB::table('plantilla_db')
                            ->where('id', $plantillaDbId)
                            ->exists()
                    ) {
                        throw new \RuntimeException(
                            'The selected plantilla item no longer exists. '
                            . 'Please upload the file again.'
                        );
                    }

                    if (
                        $schoolDbId !== null
                        && !DB::table('school_db')
                            ->where('id', $schoolDbId)
                            ->exists()
                    ) {
                        throw new \RuntimeException(
                            'The selected school no longer exists. '
                            . 'Please upload the file again.'
                        );
                    }

                    // Find employment records by employee, not plantilla.
                    $existingRecords = DB::table('employment_status')
                        ->where('users_id', $user->id)
                        ->lockForUpdate()
                        ->get(['id']);

                    if ($existingRecords->count() > 1) {
                        throw new \RuntimeException(
                            "Employee {$email} already has multiple employment "
                            . 'status records. Resolve these before importing '
                            . 'this employee.'
                        );
                    }

                    $existing = $existingRecords->first();
                    $timestamp = now();

                    $employmentData = [
                        'plantilla_db_id' => $plantillaDbId,
                        'school_db_id' => $schoolDbId,
                        'date_of_original_appointment' => $nullableValue(
                            $record['date_of_original_appointment'] ?? null
                        ),
                        'date_of_last_promotion' => $nullableValue(
                            $record['date_of_last_promotion'] ?? null
                        ),
                        'employment_status' => $nullableValue(
                            $record['employment_status'] ?? null
                        ),
                        'warm_body_status' => $nullableValue(
                            $record['warm_body_status'] ?? null
                        ),
                        'nature_of_work' => $nullableValue(
                            $record['nature_of_work'] ?? null
                        ),
                        'source_of_fund' => $nullableValue(
                            $record['source_of_fund'] ?? null
                        ),
                        'monthly_salary' => $nullableValue(
                            $record['monthly_salary'] ?? null
                        ),
                        'contract_duration' => $nullableValue(
                            $record['contract_duration'] ?? null
                        ),
                        'updated_at' => $timestamp,
                    ];

                    if ($existing) {
                        DB::table('employment_status')
                            ->where('id', $existing->id)
                            ->update($employmentData);

                        return 'updated';
                    }

                    $employmentData['users_id'] = $user->id;
                    $employmentData['created_at'] = $timestamp;

                    DB::table('employment_status')
                        ->insert($employmentData);

                    return 'imported';
                });

                if ($result === 'updated') {
                    $updated++;
                } else {
                    $imported++;
                }
            } catch (\Throwable $e) {
                $skipped++;

                // Log technical details without exposing SQL in the interface.
                report($e);

                $errors[] = [
                    'row' => $excelRow,
                    'email' => $email,
                    'message' => $e instanceof \Illuminate\Database\QueryException
                        ? 'Unable to save this row because of a database error. '
                            . 'Check the row values and application log.'
                        : $e->getMessage(),
                ];
            }
        }

        session()->forget([
            'employment_status_import_records',
            'employment_status_import_errors',
        ]);

        return redirect()
            ->route('data-management.employment-status')
            ->with('employment_import_result', [
                'imported' => $imported,
                'updated' => $updated,
                'skipped' => $skipped,
                'errors' => $errors,
                'duplicate_plantilla' => false,
            ]);
    }

    public function downloadEmploymentStatusTemplate()
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Employment Status');


        /*
        |--------------------------------------------------------------------------
        | TITLE
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A1:K1');

        $sheet->setCellValue(
            'A1',
            'EMPLOYMENT STATUS RECORDS'
        );

        $sheet->getStyle('A1')->applyFromArray([

            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => [
                    'rgb' => 'FFFFFF'
                ],
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '15803D'
                ],
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],

        ]);

        $sheet->getRowDimension(1)->setRowHeight(28);


        /*
        |--------------------------------------------------------------------------
        | INSTRUCTIONS
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A2:K2');

        $sheet->setCellValue(
            'A2',
            'Please do not modify the column headers. Enter one personnel employment record per row.'
        );

        $sheet->getStyle('A2')->applyFromArray([

            'font' => [
                'italic' => true,
                'size' => 10,
                'color' => [
                    'rgb' => '666666'
                ],
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],

        ]);

        $sheet->getRowDimension(2)->setRowHeight(22);


        /*
        |--------------------------------------------------------------------------
        | COLUMN HEADERS
        |--------------------------------------------------------------------------
        */

        $headers = [

            'A4' => 'email',

            'B4' => 'item_number',

            'C4' => 'school_id',

            'D4' => 'date_of_original_appointment',

            'E4' => 'date_of_last_promotion',

            'F4' => 'employment_status',

            'G4' => 'warm_body_status',

            'H4' => 'nature_of_work',

            'I4' => 'source_of_fund',

            'J4' => 'monthly_salary',

            'K4' => 'contract_duration',

        ];

        foreach ($headers as $cell => $value) {

            $sheet->setCellValue(
                $cell,
                $value
            );

        }


        /*
        |--------------------------------------------------------------------------
        | HEADER STYLE
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A4:K4')->applyFromArray([

            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' => 'FFFFFF'
                ],
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '166534'
                ],
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],

            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => 'D1D5DB'
                    ],
                ],
            ],

        ]);

        $sheet->getRowDimension(4)->setRowHeight(40);


        /*
        |--------------------------------------------------------------------------
        | SAMPLE DATA
        |--------------------------------------------------------------------------
        */

        $sheet->setCellValue(
            'A5',
            'example@deped.gov.ph'
        );

        $sheet->setCellValue(
            'B5',
            'OSEC-DESCB-TCH1-123456-2026'
        );

        $sheet->setCellValue(
            'C5',
            '123456'
        );

        $sheet->setCellValue(
            'D5',
            '01/15/2020'
        );

        $sheet->setCellValue(
            'E5',
            '01/15/2024'
        );

        $sheet->setCellValue(
            'F5',
            'Permanent'
        );

        $sheet->setCellValue(
            'G5',
            'Original'
        );

        $sheet->setCellValue(
            'H5',
            'Teaching Services'
        );

        $sheet->setCellValue(
            'I5',
            'General Fund'
        );

        $sheet->setCellValue(
            'J5',
            30000
        );

        $sheet->setCellValue(
            'K5',
            'Permanent'
        );


        /*
        |--------------------------------------------------------------------------
        | SAMPLE DATA STYLE
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A5:K5')->applyFromArray([

            'font' => [
                'color' => [
                    'rgb' => '6B7280'
                ],
                'italic' => true,
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'F9FAFB'
                ],
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | EMPLOYMENT STATUS DROPDOWN
        |--------------------------------------------------------------------------
        */

        $employmentStatusValidation = new DataValidation();

        $employmentStatusValidation
            ->setType(DataValidation::TYPE_LIST)
            ->setErrorStyle(DataValidation::STYLE_STOP)
            ->setAllowBlank(true)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('Invalid Employment Status')
            ->setError(
                'Please select a valid employment status.'
            )
            ->setFormula1(
                '"Permanent,Provisional,Temporary,Contractual,Casual,Contract of Service,Job Order,LGU Deployed"'
            );


        /*
        |--------------------------------------------------------------------------
        | WARM BODY STATUS DROPDOWN
        |--------------------------------------------------------------------------
        */

        $warmBodyValidation = new DataValidation();

        $warmBodyValidation
            ->setType(DataValidation::TYPE_LIST)
            ->setErrorStyle(DataValidation::STYLE_STOP)
            ->setAllowBlank(true)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('Invalid Warm Body Status')
            ->setError(
                'Please select a valid warm body status.'
            )
            ->setFormula1(
                '"Original,Borrowed,Detailed,TIC,ALS,SNED,Vacant (Retired),Vacant (Resigned),Vacant (Others)"'
            );


        /*
        |--------------------------------------------------------------------------
        | NATURE OF WORK DROPDOWN
        |--------------------------------------------------------------------------
        */

        $natureOfWorkValidation = new DataValidation();

        $natureOfWorkValidation
            ->setType(DataValidation::TYPE_LIST)
            ->setErrorStyle(DataValidation::STYLE_STOP)
            ->setAllowBlank(true)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('Invalid Nature of Work')
            ->setError(
                'Please select a valid nature of work.'
            )
            ->setFormula1(
                '"District Supervisor,Teaching Services,School Administration,Administrative Support,Clerical Services,Driving Services,Engineering Services,Health and Allied Services,IT Services,Janitorial Services,Legal Services,Security Services,Technical Services,Labor Services,Executive or Management Services,Others"'
            );


        /*
        |--------------------------------------------------------------------------
        | SOURCE OF FUND DROPDOWN
        |--------------------------------------------------------------------------
        */

        $sourceOfFundValidation = new DataValidation();

        $sourceOfFundValidation
            ->setType(DataValidation::TYPE_LIST)
            ->setErrorStyle(DataValidation::STYLE_STOP)
            ->setAllowBlank(true)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('Invalid Source of Fund')
            ->setError(
                'Please select a valid source of fund.'
            )
            ->setFormula1(
                '"Plantilla,MOOE/GMS,LGU Funds,LGU SEFs,Program Support Funds"'
            );


        /*
        |--------------------------------------------------------------------------
        | APPLY DROPDOWNS
        |--------------------------------------------------------------------------
        |
        | Apply validation to rows 5-1000.
        |
        */

        for ($row = 5; $row <= 1000; $row++) {

            /*
            | Employment Status
            */

            $sheet
                ->getCell("F{$row}")
                ->setDataValidation(
                    clone $employmentStatusValidation
                );


            /*
            | Warm Body Status
            */

            $sheet
                ->getCell("G{$row}")
                ->setDataValidation(
                    clone $warmBodyValidation
                );


            /*
            | Nature of Work
            */

            $sheet
                ->getCell("H{$row}")
                ->setDataValidation(
                    clone $natureOfWorkValidation
                );


            /*
            | Source of Fund
            */

            $sheet
                ->getCell("I{$row}")
                ->setDataValidation(
                    clone $sourceOfFundValidation
                );

        }


        /*
        |--------------------------------------------------------------------------
        | BORDERS FOR DATA AREA
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A4:K1000')->applyFromArray([

            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => 'E5E7EB'
                    ],
                ],
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | COLUMN WIDTH
        |--------------------------------------------------------------------------
        */

        $sheet->getColumnDimension('A')
            ->setWidth(32);

        $sheet->getColumnDimension('B')
            ->setWidth(20);

        $sheet->getColumnDimension('C')
            ->setWidth(18);

        $sheet->getColumnDimension('D')
            ->setWidth(28);

        $sheet->getColumnDimension('E')
            ->setWidth(25);

        $sheet->getColumnDimension('F')
            ->setWidth(22);

        $sheet->getColumnDimension('G')
            ->setWidth(22);

        $sheet->getColumnDimension('H')
            ->setWidth(20);

        $sheet->getColumnDimension('I')
            ->setWidth(22);

        $sheet->getColumnDimension('J')
            ->setWidth(18);

        $sheet->getColumnDimension('K')
            ->setWidth(22);


        /*
        |--------------------------------------------------------------------------
        | FREEZE HEADER
        |--------------------------------------------------------------------------
        */

        $sheet->freezePane('A5');


        /*
        |--------------------------------------------------------------------------
        | AUTO FILTER
        |--------------------------------------------------------------------------
        */

        $sheet->setAutoFilter('A4:K1000');


        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD
        |--------------------------------------------------------------------------
        */

        $fileName =
            'PDMS_Employment_Status_Template.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(
            function () use ($writer) {

                $writer->save('php://output');

            },
            $fileName,
            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }

    public function editEmploymentStatus($employmentStatus)
    {
        /*
        |--------------------------------------------------------------------------
        | Employment Record
        |--------------------------------------------------------------------------
        */

        $record = \App\Models\EmploymentStatus::whereHas('user')
            ->with([
                'user.basicInformation',
                'plantilla',
                'school',
                'officeUnit.officeGroup',
                'officeUnit.parent',
            ])
            ->findOrFail($employmentStatus);


        /*
        |--------------------------------------------------------------------------
        | Logged-in User
        |--------------------------------------------------------------------------
        */

        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Determine Current Personnel Assignment
        |--------------------------------------------------------------------------
        |
        | school_db_id  = School Based
        | office_unit_id = Division Office
        |
        | old() is prioritized so the selected value remains after
        | validation errors.
        |
        */

        if (old('personnel_assignment')) {

            $personnelAssignment = old('personnel_assignment');

        } elseif (! empty($record->office_unit_id)) {

            $personnelAssignment = 'division_office';

        } elseif (! empty($record->school_db_id)) {

            $personnelAssignment = 'school_based';

        } else {

            $personnelAssignment = null;
        }


        /*
        |--------------------------------------------------------------------------
        | Selected Plantilla Item
        |--------------------------------------------------------------------------
        */

        $selectedItem = (string) old(
            'item_number',
            $record->plantilla?->item_number ?? ''
        );


        /*
        |--------------------------------------------------------------------------
        | Preload Selected Plantilla Item Only
        |--------------------------------------------------------------------------
        */

        $plantillaItems = $selectedItem !== ''
            ? \App\Models\PlantillaDb::query()
                ->where('item_number', $selectedItem)
                ->get([
                    'id',
                    'item_number',
                    'position_title',
                ])
            : collect();


        /*
        |--------------------------------------------------------------------------
        | Schools
        |--------------------------------------------------------------------------
        */

        $schools = \App\Models\SchoolDb::query()
            ->orderBy('school_name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Division Office Units
        |--------------------------------------------------------------------------
        |
        | Load only active office units.
        | Parent and Office Group are included for clearer display.
        |
        */

        $officeUnits = \App\Models\OfficeUnit::query()
            ->with([
                'officeGroup',
                'parent',
            ])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'data-management.employment-status-edit',
            compact(
                'record',
                'plantillaItems',
                'schools',
                'officeUnits',
                'personnelAssignment'
            )
        );
    }

    public function searchEmploymentPlantilla(\Illuminate\Http\Request $request,$employmentStatus) 
    {
        \App\Models\EmploymentStatus::whereHas('user')->findOrFail($employmentStatus);

        $validated = $request->validate([
            'q' => ['required', 'string', 'max:255'],
        ]);

        $input = trim($validated['q']);

        if (!preg_match('/(?<!\d)(\d{6})(?!\d)/', $input, $matches)) {
            return response()->json([]);
        }

        $number = $matches[1];

        $items = \App\Models\PlantillaDb::query()
            ->select(['id', 'item_number', 'position_title'])
            ->where(function ($query) use ($number) {
                $query
                    // Example: OSEC-DECSB-MTCHR2-540126-2018
                    ->where('item_number', 'like', '%-' . $number . '-%')

                    // Also support records without a year or position prefix.
                    ->orWhere('item_number', 'like', '%-' . $number)
                    ->orWhere('item_number', 'like', $number . '-%')
                    ->orWhere('item_number', $number);
            })
            ->orderBy('item_number')
            ->limit(40)
            ->get()
            ->map(function ($item) {
                return [
                    'value' => (string) $item->item_number,
                    'text' => $item->item_number
                        . ' - '
                        . $item->position_title,
                ];
            })
            ->values();

        return response()->json($items);
    }

    public function employmentPlantillaAssignments(
        \Illuminate\Http\Request $request,
        $employmentStatus
    ) {
        /*
        |--------------------------------------------------------------------------
        | Current Employment Record
        |--------------------------------------------------------------------------
        */

        $record = \App\Models\EmploymentStatus::query()
            ->whereHas('user')
            ->findOrFail($employmentStatus);


        /*
        |--------------------------------------------------------------------------
        | Validate Selected Plantilla Item
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'item_number' => [
                'required',
                'string',
                'max:255',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Find Plantilla Item
        |--------------------------------------------------------------------------
        */

        $plantilla = \App\Models\PlantillaDb::query()
            ->where('item_number', $validated['item_number'])
            ->firstOrFail([
                'id',
                'item_number',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Get Other Employees Assigned to the Same Plantilla Item
        |--------------------------------------------------------------------------
        |
        | Exclude the employee currently being edited.
        |
        | Require:
        | - valid User
        | - valid Basic Information
        |
        */

        $assignments = \App\Models\EmploymentStatus::query()
            ->select([
                'id',
                'users_id',
                'plantilla_db_id',
            ])

            ->where('plantilla_db_id', $plantilla->id)

            // Exclude current employee
            ->where('users_id', '!=', $record->users_id)

            // Must have a valid user
            ->whereHas('user')

            // Must have Basic Information
            ->whereHas('user.basicInformation')

            ->with([
                'user:id,name',

                'user.basicInformation:id,users_id,first_name,middle_name,last_name,extension_name',
            ])

            ->get();


        /*
        |--------------------------------------------------------------------------
        | Build Employee Names
        |--------------------------------------------------------------------------
        */

        $names = $assignments

            // Prevent duplicate employee names caused by duplicate
            // EmploymentStatus records.
            ->unique('users_id')

            ->map(function ($assignment) {

                $basic = $assignment->user?->basicInformation;

                /*
                |--------------------------------------------------------------------------
                | Skip Incomplete Basic Information
                |--------------------------------------------------------------------------
                */

                if (! $basic) {
                    return null;
                }


                /*
                |--------------------------------------------------------------------------
                | Build Complete Name
                |--------------------------------------------------------------------------
                */

                $name = collect([

                    $basic->first_name,

                    $basic->middle_name,

                    $basic->last_name,

                    $basic->extension_name,

                ])
                    ->map(function ($part) {

                        return trim((string) $part);

                    })
                    ->filter(function ($part) {

                        return $part !== '';

                    })
                    ->implode(' ');


                /*
                |--------------------------------------------------------------------------
                | Skip Empty Names
                |--------------------------------------------------------------------------
                */

                if (trim($name) === '') {
                    return null;
                }


                return trim($name);
            })

            // Remove null / empty names
            ->filter()

            // Prevent duplicate displayed names
            ->unique()

            // Sort alphabetically
            ->sort()

            ->values();


        /*
        |--------------------------------------------------------------------------
        | Return Assignment Information
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'names' => $names,
        ]);
    }

    public function updateEmploymentStatus(
        Request $request,
        $employmentStatus
    ) {
        /*
        |--------------------------------------------------------------------------
        | Logged-in User
        |--------------------------------------------------------------------------
        */

        $user = auth()->user();

        abort_unless($user, 401);


        /*
        |--------------------------------------------------------------------------
        | Allowed Roles
        |--------------------------------------------------------------------------
        |
        | Super Admin:
        | - Can edit all employment information
        | - Can change School Based / Division Office assignment
        |
        | Admin:
        | - Can edit employees from the Admin's CURRENT school only
        | - Can change the employee's SCHOOL assignment
        | - Cannot assign an employee to the Division Office
        |
        */

        abort_unless(
            in_array($user->role, ['super_admin', 'admin'], true),
            403,
            'You are not authorized to update employment information.'
        );


        /*
        |--------------------------------------------------------------------------
        | Employment Record
        |--------------------------------------------------------------------------
        */

        $record = \App\Models\EmploymentStatus::query()
            ->whereHas('user')
            ->with([
                'user',
                'school',
                'officeUnit',
                'plantilla',
            ])
            ->findOrFail($employmentStatus);


        /*
        |--------------------------------------------------------------------------
        | ADMIN SECURITY CHECK
        |--------------------------------------------------------------------------
        |
        | An Admin can only edit an employee who is CURRENTLY assigned to
        | the same school as the Admin.
        |
        | IMPORTANT:
        | We check the employee's CURRENT school BEFORE changing anything.
        |
        */

        if ($user->role === 'admin') {

            /*
            |--------------------------------------------------------------------------
            | Get Admin's Current School
            |--------------------------------------------------------------------------
            */

            $adminSchoolId = $user->employmentStatus?->school_db_id;


            if (! $adminSchoolId) {

                abort(
                    403,
                    'Your account does not have a valid school assignment.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Employee Must Currently Belong to Admin's School
            |--------------------------------------------------------------------------
            */

            if ((int) $record->school_db_id !== (int) $adminSchoolId) {

                abort(
                    403,
                    'You are not authorized to update this employee because the employee is not assigned to your school.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Prevent Admin From Editing Division Office Personnel
            |--------------------------------------------------------------------------
            */

            if (! empty($record->office_unit_id)) {

                abort(
                    403,
                    'School administrators cannot modify Division Office personnel.'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Validation Rules
        |--------------------------------------------------------------------------
        */

        $rules = [

            'item_number' => [
                'nullable',
                'string',
                'max:255',
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
                'max:255',
            ],

        ];


        /*
        |--------------------------------------------------------------------------
        | Super Admin Assignment Validation
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'super_admin') {

            $rules['personnel_assignment'] = [
                'required',
                'in:school_based,division_office',
            ];

            $rules['school_id'] = [
                'nullable',
                'required_if:personnel_assignment,school_based',
                'string',
            ];

            $rules['office_unit_id'] = [
                'nullable',
                'required_if:personnel_assignment,division_office',
                'integer',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Admin Assignment Validation
        |--------------------------------------------------------------------------
        |
        | Admin has NO personnel_assignment dropdown.
        | Admin has NO office_unit_id dropdown.
        |
        | School is required because Admin is changing school assignment only.
        |
        */

        if ($user->role === 'admin') {

            $rules['school_id'] = [
                'required',
                'string',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            $rules,
            [
                'personnel_assignment.required' =>
                    'Please select the personnel assignment type.',

                'personnel_assignment.in' =>
                    'The selected personnel assignment type is invalid.',

                'school_id.required' =>
                    'Please select the school where the employee will be assigned.',

                'school_id.required_if' =>
                    'Please select the school where the employee will be assigned.',

                'office_unit_id.required_if' =>
                    'Please select the Division Office unit where the employee will be assigned.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | SECURITY: Reject Assignment Manipulation by Admin
        |--------------------------------------------------------------------------
        |
        | Even if someone manually modifies the HTML or sends a custom HTTP
        | request, Admin cannot assign an Office Unit.
        |
        */

        if ($user->role === 'admin') {

            if (
                $request->filled('office_unit_id') ||
                $request->input('personnel_assignment') === 'division_office'
            ) {

                abort(
                    403,
                    'You are not authorized to assign personnel to a Division Office unit.'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Resolve Plantilla Item
        |--------------------------------------------------------------------------
        */

        $plantillaDbId = null;

        if (! empty($validated['item_number'])) {

            $plantilla = \App\Models\PlantillaDb::query()
                ->where(
                    'item_number',
                    $validated['item_number']
                )
                ->first();


            if (! $plantilla) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'The selected plantilla item number was not found.'
                    );
            }


            $plantillaDbId = $plantilla->id;
        }


        /*
        |--------------------------------------------------------------------------
        | Start With Existing Assignment
        |--------------------------------------------------------------------------
        */

        $schoolDbId = $record->school_db_id;

        $officeUnitId = $record->office_unit_id;


        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN - Resolve Assignment
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'super_admin') {

            $assignmentType =
                $validated['personnel_assignment'];


            /*
            |--------------------------------------------------------------------------
            | School Based
            |--------------------------------------------------------------------------
            */

            if ($assignmentType === 'school_based') {

                $school = \App\Models\SchoolDb::query()
                    ->where(
                        'school_id',
                        $validated['school_id']
                    )
                    ->first();


                if (! $school) {

                    return back()
                        ->withInput()
                        ->with(
                            'error',
                            'The selected school was not found.'
                        );
                }


                /*
                | Save selected school
                */

                $schoolDbId = $school->id;


                /*
                | Remove Division Office assignment
                */

                $officeUnitId = null;
            }


            /*
            |--------------------------------------------------------------------------
            | Division Office
            |--------------------------------------------------------------------------
            */

            elseif ($assignmentType === 'division_office') {

                $officeUnit = \App\Models\OfficeUnit::query()
                    ->where(
                        'id',
                        $validated['office_unit_id']
                    )
                    ->where('is_active', true)
                    ->first();


                if (! $officeUnit) {

                    return back()
                        ->withInput()
                        ->with(
                            'error',
                            'The selected Division Office unit was not found or is inactive.'
                        );
                }


                /*
                | Save selected Office Unit
                */

                $officeUnitId = $officeUnit->id;


                /*
                | Remove School assignment
                */

                $schoolDbId = null;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | ADMIN - Resolve New School Assignment
        |--------------------------------------------------------------------------
        |
        | THIS IS THE PART MISSING FROM YOUR OLD CODE.
        |
        | Previously, Admin's submitted school_id was validated but never
        | converted to school_db_id.
        |
        */

        elseif ($user->role === 'admin') {

            $school = \App\Models\SchoolDb::query()
                ->where(
                    'school_id',
                    $validated['school_id']
                )
                ->first();


            if (! $school) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'The selected school was not found.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Save New School
            |--------------------------------------------------------------------------
            */

            $schoolDbId = $school->id;


            /*
            |--------------------------------------------------------------------------
            | Security
            |--------------------------------------------------------------------------
            |
            | An Admin can only make a SCHOOL assignment.
            | Therefore office_unit_id must always be NULL.
            |
            */

            $officeUnitId = null;
        }


        /*
        |--------------------------------------------------------------------------
        | Update Employment Status
        |--------------------------------------------------------------------------
        */

        try {

            \Illuminate\Support\Facades\DB::transaction(
                function () use (
                    $record,
                    $validated,
                    $plantillaDbId,
                    $schoolDbId,
                    $officeUnitId
                ) {

                    $record->update([

                        /*
                        |--------------------------------------------------------------------------
                        | Plantilla
                        |--------------------------------------------------------------------------
                        */

                        'plantilla_db_id' =>
                            $plantillaDbId,


                        /*
                        |--------------------------------------------------------------------------
                        | Personnel Assignment
                        |--------------------------------------------------------------------------
                        */

                        'school_db_id' =>
                            $schoolDbId,

                        'office_unit_id' =>
                            $officeUnitId,


                        /*
                        |--------------------------------------------------------------------------
                        | Appointment Information
                        |--------------------------------------------------------------------------
                        */

                        'date_of_original_appointment' =>
                            $validated['date_of_original_appointment']
                                ?? null,

                        'date_of_last_promotion' =>
                            $validated['date_of_last_promotion']
                                ?? null,


                        /*
                        |--------------------------------------------------------------------------
                        | Employment Information
                        |--------------------------------------------------------------------------
                        */

                        'employment_status' =>
                            $validated['employment_status']
                                ?? null,

                        'warm_body_status' =>
                            $validated['warm_body_status']
                                ?? null,

                        'nature_of_work' =>
                            $validated['nature_of_work']
                                ?? null,

                        'source_of_fund' =>
                            $validated['source_of_fund']
                                ?? null,


                        /*
                        |--------------------------------------------------------------------------
                        | Salary
                        |--------------------------------------------------------------------------
                        */

                        'monthly_salary' =>
                            $validated['monthly_salary']
                                ?? null,


                        /*
                        |--------------------------------------------------------------------------
                        | Contract
                        |--------------------------------------------------------------------------
                        */

                        'contract_duration' =>
                            $validated['contract_duration']
                                ?? null,

                    ]);

                }
            );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Log Actual Error
            |--------------------------------------------------------------------------
            */

            \Illuminate\Support\Facades\Log::error(
                'Employment status update failed.',
                [
                    'employment_status_id' => $record->id,
                    'updated_by' => $user->id,
                    'role' => $user->role,
                    'error' => $e->getMessage(),
                ]
            );


            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to update the employment information. Please try again.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Refresh Updated Record
        |--------------------------------------------------------------------------
        */

        $record->refresh();


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'data-management.employment-status.edit',
                $record->id
            )
            ->with(
                'success',
                'Employment information and school assignment updated successfully.'
            );
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
            | Skip Completely Empty Rows
            |--------------------------------------------------------------------------
            */

            $isEmptyRow = $row->filter(function ($value) {
                return trim((string) $value) !== '';
            })->isEmpty();

            if ($isEmptyRow) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Count Only Actual Data Rows
            |--------------------------------------------------------------------------
            */

            $excelRow = $index + 5;
            $dataRowNumber = $index + 1;


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

            $salaryGrade = trim((string) ($row['salary_grade'] ?? ''));

            if (
                $salaryGrade !== '' &&
                !preg_match('/^[A-Za-z0-9\s\(\)\-\/]+$/', $salaryGrade)
            ) {
                $errors[] = [
                    'row' => $excelRow,
                    'message' => 'Salary Grade contains invalid characters.'
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | Prepare Preview Data
            |--------------------------------------------------------------------------
            */

            $previewRows[] = [

                'excel_row' => $dataRowNumber,

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

    public function downloadPlantillaDatabaseTemplate()
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Plantilla Database');


        /*
        |--------------------------------------------------------------------------
        | TITLE
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A1:I1');

        $sheet->setCellValue(
            'A1',
            'PLANTILLA DATABASE RECORDS'
        );

        $sheet->getStyle('A1')->applyFromArray([

            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => [
                    'rgb' => 'FFFFFF'
                ],
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '15803D'
                ],
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],

        ]);

        $sheet->getRowDimension(1)->setRowHeight(28);


        /*
        |--------------------------------------------------------------------------
        | INSTRUCTIONS
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A2:I2');

        $sheet->setCellValue(
            'A2',
            'Please do not modify the column headers. Enter one plantilla record per row.'
        );

        $sheet->getStyle('A2')->applyFromArray([

            'font' => [
                'italic' => true,
                'size' => 10,
                'color' => [
                    'rgb' => '666666'
                ],
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],

        ]);

        $sheet->getRowDimension(2)->setRowHeight(22);


        /*
        |--------------------------------------------------------------------------
        | COLUMN HEADERS
        |--------------------------------------------------------------------------
        */

        $headers = [

            'A4' => 'item_number',

            'B4' => 'item_from',

            'C4' => 'item_from_school_level',

            'D4' => 'position_title',

            'E4' => 'salary_grade',

            'F4' => 'area_code',

            'G4' => 'area_type',

            'H4' => 'plantilla_level',

            'I4' => 'pppa_attribution',

        ];

        foreach ($headers as $cell => $value) {

            $sheet->setCellValue(
                $cell,
                $value
            );

        }


        /*
        |--------------------------------------------------------------------------
        | HEADER STYLE
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A4:I4')->applyFromArray([

            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' => 'FFFFFF'
                ],
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '166534'
                ],
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],

            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => 'D1D5DB'
                    ],
                ],
            ],

        ]);

        $sheet->getRowDimension(4)->setRowHeight(35);


        /*
        |--------------------------------------------------------------------------
        | SAMPLE DATA
        |--------------------------------------------------------------------------
        */

        $sheet->setCellValue(
            'A5',
            'OSEC-DESCB-TCH1-123456-2026'
        );

        $sheet->setCellValue(
            'B5',
            'DepEd'
        );

        $sheet->setCellValue(
            'C5',
            'Elementary'
        );

        $sheet->setCellValue(
            'D5',
            'Teacher I'
        );

        $sheet->setCellValue(
            'E5',
            '11'
        );

        $sheet->setCellValue(
            'F5',
            '08-LEY'
        );

        $sheet->setCellValue(
            'G5',
            'Leyte'
        );

        $sheet->setCellValue(
            'H5',
            'Elementary'
        );

        $sheet->setCellValue(
            'I5',
            'PPPA'
        );


        /*
        |--------------------------------------------------------------------------
        | SAMPLE DATA STYLE
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A5:I5')->applyFromArray([

            'font' => [
                'color' => [
                    'rgb' => '6B7280'
                ],
                'italic' => true,
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'F9FAFB'
                ],
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | ITEM FROM SCHOOL LEVEL DROPDOWN
        |--------------------------------------------------------------------------
        */

        $schoolLevelValidation = new DataValidation();

        $schoolLevelValidation
            ->setType(DataValidation::TYPE_LIST)
            ->setErrorStyle(DataValidation::STYLE_STOP)
            ->setAllowBlank(true)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('Invalid School Level')
            ->setError(
                'Please select a valid school level.'
            )
            ->setFormula1(
                '"Elementary,Junior High School,Senior High School"'
            );


        /*
        |--------------------------------------------------------------------------
        | AREA TYPE DROPDOWN
        |--------------------------------------------------------------------------
        */

        $areaTypeValidation = new DataValidation();

        $areaTypeValidation
            ->setType(DataValidation::TYPE_LIST)
            ->setErrorStyle(DataValidation::STYLE_STOP)
            ->setAllowBlank(true)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('Invalid Area Type')
            ->setError(
                'Please select a valid area type.'
            )
            ->setFormula1(
                '"I,II-A,II-B,III,IV,V-A,V-B"'
            );


        /*
        |--------------------------------------------------------------------------
        | PLANTILLA LEVEL DROPDOWN
        |--------------------------------------------------------------------------
        */

        $plantillaLevelValidation = new DataValidation();

        $plantillaLevelValidation
            ->setType(DataValidation::TYPE_LIST)
            ->setErrorStyle(DataValidation::STYLE_STOP)
            ->setAllowBlank(true)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('Invalid Plantilla Level')
            ->setError(
                'Please select a valid plantilla level.'
            )
            ->setFormula1(
                '"Teaching,Non-Teaching"'
            );


        /*
        |--------------------------------------------------------------------------
        | APPLY VALIDATIONS
        |--------------------------------------------------------------------------
        |
        | Apply dropdowns to rows 5-1000.
        |
        */

        for ($row = 5; $row <= 1000; $row++) {

            $sheet
                ->getCell("C{$row}")
                ->setDataValidation(
                    clone $schoolLevelValidation
                );

            $sheet
                ->getCell("G{$row}")
                ->setDataValidation(
                    clone $areaTypeValidation
                );

            $sheet
                ->getCell("H{$row}")
                ->setDataValidation(
                    clone $plantillaLevelValidation
                );

        }


        /*
        |--------------------------------------------------------------------------
        | BORDERS FOR DATA AREA
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A4:I1000')->applyFromArray([

            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => 'E5E7EB'
                    ],
                ],
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | COLUMN WIDTH
        |--------------------------------------------------------------------------
        */

        $sheet->getColumnDimension('A')
            ->setWidth(20);

        $sheet->getColumnDimension('B')
            ->setWidth(18);

        $sheet->getColumnDimension('C')
            ->setWidth(28);

        $sheet->getColumnDimension('D')
            ->setWidth(30);

        $sheet->getColumnDimension('E')
            ->setWidth(15);

        $sheet->getColumnDimension('F')
            ->setWidth(18);

        $sheet->getColumnDimension('G')
            ->setWidth(20);

        $sheet->getColumnDimension('H')
            ->setWidth(22);

        $sheet->getColumnDimension('I')
            ->setWidth(22);


        /*
        |--------------------------------------------------------------------------
        | FREEZE HEADER
        |--------------------------------------------------------------------------
        */

        $sheet->freezePane('A5');


        /*
        |--------------------------------------------------------------------------
        | AUTO FILTER
        |--------------------------------------------------------------------------
        */

        $sheet->setAutoFilter('A4:I1000');


        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD
        |--------------------------------------------------------------------------
        */

        $fileName =
            'PDMS_Plantilla_Database_Template.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(
            function () use ($writer) {

                $writer->save('php://output');

            },
            $fileName,
            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }

    /*   
    |   END  OF PLANTILLA POSITION RECORDS FUNCTIONS
    |
    |--------------------------------------------------------------------------
    |  
    |   START OF SCHOOL INFORMATION RECORDS FUNCTIONS
    */


    public function schools(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        $search = trim($request->input('search', ''));


        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */

        $query = DB::table('school_db');


        /*
        |--------------------------------------------------------------------------
        | Search School Records
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $query->where(function ($q) use ($search) {

                $q->where('school_id', 'like', "%{$search}%")

                ->orWhere('school_name', 'like', "%{$search}%")

                ->orWhere('school_district', 'like', "%{$search}%")

                ->orWhere('school_municipality', 'like', "%{$search}%")

                ->orWhere('school_area', 'like', "%{$search}%")

                ->orWhere('legislative_district', 'like', "%{$search}%")

                ->orWhere('school_sector', 'like', "%{$search}%")

                ->orWhere(
                    'school_curricular_offering',
                    'like',
                    "%{$search}%"
                );

            });

        }


        /*
        |--------------------------------------------------------------------------
        | Sort and Paginate
        |--------------------------------------------------------------------------
        */

        $schools = $query
            ->orderBy('school_name')
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'data-management.schools',
            compact(
                'schools',
                'search'
            )
        );
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
            | Skip Completely Empty Rows
            |--------------------------------------------------------------------------
            */

            $isEmptyRow = $row->filter(function ($value) {
                return trim((string) $value) !== '';
            })->isEmpty();

            if ($isEmptyRow) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Actual Excel Row Number
            |--------------------------------------------------------------------------
            */

            $excelRow = $index + 5;
            $dataRowNumber = $index + 1;


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

                'excel_row' => $dataRowNumber,

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

    public function downloadSchoolDatabaseTemplate()
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('School Database');


        /*
        |--------------------------------------------------------------------------
        | TITLE
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A1:H1');

        $sheet->setCellValue(
            'A1',
            'SCHOOL DATABASE RECORDS'
        );

        $sheet->getStyle('A1')->applyFromArray([

            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => [
                    'rgb' => 'FFFFFF'
                ],
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '15803D'
                ],
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],

        ]);

        $sheet->getRowDimension(1)->setRowHeight(28);


        /*
        |--------------------------------------------------------------------------
        | INSTRUCTIONS
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A2:H2');

        $sheet->setCellValue(
            'A2',
            'Please do not modify the column headers. Enter one school record per row.'
        );

        $sheet->getStyle('A2')->applyFromArray([

            'font' => [
                'italic' => true,
                'size' => 10,
                'color' => [
                    'rgb' => '666666'
                ],
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],

        ]);

        $sheet->getRowDimension(2)->setRowHeight(22);


        /*
        |--------------------------------------------------------------------------
        | COLUMN HEADERS
        |--------------------------------------------------------------------------
        */

        $headers = [

            'A4' => 'school_id',

            'B4' => 'school_name',

            'C4' => 'school_area',

            'D4' => 'legislative_district',

            'E4' => 'school_district',

            'F4' => 'school_municipality',

            'G4' => 'school_sector',

            'H4' => 'school_curricular_offering',

        ];

        foreach ($headers as $cell => $value) {

            $sheet->setCellValue(
                $cell,
                $value
            );

        }


        /*
        |--------------------------------------------------------------------------
        | HEADER STYLE
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A4:H4')->applyFromArray([

            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' => 'FFFFFF'
                ],
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '166534'
                ],
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],

            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => 'D1D5DB'
                    ],
                ],
            ],

        ]);

        $sheet->getRowDimension(4)->setRowHeight(30);


        /*
        |--------------------------------------------------------------------------
        | SAMPLE DATA
        |--------------------------------------------------------------------------
        */

        $sheet->setCellValue(
            'A5',
            '123456'
        );

        $sheet->setCellValue(
            'B5',
            'Sample Elementary School'
        );

        $sheet->setCellValue(
            'C5',
            'Leyte Area'
        );

        $sheet->setCellValue(
            'D5',
            '1st Legislative District'
        );

        $sheet->setCellValue(
            'E5',
            'Hilongos District'
        );

        $sheet->setCellValue(
            'F5',
            'Hilongos'
        );

        $sheet->setCellValue(
            'G5',
            'Public'
        );

        $sheet->setCellValue(
            'H5',
            'Elementary'
        );


        /*
        |--------------------------------------------------------------------------
        | SAMPLE DATA STYLE
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A5:H5')->applyFromArray([

            'font' => [
                'color' => [
                    'rgb' => '6B7280'
                ],
                'italic' => true,
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'F9FAFB'
                ],
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | SCHOOL SECTOR DROPDOWN
        |--------------------------------------------------------------------------
        */

        $sectorValidation = new DataValidation();

        $sectorValidation
            ->setType(DataValidation::TYPE_LIST)
            ->setErrorStyle(DataValidation::STYLE_STOP)
            ->setAllowBlank(true)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('Invalid School Sector')
            ->setError(
                'Please select a valid school sector.'
            )
            ->setFormula1(
                '"Public,Private"'
            );


        /*
        |--------------------------------------------------------------------------
        | SCHOOL AREA DROPDOWN
        |--------------------------------------------------------------------------
        */

        $areaValidation = new DataValidation();

        $areaValidation
            ->setType(DataValidation::TYPE_LIST)
            ->setErrorStyle(DataValidation::STYLE_STOP)
            ->setAllowBlank(true)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('Invalid School Area')
            ->setError(
                'Please select a valid school area.'
            )
            ->setFormula1(
                '"I,II-A,II-B,III,IV,V-A,V-B"'
            );


        /*
        |--------------------------------------------------------------------------
        | APPLY DROPDOWNS
        |--------------------------------------------------------------------------
        |
        | Apply validation to rows 5-1000.
        |
        */

        for ($row = 5; $row <= 1000; $row++) {

            $sheet
                ->getCell("C{$row}")
                ->setDataValidation(
                    clone $areaValidation
                );

            $sheet
                ->getCell("G{$row}")
                ->setDataValidation(
                    clone $sectorValidation
                );

        }


        /*
        |--------------------------------------------------------------------------
        | BORDERS FOR DATA AREA
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A4:H1000')->applyFromArray([

            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => 'E5E7EB'
                    ],
                ],
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | COLUMN WIDTH
        |--------------------------------------------------------------------------
        */

        $sheet->getColumnDimension('A')
            ->setWidth(18);

        $sheet->getColumnDimension('B')
            ->setWidth(35);

        $sheet->getColumnDimension('C')
            ->setWidth(20);

        $sheet->getColumnDimension('D')
            ->setWidth(25);

        $sheet->getColumnDimension('E')
            ->setWidth(25);

        $sheet->getColumnDimension('F')
            ->setWidth(25);

        $sheet->getColumnDimension('G')
            ->setWidth(18);

        $sheet->getColumnDimension('H')
            ->setWidth(35);


        /*
        |--------------------------------------------------------------------------
        | FREEZE HEADER
        |--------------------------------------------------------------------------
        */

        $sheet->freezePane('A5');


        /*
        |--------------------------------------------------------------------------
        | AUTO FILTER
        |--------------------------------------------------------------------------
        */

        $sheet->setAutoFilter('A4:H1000');


        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD
        |--------------------------------------------------------------------------
        */

        $fileName =
            'PDMS_School_Database_Template.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(
            function () use ($writer) {

                $writer->save('php://output');

            },
            $fileName,
            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
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
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | SORTING
        |--------------------------------------------------------------------------
        */

        $sort = $request->input('sort', 'created_at');
        $direction = strtolower($request->input('direction', 'desc'));

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        $allowedSorts = [
            'name',
            'school',
            'district',
            'school_level',
            'position',
            'employment_status',
            'mode_of_availment',
            'disbursement_status',
            'created_at',
        ];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }


        /*
        |--------------------------------------------------------------------------
        | BASE QUERY
        |--------------------------------------------------------------------------
        */

        $query = MedicalAllowance::query()
            ->whereNull('users.deleted_at')
            ->select('medical_allowance.*')

            ->leftJoin(
                'users',
                'users.id',
                '=',
                'medical_allowance.users_id'
            )

            ->leftJoin(
                'basic_information',
                'basic_information.users_id',
                '=',
                'users.id'
            )

            ->leftJoin(
                'employment_status',
                'employment_status.users_id',
                '=',
                'users.id'
            )

            ->leftJoin(
                'plantilla_db',
                'plantilla_db.id',
                '=',
                'employment_status.plantilla_db_id'
            )

            ->leftJoin(
                'school_db',
                'school_db.id',
                '=',
                'employment_status.school_db_id'
            )

            ->with([
                'user.basicInformation',
                'user.employmentStatus.plantilla',
                'user.employmentStatus.school',
            ]);


        /*
        |--------------------------------------------------------------------------
        | RESTRICT ADMIN TO THEIR ASSIGNED SCHOOL
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            $schoolId = $user->employmentStatus?->school_db_id;

            if ($schoolId) {

                $query->where(
                    'employment_status.school_db_id',
                    $schoolId
                );

            } else {

                $query->whereRaw('1 = 0');
            }
        }


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $query->where(function ($q) use ($search) {

                /*
                |--------------------------------------------------------------------------
                | EMAIL
                |--------------------------------------------------------------------------
                */

                $q->where(
                    'users.email',
                    'like',
                    "%{$search}%"
                );


                /*
                |--------------------------------------------------------------------------
                | PERSONNEL NAME
                |--------------------------------------------------------------------------
                */

                $q->orWhere(
                    'basic_information.first_name',
                    'like',
                    "%{$search}%"
                );

                $q->orWhere(
                    'basic_information.middle_name',
                    'like',
                    "%{$search}%"
                );

                $q->orWhere(
                    'basic_information.last_name',
                    'like',
                    "%{$search}%"
                );


                /*
                |--------------------------------------------------------------------------
                | MEDICAL ALLOWANCE
                |--------------------------------------------------------------------------
                */

                $q->orWhere(
                    'medical_allowance.mode_of_availment',
                    'like',
                    "%{$search}%"
                );

                $q->orWhere(
                    'medical_allowance.disbursement_status',
                    'like',
                    "%{$search}%"
                );


                /*
                |--------------------------------------------------------------------------
                | EMPLOYMENT STATUS
                |--------------------------------------------------------------------------
                */

                $q->orWhere(
                    'employment_status.employment_status',
                    'like',
                    "%{$search}%"
                );


                /*
                |--------------------------------------------------------------------------
                | SCHOOL
                |--------------------------------------------------------------------------
                */

                $q->orWhere(
                    'school_db.school_name',
                    'like',
                    "%{$search}%"
                );

                $q->orWhere(
                    'school_db.school_district',
                    'like',
                    "%{$search}%"
                );


                /*
                |--------------------------------------------------------------------------
                | POSITION
                |--------------------------------------------------------------------------
                */

                $q->orWhere(
                    'plantilla_db.position_title',
                    'like',
                    "%{$search}%"
                );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | APPLY SORT
        |--------------------------------------------------------------------------
        */

        switch ($sort) {

            case 'name':

                $query
                    ->orderBy(
                        'basic_information.last_name',
                        $direction
                    )
                    ->orderBy(
                        'basic_information.first_name',
                        $direction
                    );

                break;


            case 'school':

                $query->orderBy(
                    'school_db.school_name',
                    $direction
                );

                break;


            case 'district':

                $query->orderBy(
                    'school_db.school_district',
                    $direction
                );

                break;


            case 'school_level':

                $query->orderBy(
                    'plantilla_db.item_from_school_level',
                    $direction
                );

                break;


            case 'position':

                $query->orderBy(
                    'plantilla_db.position_title',
                    $direction
                );

                break;


            case 'employment_status':

                $query->orderBy(
                    'employment_status.employment_status',
                    $direction
                );

                break;


            case 'mode_of_availment':

                $query->orderBy(
                    'medical_allowance.mode_of_availment',
                    $direction
                );

                break;


            case 'disbursement_status':

                $query->orderBy(
                    'medical_allowance.disbursement_status',
                    $direction
                );

                break;


            default:

                $query->orderBy(
                    'medical_allowance.created_at',
                    $direction
                );

                break;
        }


        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $medicalAllowances = $query
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | MEDICAL ALLOWANCE REPORT
        |--------------------------------------------------------------------------
        */

        $medicalReport = Report::where(
            'name_of_report',
            'Medical Allowance Report'
        )->latest('id')->first();

        // employmentStatus.school_db_id references school_db.id.
        // Submissions use school_db.school_id instead.
        $schoolCode = DB::table('school_db')
            ->where('id', $user->employmentStatus?->school_db_id)
            ->value('school_id');

        $medicalSubmission = null;

        if (
            $user->role === 'admin'
            && $medicalReport
            && $schoolCode !== null
            && $schoolCode !== ''
        ) {
            $medicalSubmission = ReportSubmission::with('validatedBy')
                ->where('report_id', $medicalReport->id)
                ->where('school_id', $schoolCode)
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'data-management.medical-allowance',
            compact(
                'medicalAllowances',
                'search',
                'sort',
                'direction',
                'medicalReport',
                'medicalSubmission',
                'schoolCode'
            )
        );
    } 

    public function importMedicalAllowance(Request $request)
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
        | Read Excel
        |--------------------------------------------------------------------------
        */

        $collections = Excel::toCollection(
            new MedicalAllowanceImport,
            $request->file('file')
        );

        $rows = $collections->first();


        /*
        |--------------------------------------------------------------------------
        | Check Empty File
        |--------------------------------------------------------------------------
        */

        if (!$rows || $rows->isEmpty()) {

            return back()
                ->withErrors([
                    'file' =>
                        'The uploaded Excel file contains no records.'
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


        /*
        |--------------------------------------------------------------------------
        | Get Headers
        |--------------------------------------------------------------------------
        |
        | Because MedicalAllowanceImport uses WithHeadingRow
        | and headingRow() returns 4, the rows already contain
        | the headers from Excel Row 4.
        |
        */

        $firstRow = $rows->first();

        $missingColumns = [];


        foreach ($requiredColumns as $column) {

            if (!array_key_exists(
                $column,
                $firstRow->toArray()
            )) {

                $missingColumns[] = $column;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Invalid Header
        |--------------------------------------------------------------------------
        */

        if (!empty($missingColumns)) {

            return back()
                ->withErrors([
                    'file' =>
                        'The Excel file is missing the following columns: '
                        . implode(', ', $missingColumns)
                        . '. Please use the official Medical Allowance template.'
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
            | IMPORTANT:
            | WithHeadingRow uses Row 4 as the header.
            | Therefore first data row is Excel Row 5.
            |
            */

            $excelRow = $index + 5;


            /*
            |--------------------------------------------------------------------------
            | Read Values
            |--------------------------------------------------------------------------
            */

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
            | Skip Completely Empty Rows
            |--------------------------------------------------------------------------
            */

            if (
                $email === '' &&
                $modeOfAvailment === '' &&
                $disbursementStatus === ''
            ) {

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Validate Email
            |--------------------------------------------------------------------------
            */

            if ($email === '') {

                $errors[] = [

                    'row' => $excelRow,

                    'message' =>
                        'Email is required.'

                ];

            }elseif (
                !preg_match(
                    '/^[\p{L}\p{N}._%+\-]+@[\p{L}\p{N}.-]+\.[\p{L}]{2,}$/u',
                    $email
                )
            ) {
                $errors[] = [

                    'row' => $excelRow,

                    'message' =>
                        "Invalid email address: {$email}."

                ];
            }


            /*
            |--------------------------------------------------------------------------
            | Find User
            |--------------------------------------------------------------------------
            */

            $user = null;


            if ($email !== '') {

                $user = User::where(
                    'email',
                    $email
                )->first();


                if (!$user) {

                    $errors[] = [

                        'row' => $excelRow,

                        'message' =>
                            "No personnel account found for {$email}."

                    ];
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Preview Row
            |--------------------------------------------------------------------------
            */

            $previewRows[] = [

                'excel_row' =>
                    $excelRow,

                'email' =>
                    $email,

                'user_id' =>
                    $user?->id,

                'name' =>
                    $user?->name,

                'mode_of_availment' =>
                    $modeOfAvailment,

                'disbursement_status' =>
                    $disbursementStatus,

            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Store Temporary Import Data
        |--------------------------------------------------------------------------
        */

        session([

            'medical_allowance_import_records' =>
                $previewRows,

            'medical_allowance_import_errors' =>
                $errors,

        ]);


        /*
        |--------------------------------------------------------------------------
        | Redirect to Preview
        |--------------------------------------------------------------------------
        */

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
        $user = $request->user();

        abort_unless(
            $user && in_array($user->role, ['super_admin', 'admin'], true),
            403
        );

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:150'],
        ]);

        $search = trim($filters['search'] ?? '');
        $district = $filters['district'] ?? '';

        $adminSchool = null;
        $showOwnSchool = false;

        /*
        |--------------------------------------------------------------------------
        | School admin scope
        |--------------------------------------------------------------------------
        | Initial visit: own school.
        | Search submitted: schools within the assigned district.
        */
        if ($user->role === 'admin') {
            $adminSchool = DB::table('school_db')
                ->where('id', $user->employmentStatus?->school_db_id)
                ->first();

            abort_unless(
                $adminSchool,
                403,
                'Your account has no assigned school.'
            );

            abort_if(
                trim((string) $adminSchool->school_district) === '',
                403,
                'Your assigned school has no district configured.'
            );

            // Always enforce the assigned district on the server.
            $district = $adminSchool->school_district;

            // The existing search form sends "search", even when it is empty.
            $showOwnSchool = !$request->query->has('search');

            if ($showOwnSchool) {
                $search = (string) $adminSchool->school_id;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Personnel with an assigned plantilla
        |--------------------------------------------------------------------------
        */
        $query = DB::table('employment_status')
            ->whereNull('users.deleted_at')
            ->join(
                'users',
                'users.id',
                '=',
                'employment_status.users_id'
            )
            ->join(
                'school_db',
                'school_db.id',
                '=',
                'employment_status.school_db_id'
            )
            ->leftJoin(
                'medical_allowance',
                'medical_allowance.users_id',
                '=',
                'users.id'
            )
            ->whereNotNull('employment_status.plantilla_db_id')
            ->when(
                $user->role === 'admin',
                function ($query) use ($district, $showOwnSchool, $adminSchool) {
                    $query->where('school_db.school_district', $district);

                    if ($showOwnSchool) {
                        $query->where('school_db.id', $adminSchool->id);
                    }
                }
            )
            ->select(
                'school_db.id as school_db_id',
                'school_db.school_id',
                'school_db.school_name',
                'school_db.school_district',
                'school_db.school_area'
            )
            ->selectRaw("
                COUNT(DISTINCT CASE
                    WHEN medical_allowance.mode_of_availment = 'Group Availment (HMO)'
                    THEN users.id
                END) AS group_hmo,

                COUNT(DISTINCT CASE
                    WHEN medical_allowance.mode_of_availment = 'Individual Availment (HMO)'
                    THEN users.id
                END) AS individual_hmo,

                COUNT(DISTINCT CASE
                    WHEN medical_allowance.mode_of_availment = 'Not Eligible'
                    THEN users.id
                END) AS not_eligible,

                COUNT(DISTINCT users.id) AS total_eligible_employee
            ")
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('school_db.school_id', 'like', "%{$search}%")
                        ->orWhere('school_db.school_name', 'like', "%{$search}%")
                        ->orWhere('school_db.school_district', 'like', "%{$search}%");
                });
            })
            ->when(
                $user->role === 'super_admin' && $district !== '',
                function ($query) use ($district) {
                    $query->where('school_db.school_district', $district);
                }
            )
            ->groupBy(
                'school_db.id',
                'school_db.school_id',
                'school_db.school_name',
                'school_db.school_district',
                'school_db.school_area'
            );

        /*
        |--------------------------------------------------------------------------
        | Summary before pagination
        |--------------------------------------------------------------------------
        */
        $summary = DB::query()
            ->fromSub(clone $query, 'school_summary')
            ->selectRaw('
                COUNT(*) AS total_schools,
                COALESCE(SUM(total_eligible_employee), 0) AS total_plantilla_employee,
                COALESCE(SUM(not_eligible), 0) AS total_not_eligible,
                COALESCE(SUM(group_hmo), 0) AS total_group_availment,
                COALESCE(SUM(individual_hmo), 0) AS total_individual_availment
            ')
            ->first();

        $totalSchools = (int) $summary->total_schools;
        $totalPlantillaEmployee = (int) $summary->total_plantilla_employee;
        $totalNotEligible = (int) $summary->total_not_eligible;
        $totalGroupAvailment = (int) $summary->total_group_availment;
        $totalIndividualAvailment = (int) $summary->total_individual_availment;
        $totalEligible = $totalGroupAvailment + $totalIndividualAvailment;

        $reports = $query
            ->orderBy('school_db.school_name')
            ->orderBy('school_db.id')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Available districts
        |--------------------------------------------------------------------------
        */
        $districts = DB::table('school_db')
            ->when(
                $user->role === 'admin',
                fn ($query) => $query->where('school_district', $district)
            )
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
                'district',
                'totalSchools',
                'totalPlantillaEmployee',
                'totalNotEligible',
                'totalGroupAvailment',
                'totalIndividualAvailment',
                'totalEligible'
            )
        );
    }

    public function downloadMedicalAllowanceTemplate()
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Medical Allowance');


        /*
        |--------------------------------------------------------------------------
        | TITLE
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A1:C1');

        $sheet->setCellValue(
            'A1',
            'MEDICAL ALLOWANCE RECORDS'
        );

        $sheet->getStyle('A1')->applyFromArray([

            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => [
                    'rgb' => 'FFFFFF'
                ],
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '15803D'
                ],
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],

        ]);

        $sheet->getRowDimension(1)->setRowHeight(28);


        /*
        |--------------------------------------------------------------------------
        | INSTRUCTIONS
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A2:C2');

        $sheet->setCellValue(
            'A2',
            'Please do not modify the column headers. Enter one personnel record per row.'
        );

        $sheet->getStyle('A2')->applyFromArray([

            'font' => [
                'italic' => true,
                'size' => 10,
                'color' => [
                    'rgb' => '666666'
                ],
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],

        ]);

        $sheet->getRowDimension(2)->setRowHeight(22);


        /*
        |--------------------------------------------------------------------------
        | COLUMN HEADERS
        |--------------------------------------------------------------------------
        */

        $headers = [

            'A4' => 'email',

            'B4' => 'mode_of_availment',

            'C4' => 'disbursement_status',

        ];

        foreach ($headers as $cell => $value) {

            $sheet->setCellValue(
                $cell,
                $value
            );

        }


        /*
        |--------------------------------------------------------------------------
        | HEADER STYLE
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A4:C4')->applyFromArray([

            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' => 'FFFFFF'
                ],
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '166534'
                ],
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],

            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => 'D1D5DB'
                    ],
                ],
            ],

        ]);

        $sheet->getRowDimension(4)->setRowHeight(25);


        /*
        |--------------------------------------------------------------------------
        | SAMPLE DATA
        |--------------------------------------------------------------------------
        */

        $sheet->setCellValue(
            'A5',
            'example@deped.gov.ph'
        );

        $sheet->setCellValue(
            'B5',
            'Group Availment (HMO)'
        );

        $sheet->setCellValue(
            'C5',
            'Paid'
        );


        /*
        |--------------------------------------------------------------------------
        | SAMPLE DATA STYLE
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A5:C5')->applyFromArray([

            'font' => [
                'color' => [
                    'rgb' => '6B7280'
                ],
                'italic' => true,
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'F9FAFB'
                ],
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | MODE OF AVAILMENT DROPDOWN
        |--------------------------------------------------------------------------
        */

        $modeValidation = new DataValidation();

        $modeValidation
            ->setType(DataValidation::TYPE_LIST)
            ->setErrorStyle(DataValidation::STYLE_STOP)
            ->setAllowBlank(true)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('Invalid Mode of Availment')
            ->setError(
                'Please select a valid mode of availment.'
            )
            ->setFormula1(
                '"Group Availment (HMO),Individual Availment (HMO)"'
            );


        /*
        |--------------------------------------------------------------------------
        | DISBURSEMENT STATUS DROPDOWN
        |--------------------------------------------------------------------------
        */

        $statusValidation = new DataValidation();

        $statusValidation
            ->setType(DataValidation::TYPE_LIST)
            ->setErrorStyle(DataValidation::STYLE_STOP)
            ->setAllowBlank(true)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('Invalid Disbursement Status')
            ->setError(
                'Please select Paid or Pending.'
            )
            ->setFormula1(
                '"Disbursed,Pending"'
            );


        /*
        |--------------------------------------------------------------------------
        | APPLY DROPDOWNS
        |--------------------------------------------------------------------------
        |
        | Apply to rows 5-1000.
        |
        */

        for ($row = 5; $row <= 1000; $row++) {

            $sheet
                ->getCell("B{$row}")
                ->setDataValidation(
                    clone $modeValidation
                );

            $sheet
                ->getCell("C{$row}")
                ->setDataValidation(
                    clone $statusValidation
                );

        }


        /*
        |--------------------------------------------------------------------------
        | BORDERS FOR DATA AREA
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A4:C1000')->applyFromArray([

            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => 'E5E7EB'
                    ],
                ],
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | COLUMN WIDTH
        |--------------------------------------------------------------------------
        */

        $sheet->getColumnDimension('A')
            ->setWidth(35);

        $sheet->getColumnDimension('B')
            ->setWidth(45);

        $sheet->getColumnDimension('C')
            ->setWidth(25);


        /*
        |--------------------------------------------------------------------------
        | FREEZE HEADER
        |--------------------------------------------------------------------------
        */

        $sheet->freezePane('A5');


        /*
        |--------------------------------------------------------------------------
        | AUTO FILTER
        |--------------------------------------------------------------------------
        */

        $sheet->setAutoFilter('A4:C1000');


        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD
        |--------------------------------------------------------------------------
        */

        $fileName =
            'PDMS_Medical_Allowance_Template.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(
            function () use ($writer) {

                $writer->save('php://output');

            },
            $fileName,
            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }

    public function updateAvailment(Request $request, $record)
    {
        $validated = $request->validate([
            'mode_of_availment' => [
                'required',
                'in:Group Availment (HMO),Individual Availment (HMO),Not Eligible',
            ],
        ]);

        $medicalAllowance = \App\Models\MedicalAllowance::whereHas('user')
            ->findOrFail($record);

        $medicalAllowance->update([
            'mode_of_availment' => $validated['mode_of_availment'],
        ]);

        return back()->with('success', 'Mode of availment updated successfully.');
    }

    public function validateMedicalAllowance(Request $request, Report $report): RedirectResponse 
    {
        $user = $request->user();

        abort_unless($user && $user->role === 'admin', 403);

        $schoolCode = DB::table('school_db')
            ->where('id', $user->employmentStatus?->school_db_id)
            ->value('school_id');

        abort_if(
            $schoolCode === null || $schoolCode === '',
            403,
            'Your account has no assigned school.'
        );

        $changed = DB::transaction(function () use (
            $report,
            $user,
            $schoolCode
        ) {
            $lockedReport = Report::query()
                ->lockForUpdate()
                ->findOrFail($report->id);

            abort_unless(
                $lockedReport->name_of_report === 'Medical Allowance Report',
                404
            );

            abort_unless(
                $lockedReport->status === 'Ongoing',
                409,
                'This report is closed.'
            );

            $submission = ReportSubmission::query()
                ->where('report_id', $lockedReport->id)
                ->where('school_id', $schoolCode)
                ->lockForUpdate()
                ->first();

            // Only schools assigned when the report was created may submit.
            abort_if(
                !$submission,
                403,
                'Your school is not assigned to this report.'
            );

            // Preserve the original validation details on repeated requests.
            if ($submission->status === 'Verified') {
                return false;
            }

            abort_unless(
                in_array($submission->status, ['Pending', 'Done'], true),
                409,
                'This submission cannot be validated.'
            );

            // Preserve the submitter if the report was already submitted.
            if ($submission->user_id === null) {
                $submission->user_id = $user->id;
            }

            $submission->status = 'Verified';
            $submission->validated_by = $user->id;
            $submission->validated_at = now();
            $submission->save();

            return true;
        });

        return redirect()
            ->route('data-management.medical-allowance')
            ->with(
                'success',
                $changed
                    ? 'Your school’s Medical Allowance Report was validated and submitted.'
                    : 'Your school’s Medical Allowance Report is already verified.'
            );
    }

    public function exportMedicalAllowanceReport(Request $request): StreamedResponse 
    {
        $user = $request->user();

        abort_unless(
            $user && in_array($user->role, ['super_admin', 'admin'], true),
            403
        );

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:150'],
        ]);

        $search = trim($filters['search'] ?? '');
        $district = $filters['district'] ?? '';

        /*
        |--------------------------------------------------------------------------
        | Personnel and medical allowance records
        |--------------------------------------------------------------------------
        */
        $query = DB::table('medical_allowance as medical')
            ->whereNull('users.deleted_at')
            ->join(
                'users',
                'users.id',
                '=',
                'medical.users_id'
            )
            ->leftJoin(
                'basic_information as basic',
                'basic.users_id',
                '=',
                'users.id'
            )
            ->join(
                'employment_status as employment',
                'employment.users_id',
                '=',
                'users.id'
            )
            ->leftJoin(
                'plantilla_db as plantilla',
                'plantilla.id',
                '=',
                'employment.plantilla_db_id'
            )
            ->join(
                'school_db as school',
                'school.id',
                '=',
                'employment.school_db_id'
            )
            ->where('employment.source_of_fund', 'Plantilla')
            ->select([
                'basic.first_name',
                'basic.middle_name',
                'basic.last_name',
                'basic.extension_name',
                'plantilla.item_number as plantilla_number',
                'school.school_id',
                'school.school_name',
                'school.school_district',
                'medical.mode_of_availment',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Restrict school admins to their assigned school
        |--------------------------------------------------------------------------
        */
        if ($user->role === 'admin') {
            $schoolId = $user->employmentStatus?->school_db_id;

            abort_unless(
                $schoolId,
                403,
                'Your account has no assigned school.'
            );

            $query->where('employment.school_db_id', $schoolId);
        }

        /*
        |--------------------------------------------------------------------------
        | Apply search and district filters
        |--------------------------------------------------------------------------
        */
        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query->where(
                    'school.school_id',
                    'like',
                    "%{$search}%"
                )->orWhere(
                    'school.school_name',
                    'like',
                    "%{$search}%"
                );
            });
        }

        if ($district !== '') {
            $query->where('school.school_district', $district);
        }

        $query->orderBy('school.school_name')
            ->orderBy('basic.last_name')
            ->orderBy('basic.first_name')
            ->orderBy('medical.id');

        /*
        |--------------------------------------------------------------------------
        | Create Excel workbook
        |--------------------------------------------------------------------------
        */
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Medical Allowance');

        $sheet->fromArray([
            'First Name',
            'Middle Name',
            'Last Name',
            'Extension Name',
            'Plantilla Number',
            'School ID',
            'School Name',
            'School District',
            'Mode of Availment',
        ], null, 'A1');

        $rowNumber = 2;

        foreach ($query->lazy(500) as $record) {
            $values = [
                $record->first_name,
                $record->middle_name,
                $record->last_name,
                $record->extension_name,
                $record->plantilla_number,
                $record->school_id,
                $record->school_name,
                $record->school_district,
                $record->mode_of_availment,
            ];

            foreach ($values as $column => $value) {
                // Preserve identifiers and prevent text becoming formulas.
                $sheet->setCellValueExplicit(
                    [$column + 1, $rowNumber],
                    (string) ($value ?? ''),
                    DataType::TYPE_STRING
                );
            }

            $rowNumber++;
        }

        /*
        |--------------------------------------------------------------------------
        | Format Excel worksheet
        |--------------------------------------------------------------------------
        */
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '15803D'],
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(28);
        $sheet->freezePane('A2');
        $sheet->setAutoFilter('A1:I' . max(1, $rowNumber - 1));

        foreach (range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        /*
        |--------------------------------------------------------------------------
        | Download Excel file
        |--------------------------------------------------------------------------
        */
        $filename = 'medical-allowance-report-'
            . now('Asia/Manila')->format('Y-m-d-His')
            . '.xlsx';

        return response()->streamDownload(
            function () use ($spreadsheet) {
                try {
                    $writer = new Xlsx($spreadsheet);
                    $writer->save('php://output');
                } finally {
                    $spreadsheet->disconnectWorksheets();
                }
            },
            $filename,
            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Cache-Control' => 'private, no-store',
            ]
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

    public function downloadEnrollmentTemplate()
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Enrollment');


        /*
        |--------------------------------------------------------------------------
        | TITLE
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A1:D1');

        $sheet->setCellValue(
            'A1',
            'ENROLLMENT RECORDS'
        );

        $sheet->getStyle('A1')->applyFromArray([

            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => [
                    'rgb' => 'FFFFFF'
                ],
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '15803D'
                ],
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],

        ]);

        $sheet->getRowDimension(1)->setRowHeight(28);


        /*
        |--------------------------------------------------------------------------
        | INSTRUCTIONS
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A2:D2');

        $sheet->setCellValue(
            'A2',
            'Please do not modify the column headers. Enter one enrollment record per row.'
        );

        $sheet->getStyle('A2')->applyFromArray([

            'font' => [
                'italic' => true,
                'size' => 10,
                'color' => [
                    'rgb' => '666666'
                ],
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],

        ]);

        $sheet->getRowDimension(2)->setRowHeight(22);


        /*
        |--------------------------------------------------------------------------
        | COLUMN HEADERS
        |--------------------------------------------------------------------------
        */

        $headers = [

            'A4' => 'school_id',

            'B4' => 'school_year',

            'C4' => 'grade_level',

            'D4' => 'enrollment_count',

        ];

        foreach ($headers as $cell => $value) {

            $sheet->setCellValue(
                $cell,
                $value
            );

        }


        /*
        |--------------------------------------------------------------------------
        | HEADER STYLE
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A4:D4')->applyFromArray([

            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' => 'FFFFFF'
                ],
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '166534'
                ],
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],

            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => 'D1D5DB'
                    ],
                ],
            ],

        ]);

        $sheet->getRowDimension(4)->setRowHeight(25);


        /*
        |--------------------------------------------------------------------------
        | SAMPLE DATA
        |--------------------------------------------------------------------------
        */

        $sheet->setCellValue(
            'A5',
            '123456'
        );

        $sheet->setCellValue(
            'B5',
            '2025-2026'
        );

        $sheet->setCellValue(
            'C5',
            'Kindergarten'
        );

        $sheet->setCellValue(
            'D5',
            35
        );


        /*
        |--------------------------------------------------------------------------
        | SAMPLE DATA STYLE
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A5:D5')->applyFromArray([

            'font' => [
                'color' => [
                    'rgb' => '6B7280'
                ],
                'italic' => true,
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'F9FAFB'
                ],
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | SCHOOL YEAR DROPDOWN
        |--------------------------------------------------------------------------
        */

        $schoolYearValidation = new DataValidation();

        $schoolYearValidation
            ->setType(DataValidation::TYPE_LIST)
            ->setErrorStyle(DataValidation::STYLE_STOP)
            ->setAllowBlank(true)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('Invalid School Year')
            ->setError(
                'Please select a valid school year.'
            )
            ->setFormula1(
                '"2025-2026,2026-2027"'
            );


        /*
        |--------------------------------------------------------------------------
        | GRADE LEVEL DROPDOWN
        |--------------------------------------------------------------------------
        */

        $gradeLevelValidation = new DataValidation();

        $gradeLevelValidation
            ->setType(DataValidation::TYPE_LIST)
            ->setErrorStyle(DataValidation::STYLE_STOP)
            ->setAllowBlank(true)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('Invalid Grade Level')
            ->setError(
                'Please select a valid grade level.'
            )
            ->setFormula1(
                '"Kindergarten,Grade 1,Grade 2,Grade 3,Grade 4,Grade 5,Grade 6,Grade 7,Grade 8,Grade 9,Grade 10,Grade 11,Grade 12"'
            );


        /*
        |--------------------------------------------------------------------------
        | ENROLLMENT COUNT VALIDATION
        |--------------------------------------------------------------------------
        */

        $enrollmentValidation = new DataValidation();

        $enrollmentValidation
            ->setType(DataValidation::TYPE_WHOLE)
            ->setErrorStyle(DataValidation::STYLE_STOP)
            ->setAllowBlank(true)
            ->setOperator(DataValidation::OPERATOR_GREATERTHANOREQUAL)
            ->setFormula1('0')
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setErrorTitle('Invalid Enrollment Count')
            ->setError(
                'Enrollment count must be a whole number greater than or equal to 0.'
            );


        /*
        |--------------------------------------------------------------------------
        | APPLY DROPDOWNS / VALIDATION
        |--------------------------------------------------------------------------
        |
        | Apply to rows 5-1000.
        |
        */

        for ($row = 5; $row <= 1000; $row++) {

            $sheet
                ->getCell("B{$row}")
                ->setDataValidation(
                    clone $schoolYearValidation
                );

            $sheet
                ->getCell("C{$row}")
                ->setDataValidation(
                    clone $gradeLevelValidation
                );

            $sheet
                ->getCell("D{$row}")
                ->setDataValidation(
                    clone $enrollmentValidation
                );
        }


        /*
        |--------------------------------------------------------------------------
        | BORDERS FOR DATA AREA
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A4:D1000')->applyFromArray([

            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => 'E5E7EB'
                    ],
                ],
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | COLUMN WIDTH
        |--------------------------------------------------------------------------
        */

        $sheet->getColumnDimension('A')
            ->setWidth(18);

        $sheet->getColumnDimension('B')
            ->setWidth(20);

        $sheet->getColumnDimension('C')
            ->setWidth(25);

        $sheet->getColumnDimension('D')
            ->setWidth(22);


        /*
        |--------------------------------------------------------------------------
        | FREEZE HEADER
        |--------------------------------------------------------------------------
        */

        $sheet->freezePane('A5');


        /*
        |--------------------------------------------------------------------------
        | AUTO FILTER
        |--------------------------------------------------------------------------
        */

        $sheet->setAutoFilter('A4:D1000');


        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD
        |--------------------------------------------------------------------------
        */

        $fileName =
            'PDMS_Enrollment_Template.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(
            function () use ($writer) {

                $writer->save('php://output');

            },
            $fileName,
            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }


    /*   
    |   END  OF  ENROLLMENT RECORDS FUNCTIONS
    |
    |--------------------------------------------------------------------------
    |  
    |   START OF OFFICE UNITS DATABASE
    */

    public function officeUnits(Request $request)
    {
        $search = trim($request->input('search', ''));
        $group = $request->input('group');

        /*
        |--------------------------------------------------------------------------
        | Office Groups
        |--------------------------------------------------------------------------
        */

        $officeGroups = OfficeGroup::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Office Units
        |--------------------------------------------------------------------------
        */

        $query = OfficeUnit::with([
            'officeGroup',
            'parent',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('short_name', 'like', "%{$search}%")
                    ->orWhere('unit_type', 'like', "%{$search}%")

                    ->orWhereHas('officeGroup', function ($groupQuery) use ($search) {

                        $groupQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                    })

                    ->orWhereHas('parent', function ($parentQuery) use ($search) {

                        $parentQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                    });
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Office Group Filter
        |--------------------------------------------------------------------------
        */

        if ($group !== null && $group !== '') {

            $query->where('office_group_id', $group);
        }


        /*
        |--------------------------------------------------------------------------
        | Results
        |--------------------------------------------------------------------------
        */

        $officeUnits = $query
            ->orderBy('office_group_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Parent Unit Options
        |--------------------------------------------------------------------------
        */

        $parentUnits = OfficeUnit::with('officeGroup')
            ->where('is_active', true)
            ->orderBy('office_group_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();


        return view(
            'data-management.office-units',
            compact(
                'officeGroups',
                'officeUnits',
                'parentUnits',
                'search',
                'group'
            )
        );
    }

    public function toggleOfficeUnitStatus(
        OfficeUnit $officeUnit
    ) {

        $officeUnit->update([
            'is_active' => !$officeUnit->is_active,
        ]);


        return back()->with(
            'success',
            $officeUnit->is_active
                ? 'Office unit activated successfully.'
                : 'Office unit deactivated successfully.'
        );
    }

    public function destroyOfficeUnit(
        OfficeUnit $officeUnit
    ) {

        /*
        |--------------------------------------------------------------------------
        | Check Child Units
        |--------------------------------------------------------------------------
        */

        if ($officeUnit->children()->exists()) {

            return back()->with(
                'error',
                'This office unit cannot be deleted because it has sub-units.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Check Personnel Assignment
        |--------------------------------------------------------------------------
        */

        if ($officeUnit->employmentStatuses()->exists()) {

            return back()->with(
                'error',
                'This office unit cannot be deleted because personnel are assigned to it. Deactivate the unit instead.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        $officeUnit->delete();


        return back()->with(
            'success',
            'Office unit deleted successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Office Unit
    |--------------------------------------------------------------------------
    */

    public function updateOfficeUnit(
        Request $request,
        \App\Models\OfficeUnit $officeUnit
    )
    {
        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'office_group_id' => [
                'required',
                'exists:office_groups,id',
            ],

            'parent_id' => [
                'nullable',
                'exists:office_units,id',
            ],

            'code' => [
                'nullable',
                'string',
                'max:50',
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'short_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'unit_type' => [
                'required',
                'string',
                'max:50',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Prevent Unit From Being Its Own Parent
        |--------------------------------------------------------------------------
        */

        if (
            !empty($validated['parent_id']) &&
            (int) $validated['parent_id'] === (int) $officeUnit->id
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'parent_id' => 'An office unit cannot be its own parent.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Parent Office Group
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['parent_id'])) {

            $parent = \App\Models\OfficeUnit::findOrFail(
                $validated['parent_id']
            );

            if (
                (int) $parent->office_group_id !==
                (int) $validated['office_group_id']
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'parent_id' =>
                            'The parent unit must belong to the same office group.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Circular Parent Relationship
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['parent_id'])) {

            $parentId = $validated['parent_id'];

            while ($parentId) {

                $parent = \App\Models\OfficeUnit::find($parentId);

                if (!$parent) {
                    break;
                }

                if ((int) $parent->id === (int) $officeUnit->id) {

                    return back()
                        ->withInput()
                        ->withErrors([
                            'parent_id' =>
                                'Invalid parent unit. This would create a circular office hierarchy.',
                        ]);
                }

                $parentId = $parent->parent_id;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Update Office Unit
        |--------------------------------------------------------------------------
        */

        $officeUnit->update([

            'office_group_id' =>
                $validated['office_group_id'],

            'parent_id' =>
                $validated['parent_id'] ?? null,

            'code' =>
                !empty($validated['code'])
                    ? strtoupper(trim($validated['code']))
                    : null,

            'name' =>
                trim($validated['name']),

            'short_name' =>
                !empty($validated['short_name'])
                    ? trim($validated['short_name'])
                    : null,

            'unit_type' =>
                $validated['unit_type'],

            'sort_order' =>
                $validated['sort_order'] ?? 0,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('data-management.office-units')
            ->with(
                'success',
                'Office unit updated successfully.'
            );
    }

    /*   
    |   END  OF OFFICE UNITS DATABASE
    |
    |--------------------------------------------------------------------------
    */

    
}