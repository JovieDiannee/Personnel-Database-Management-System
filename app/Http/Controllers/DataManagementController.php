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
use App\Exports\EnrollmentTemplateExport;


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

        $query = \App\Models\BasicInformation::with([
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

            $adminSchoolId = $user->employmentStatus?->school_db_id;

            if ($adminSchoolId) {

                $query->whereHas(
                    'user.employmentStatus',
                    function ($employmentQuery) use ($adminSchoolId) {

                        $employmentQuery->where(
                            'school_db_id',
                            $adminSchoolId
                        );

                    }
                );

            } else {

                /*
                | Admin without a school assignment
                | should not see personnel from other schools.
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

                $q->whereHas(
                    'issuedId',
                    function ($issuedIdQuery) use ($search) {

                        $issuedIdQuery->where(
                            'employee_id',
                            'like',
                            "%{$search}%"
                        );

                    }
                )


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
                        last_name,
                        first_name,
                        middle_name,
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
                | User Role
                |--------------------------------------------------------------------------
                */

                ->orWhereHas(
                    'user',
                    function ($userQuery) use ($search) {

                        $userQuery->where(
                            'role',
                            'like',
                            "%{$search}%"
                        );

                    }
                )


                /*
                |--------------------------------------------------------------------------
                | User Status
                |--------------------------------------------------------------------------
                */

                ->orWhereHas(
                    'user',
                    function ($userQuery) use ($search) {

                        $userQuery->where(
                            'status',
                            'like',
                            "%{$search}%"
                        );

                    }
                )


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
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(20)
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

            if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
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

        $sheet->mergeCells('A1:AG1');

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

        $sheet->mergeCells('A2:AG2');

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
            'R5'  => 'Information Technology',
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
            'R'  => 28,
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

        $personnel = \App\Models\BasicInformation::findOrFail($person);


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
        $person = \App\Models\BasicInformation::with([
            'user',
            'issuedId',
            'employmentStatus',
        ])->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Address
        |--------------------------------------------------------------------------
        */

        $addresses = DB::table('address')
            ->where('basic_information_id', $person->id)
            ->get();

        return view(
            'data-management.personnel-edit',
            compact(
                'person',
                'addresses'
            )
        );
    }

    public function updatePersonnel(Request $request, $id)
    {
        $person = \App\Models\BasicInformation::findOrFail($id);

        $validated = $request->validate([

            'email' => [
                'required',
                'email',
            ],

            'first_name' => [
                'required',
                'string',
                'max:255',
            ],

            'middle_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'last_name' => [
                'required',
                'string',
                'max:255',
            ],

            'extension_name' => [
                'nullable',
                'string',
                'max:50',
            ],

            'sex' => [
                'nullable',
                'string',
                'max:20',
            ],

            'birth_place' => [
                'nullable',
                'string',
                'max:255',
            ],

            'birth_date' => [
                'nullable',
                'date',
            ],

            'civil_status' => [
                'nullable',
                'string',
                'max:50',
            ],

            'religion' => [
                'nullable',
                'string',
                'max:100',
            ],

            'citizenship' => [
                'nullable',
                'string',
                'max:100',
            ],

            'mode_of_citizenship' => [
                'nullable',
                'string',
                'max:100',
            ],

            'height_m' => [
                'nullable',
                'numeric',
            ],

            'weight_kg' => [
                'nullable',
                'numeric',
            ],

            'blood_type' => [
                'nullable',
                'string',
                'max:10',
            ],

            'mobile_number' => [
                'nullable',
                'string',
                'max:50',
            ],

            'telephone_number' => [
                'nullable',
                'string',
                'max:50',
            ],

            'specialization' => [
                'nullable',
                'string',
                'max:255',
            ],

            'employee_id' => [
                'nullable',
                'string',
                'max:255',
            ],

        ]);


        DB::transaction(function () use ($person, $validated) {

            /*
            |--------------------------------------------------------------------------
            | BASIC INFORMATION
            |--------------------------------------------------------------------------
            */

            $person->update([

                'first_name' =>
                    $validated['first_name'],

                'middle_name' =>
                    $validated['middle_name'] ?? null,

                'last_name' =>
                    $validated['last_name'],

                'extension_name' =>
                    $validated['extension_name'] ?? null,

                'sex' =>
                    $validated['sex'] ?? null,

                'birth_place' =>
                    $validated['birth_place'] ?? null,

                'birth_date' =>
                    $validated['birth_date'] ?? null,

                'civil_status' =>
                    $validated['civil_status'] ?? null,

                'religion' =>
                    $validated['religion'] ?? null,

                'citizenship' =>
                    $validated['citizenship'] ?? null,

                'mode_of_citizenship' =>
                    $validated['mode_of_citizenship'] ?? null,

                'height_m' =>
                    $validated['height_m'] ?? null,

                'weight_kg' =>
                    $validated['weight_kg'] ?? null,

                'blood_type' =>
                    $validated['blood_type'] ?? null,

                'mobile_number' =>
                    $validated['mobile_number'] ?? null,

                'telephone_number' =>
                    $validated['telephone_number'] ?? null,

                'specialization' =>
                    $validated['specialization'] ?? null,

            ]);


            /*
            |--------------------------------------------------------------------------
            | USER NAME AND EMAIL
            |--------------------------------------------------------------------------
            */

            if ($person->user) {
                $fullName = collect([
                    $validated['first_name'],
                    $validated['middle_name'] ?? null,
                    $validated['last_name'],
                    $validated['extension_name'] ?? null,
                ])
                    ->filter(fn ($value) => filled($value))
                    ->implode(' ');

                $person->user->update([
                    'name' => $fullName,
                    'email' => $validated['email'],
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | ISSUED ID
            |--------------------------------------------------------------------------
            */

            if ($person->issuedId) {

                $person->issuedId->update([
                    'employee_id' =>
                        $validated['employee_id'] ?? null,
                ]);

            } else {

                \App\Models\IssuedId::create([

                    'basic_information_id' =>
                        $person->id,

                    'employee_id' =>
                        $validated['employee_id'] ?? null,

                ]);
            }

        });


        return redirect()
            ->route(
                'data-management.personnel.edit',
                $person->id
            )
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
        | Base Query
        |--------------------------------------------------------------------------
        */

        $query = \App\Models\EmploymentStatus::with([
            'user.basicInformation',
            'plantilla',
            'school',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Role-Based Data Access
        |--------------------------------------------------------------------------
        |
        | Super Admin = View ALL employment status records
        | Admin       = View ONLY records from the same school
        |
        */

        if ($user->role === 'admin') {

            /*
            |--------------------------------------------------------------------------
            | Get Admin's Employment Status / School
            |--------------------------------------------------------------------------
            */

            $adminEmployment = $user->employmentStatus;

            $adminSchool = $adminEmployment?->school;


            /*
            |--------------------------------------------------------------------------
            | Admin Has No School Assignment
            |--------------------------------------------------------------------------
            */

            if (! $adminSchool) {

                $query->whereRaw('1 = 0');

            } else {

                /*
                |--------------------------------------------------------------------------
                | Same School Only
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
        | Search Employment Records
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $query->where(function ($q) use ($search) {

                /*
                |--------------------------------------------------------------------------
                | Search by Personnel Name
                |--------------------------------------------------------------------------
                */

                $q->whereHas(
                    'user.basicInformation',
                    function ($basicQuery) use ($search) {

                        $basicQuery
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


                /*
                |--------------------------------------------------------------------------
                | Search by School Name / School ID
                |--------------------------------------------------------------------------
                */

                $q->orWhereHas(
                    'school',
                    function ($schoolQuery) use ($search) {

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

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | Search by Plantilla Information
                |--------------------------------------------------------------------------
                |
                | Item From School Level
                | Plantilla Item Number
                | Position Title
                |
                */

                $q->orWhereHas(
                    'plantilla',
                    function ($plantillaQuery) use ($search) {

                        $plantillaQuery
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


                /*
                |--------------------------------------------------------------------------
                | Search by Employment Status
                |--------------------------------------------------------------------------
                */

                $q->orWhere(
                    'employment_status',
                    'like',
                    "%{$search}%"
                );


                /*
                |--------------------------------------------------------------------------
                | Search by Date of Original Appointment
                |--------------------------------------------------------------------------
                */

                $q->orWhere(
                    'date_of_original_appointment',
                    'like',
                    "%{$search}%"
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Sort
        |--------------------------------------------------------------------------
        */

        $query->latest('updated_at');


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $employmentStatuses = $query
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
        $records = session('employment_status_import_records');

        /*
        |--------------------------------------------------------------------------
        | CHECK IMPORT RECORDS
        |--------------------------------------------------------------------------
        */

        if (!$records || count($records) === 0) {

            return redirect()
                ->route('data-management.employment-status')
                ->with(
                    'error',
                    'No employment records available for import.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | VARIABLES
        |--------------------------------------------------------------------------
        */

        $imported = 0;
        $updated = 0;
        $skipped = 0;

        $errors = [];



        /*
        |--------------------------------------------------------------------------
        | STEP 1
        | CHECK DUPLICATE EMAIL / PLANTILLA IN EXCEL
        |--------------------------------------------------------------------------
        |
        | Rules:
        |
        | ONE EMAIL     = ONE PLANTILLA
        | ONE PLANTILLA = ONE EMAIL
        |
        |--------------------------------------------------------------------------
        */

        $emailPlantillas = [];
        $plantillaEmails = [];

        $duplicateErrors = [];


        foreach ($records as $index => $record) {

            $excelRow =
                $record['excel_row'] ?? ($index + 2);


            $email = strtolower(
                trim(
                    (string) ($record['email'] ?? '')
                )
            );


            $plantillaDbId =
                trim(
                    (string) (
                        $record['plantilla_db_id'] ?? ''
                    )
                );


            /*
            |--------------------------------------------------------------------------
            | Ignore empty values here
            |--------------------------------------------------------------------------
            |
            | These will be validated again during actual import.
            |
            */

            if ($email === '' || $plantillaDbId === '') {
                continue;
            }



            /*
            |--------------------------------------------------------------------------
            | CHECK 1:
            | SAME EMAIL WITH DIFFERENT PLANTILLA
            |--------------------------------------------------------------------------
            */

            if (isset($emailPlantillas[$email])) {

                $previousPlantilla =
                    $emailPlantillas[$email]['plantilla'];

                $previousRow =
                    $emailPlantillas[$email]['row'];


                if ($previousPlantilla !== $plantillaDbId) {

                    $duplicateErrors[] = [

                        'row' => $excelRow,

                        'email' => $email,

                        'plantilla' => $plantillaDbId,

                        'message' =>
                            "Email {$email} is assigned to more "
                            . "than one Plantilla. "
                            . "Row {$previousRow} has Plantilla "
                            . "{$previousPlantilla}, while row "
                            . "{$excelRow} has Plantilla "
                            . "{$plantillaDbId}."

                    ];
                }
            }



            /*
            |--------------------------------------------------------------------------
            | CHECK 2:
            | SAME PLANTILLA WITH DIFFERENT EMAIL
            |--------------------------------------------------------------------------
            */

            if (isset($plantillaEmails[$plantillaDbId])) {

                $previousEmail =
                    $plantillaEmails[$plantillaDbId]['email'];

                $previousRow =
                    $plantillaEmails[$plantillaDbId]['row'];


                if ($previousEmail !== $email) {

                    $duplicateErrors[] = [

                        'row' => $excelRow,

                        'email' => $email,

                        'plantilla' => $plantillaDbId,

                        'message' =>
                            "Duplicate Plantilla {$plantillaDbId}. "
                            . "It is assigned to {$previousEmail} "
                            . "in Excel row {$previousRow}, "
                            . "but row {$excelRow} is assigned to "
                            . "{$email}."

                    ];
                }
            }



            /*
            |--------------------------------------------------------------------------
            | STORE FOR NEXT COMPARISON
            |--------------------------------------------------------------------------
            */

            $emailPlantillas[$email] = [

                'plantilla' =>
                    $plantillaDbId,

                'row' =>
                    $excelRow,

            ];


            $plantillaEmails[$plantillaDbId] = [

                'email' =>
                    $email,

                'row' =>
                    $excelRow,

            ];
        }



        /*
        |--------------------------------------------------------------------------
        | STEP 2
        | CHECK DUPLICATE PLANTILLA ALREADY IN DATABASE
        |--------------------------------------------------------------------------
        |
        | This checks whether the Plantilla is already assigned
        | to another employee in employment_status.
        |
        |--------------------------------------------------------------------------
        */

        foreach ($records as $index => $record) {

            $excelRow =
                $record['excel_row'] ?? ($index + 2);


            $email = strtolower(
                trim(
                    (string) ($record['email'] ?? '')
                )
            );


            $plantillaDbId =
                trim(
                    (string) (
                        $record['plantilla_db_id'] ?? ''
                    )
                );


            if ($email === '' || $plantillaDbId === '') {
                continue;
            }



            /*
            |--------------------------------------------------------------------------
            | FIND USER
            |--------------------------------------------------------------------------
            */

            $user = DB::table('users')
                ->where('email', $email)
                ->first();


            /*
            |--------------------------------------------------------------------------
            | USER DOES NOT EXIST
            |--------------------------------------------------------------------------
            |
            | This will also be handled during the actual import,
            | so don't duplicate the error here.
            |
            */

            if (!$user) {
                continue;
            }



            /*
            |--------------------------------------------------------------------------
            | FIND OTHER EMPLOYEE USING SAME PLANTILLA
            |--------------------------------------------------------------------------
            */

            $existingPlantilla =
                DB::table('employment_status')
                    ->join(
                        'users',
                        'users.id',
                        '=',
                        'employment_status.users_id'
                    )
                    ->where(
                        'employment_status.plantilla_db_id',
                        $plantillaDbId
                    )
                    ->where(
                        'employment_status.users_id',
                        '!=',
                        $user->id
                    )
                    ->select(
                        'users.email',
                        'employment_status.plantilla_db_id'
                    )
                    ->first();


            /*
            |--------------------------------------------------------------------------
            | DUPLICATE FOUND IN DATABASE
            |--------------------------------------------------------------------------
            */

            if ($existingPlantilla) {

                $existingEmail =
                    strtolower(
                        trim(
                            (string) $existingPlantilla->email
                        )
                    );


                $duplicateErrors[] = [

                    'row' =>
                        $excelRow,

                    'email' =>
                        $email,

                    'plantilla' =>
                        $plantillaDbId,

                    'message' =>
                        "Duplicate Plantilla {$plantillaDbId}. "
                        . "It is already assigned in the database "
                        . "to {$existingEmail}, but Excel row "
                        . "{$excelRow} is assigned to {$email}."

                ];
            }
        }



        /*
        |--------------------------------------------------------------------------
        | STEP 3
        | REMOVE DUPLICATE ERROR MESSAGES
        |--------------------------------------------------------------------------
        */

        $duplicateErrors = collect(
            $duplicateErrors
        )
            ->unique(function ($error) {

                return
                    ($error['row'] ?? '') . '|' .
                    ($error['email'] ?? '') . '|' .
                    ($error['plantilla'] ?? '') . '|' .
                    ($error['message'] ?? '');

            })
            ->values()
            ->toArray();



        /*
        |--------------------------------------------------------------------------
        | STEP 4
        | STOP IMPORT IF DUPLICATE PLANTILLA FOUND
        |--------------------------------------------------------------------------
        */

        if (count($duplicateErrors) > 0) {

            return redirect()
                ->route(
                    'data-management.employment-status'
                )
                ->with(
                    'employment_import_result',
                    [

                        'imported' => 0,

                        'updated' => 0,

                        'skipped' =>
                            count($records),

                        'errors' =>
                            $duplicateErrors,

                        'duplicate_plantilla' =>
                            true,

                    ]
                )
                ->with(
                    'error',
                    'Import stopped. Duplicate Plantilla assignment(s) were found. No records were imported or updated.'
                );
        }



        /*
        |--------------------------------------------------------------------------
        | STEP 5
        | ACTUAL IMPORT / UPDATE
        |--------------------------------------------------------------------------
        */

        DB::beginTransaction();

        try {

            foreach ($records as $index => $record) {

                try {

                    /*
                    |--------------------------------------------------------------------------
                    | EXCEL ROW
                    |--------------------------------------------------------------------------
                    */

                    $excelRow =
                        $record['excel_row'] ?? ($index + 2);



                    /*
                    |--------------------------------------------------------------------------
                    | EMAIL
                    |--------------------------------------------------------------------------
                    */

                    $email = trim(
                        (string) (
                            $record['email'] ?? ''
                        )
                    );


                    if ($email === '') {

                        $skipped++;

                        $errors[] = [

                            'row' =>
                                $excelRow,

                            'message' =>
                                'Email address is missing.'

                        ];

                        continue;
                    }



                    /*
                    |--------------------------------------------------------------------------
                    | PLANTILLA
                    |--------------------------------------------------------------------------
                    */

                    $plantillaDbId =
                        trim(
                            (string) (
                                $record['plantilla_db_id'] ?? ''
                            )
                        );


                    if ($plantillaDbId === '') {

                        $skipped++;

                        $errors[] = [

                            'row' =>
                                $excelRow,

                            'message' =>
                                'Plantilla Item is missing.'

                        ];

                        continue;
                    }



                    /*
                    |--------------------------------------------------------------------------
                    | FIND USER
                    |--------------------------------------------------------------------------
                    */

                    $user = DB::table('users')
                        ->where(
                            'email',
                            $email
                        )
                        ->first();


                    if (!$user) {

                        $skipped++;

                        $errors[] = [

                            'row' =>
                                $excelRow,

                            'message' =>
                                "Email {$email} does not exist in the users table."

                        ];

                        continue;
                    }



                    /*
                    |--------------------------------------------------------------------------
                    | CHECK EXISTING EMPLOYMENT RECORD
                    |--------------------------------------------------------------------------
                    */

                    $existing =
                        DB::table('employment_status')
                            ->where(
                                'users_id',
                                $user->id
                            )
                            ->first();



                    /*
                    |--------------------------------------------------------------------------
                    | EMPLOYMENT DATA
                    |--------------------------------------------------------------------------
                    */

                    $employmentData = [

                        'plantilla_db_id' =>
                            $plantillaDbId,

                        'school_db_id' =>
                            $record['school_db_id'] ?? null,

                        'date_of_original_appointment' =>
                            $record[
                                'date_of_original_appointment'
                            ] ?? null,

                        'date_of_last_promotion' =>
                            $record[
                                'date_of_last_promotion'
                            ] ?? null,

                        'employment_status' =>
                            $record[
                                'employment_status'
                            ] ?? null,

                        'warm_body_status' =>
                            $record[
                                'warm_body_status'
                            ] ?? null,

                        'nature_of_work' =>
                            $record[
                                'nature_of_work'
                            ] ?? null,

                        'source_of_fund' =>
                            $record[
                                'source_of_fund'
                            ] ?? null,

                        'monthly_salary' =>
                            ($record['monthly_salary'] ?? '') !== ''
                                ? $record['monthly_salary']
                                : null,

                        'contract_duration' =>
                            $record[
                                'contract_duration'
                            ] ?? null,

                        'updated_at' =>
                            now(),

                    ];



                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE
                    |--------------------------------------------------------------------------
                    */

                    if ($existing) {

                        DB::table('employment_status')
                            ->where(
                                'id',
                                $existing->id
                            )
                            ->update(
                                $employmentData
                            );

                        $updated++;

                    }



                    /*
                    |--------------------------------------------------------------------------
                    | CREATE
                    |--------------------------------------------------------------------------
                    */

                    else {

                        $employmentData['users_id'] =
                            $user->id;

                        $employmentData['created_at'] =
                            now();


                        DB::table('employment_status')
                            ->insert(
                                $employmentData
                            );

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
            | COMMIT
            |--------------------------------------------------------------------------
            */

            DB::commit();



            /*
            |--------------------------------------------------------------------------
            | REMOVE TEMPORARY SESSION DATA
            |--------------------------------------------------------------------------
            */

            session()->forget([
                'employment_status_import_records',
                'employment_status_import_errors',
            ]);



            /*
            |--------------------------------------------------------------------------
            | RETURN RESULT
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route(
                    'data-management.employment-status'
                )
                ->with(
                    'employment_import_result',
                    [

                        'imported' =>
                            $imported,

                        'updated' =>
                            $updated,

                        'skipped' =>
                            $skipped,

                        'errors' =>
                            $errors,

                        'duplicate_plantilla' =>
                            false,

                    ]
                );


        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | ROLLBACK
            |--------------------------------------------------------------------------
            */

            DB::rollBack();


            return redirect()
                ->route(
                    'data-management.employment-status'
                )
                ->with(
                    'error',
                    'Employment Status import failed: '
                    . $e->getMessage()
                );
        }
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
                '"OriginalBorrowed,Detailed,TIC,ALS,SNED,Vacant (Retired),Vacant (Resigned),Vacant (Others)"'
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
        | Employment Status Record
        |--------------------------------------------------------------------------
        */

        $record = \App\Models\EmploymentStatus::with([
            'user.basicInformation',
            'plantilla',
            'school',
        ])->findOrFail($employmentStatus);


        /*
        |--------------------------------------------------------------------------
        | Plantilla Items
        |--------------------------------------------------------------------------
        |
        | Show:
        | 1. Plantilla items that are NOT assigned to any employee.
        | 2. The current employee's existing plantilla item.
        |
        */

        $plantillaItems = \App\Models\PlantillaDb::where(function ($query) use ($record) {

            /*
            |--------------------------------------------------------------
            | Unassigned Plantilla Items
            |--------------------------------------------------------------
            */

            $query->whereNotIn(
                'id',
                \App\Models\EmploymentStatus::whereNotNull('plantilla_db_id')
                    ->where(
                        'id',
                        '!=',
                        $record->id
                    )
                    ->select('plantilla_db_id')
            );

        })
        ->orderBy('item_number')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Schools
        |--------------------------------------------------------------------------
        */

        $schools = \App\Models\SchoolDb::orderBy('school_name')
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
                'schools'
            )
        );
    }

    public function updateEmploymentStatus(Request $request,$employmentStatus) 
    {
        $record = \App\Models\EmploymentStatus::findOrFail(
            $employmentStatus
        );


        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'item_number' => [
                'nullable',
                'string',
            ],

            'school_id' => [
                'nullable',
                'string',
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

        ]);


        /*
        |--------------------------------------------------------------------------
        | Resolve Plantilla
        |--------------------------------------------------------------------------
        */

        $plantillaDbId = null;

        if (! empty($validated['item_number'])) {

            $plantilla = \App\Models\PlantillaDb::where(
                'item_number',
                $validated['item_number']
            )->first();

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
        | Resolve School
        |--------------------------------------------------------------------------
        */

        $schoolDbId = null;

        if (! empty($validated['school_id'])) {

            $school = \App\Models\SchoolDb::where(
                'school_id',
                $validated['school_id']
            )->first();

            if (! $school) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'The selected school was not found.'
                    );
            }

            $schoolDbId = $school->id;
        }


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $record->update([

            'plantilla_db_id' =>
                $plantillaDbId,

            'school_db_id' =>
                $schoolDbId,

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

        ]);


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
                'Employment status information updated successfully.'
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

        $query = MedicalAllowance::with([
            'user.basicInformation',
            'user.employmentStatus.plantilla',
            'user.employmentStatus.school',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Restrict Admin to Their Assigned School
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {
            $schoolId = $user->employmentStatus?->school_db_id;

            if ($schoolId) {
                $query->whereHas(
                    'user.employmentStatus',
                    function ($employmentQuery) use ($schoolId) {
                        $employmentQuery->where(
                            'school_db_id',
                            $schoolId
                        );
                    }
                );
            } else {
                // Admin without an assigned school sees no records.
                $query->whereRaw('1 = 0');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Super Admin Receives No School Filter
        |--------------------------------------------------------------------------
        */

        $medicalAllowances = $query
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
                                        );
                                }
                            );
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
            compact('medicalAllowances', 'search')
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

            } elseif (!filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            )) {

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
                                = 'Not Eligible'
                            THEN 1
                            ELSE 0
                        END
                    ) as not_eligible
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
        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'mode_of_availment' => [
                'required',
                'in:Group Availment (HMO),Individual Availment (HMO),Not Eligible',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Find Record
        |--------------------------------------------------------------------------
        */

        $medicalAllowance = \App\Models\MedicalAllowance::findOrFail($record);


        /*
        |--------------------------------------------------------------------------
        | Prevent Group → Individual
        |--------------------------------------------------------------------------
        */

        if (
            $medicalAllowance->mode_of_availment === 'Group Availment (HMO)' &&
            $validated['mode_of_availment'] === 'Individual Availment (HMO)'
        ) {
            return back()->with(
                'error',
                'Update not allowed. Group Availment (HMO) cannot be changed to Individual Availment (HMO).'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Check If There Is No Change
        |--------------------------------------------------------------------------
        */

        if (
            $medicalAllowance->mode_of_availment ===
            $validated['mode_of_availment']
        ) {
            return back()->with(
                'error',
                'No changes were made. The selected mode of availment is already the current mode.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $medicalAllowance->update([
            'mode_of_availment' =>
                $validated['mode_of_availment'],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'success',
            'Mode of availment updated successfully from Individual Availment (HMO) to Group Availment (HMO).'
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
   
    */
}