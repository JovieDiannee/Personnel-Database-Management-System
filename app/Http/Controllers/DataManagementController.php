<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PersonnelInformationImport;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class DataManagementController extends Controller
{
    public function index()
    {
        return view('data-management.index');
    }

    public function personnel()
    {
        return view('data-management.personnel');
    }

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
                ->route('data-management.personnel.import')
                ->with('error', 'No personnel records available for import.');
        }

        $imported = 0;
        $skipped = 0;
        $errors = [];

        DB::beginTransaction();

        try {

            foreach ($records as $index => $record) {

                try {

                    $email = trim($record['email'] ?? '');

                    if (empty($email)) {
                        $skipped++;

                        $errors[] = [
                            'row' => $record['excel_row'] ?? ($index + 2),
                            'message' => 'Email address is missing.'
                        ];

                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Check if email already exists
                    |--------------------------------------------------------------------------
                    */

                    if (User::where('email', $email)->exists()) {

                        $skipped++;

                        $errors[] = [
                            'row' => $record['excel_row'] ?? ($index + 2),
                            'message' => "Email {$email} already exists."
                        ];

                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Birth Date
                    |--------------------------------------------------------------------------
                    */

                    $birthDate = $record['birth_date'] ?? null;

                    if (empty($birthDate)) {

                        $skipped++;

                        $errors[] = [
                            'row' => $record['excel_row'] ?? ($index + 2),
                            'message' => 'Birth date is missing.'
                        ];

                        continue;
                    }

                    $birthDateObject = Carbon::parse($birthDate);

                    /*
                    |--------------------------------------------------------------------------
                    | Initial Password
                    |
                    | Example:
                    | 1990-08-21 → 08211990
                    |--------------------------------------------------------------------------
                    */

                    $defaultPassword = $birthDateObject->format('mdY');

                    /*
                    |--------------------------------------------------------------------------
                    | Create User
                    |--------------------------------------------------------------------------
                    */

                    $user = User::create([
                        'name' => trim(
                            ($record['first_name'] ?? '') . ' ' .
                            ($record['middle_name'] ?? '') . ' ' .
                            ($record['last_name'] ?? '') . ' ' .
                            ($record['extension_name'] ?? '')
                        ),

                        'email' => $email,

                        'role' => 'user',

                        'password' => Hash::make($defaultPassword),
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Create Basic Information
                    |--------------------------------------------------------------------------
                    */

                    DB::table('basic_information')->insert([
                        'users_id' => $user->id,

                        'first_name' => $record['first_name'] ?? null,
                        'middle_name' => $record['middle_name'] ?? null,
                        'last_name' => $record['last_name'] ?? null,
                        'extension_name' => $record['extension_name'] ?? null,

                        'sex' => $record['sex'] ?? null,
                        'birth_place' => $record['birth_place'] ?? null,
                        'birth_date' => $birthDateObject->format('Y-m-d'),

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

                    $imported++;

                } catch (\Throwable $e) {

                    $errors[] = [
                        'row' => $record['excel_row'] ?? ($index + 2),
                        'message' => $e->getMessage()
                    ];

                    $skipped++;
                }
            }

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | Remove temporary import data from session
            |--------------------------------------------------------------------------
            */

            session()->forget('personnel_import_records');

            return redirect()
                ->route('data-management.personnel')
                ->with('import_result', [
                    'imported' => $imported,
                    'skipped' => $skipped,
                    'errors' => $errors,
                ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return redirect()
                ->route('data-management.personnel')
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function employmentStatus()
    {
        return view('data-management.employment-status');
    }

    public function plantilla()
    {
        return view('data-management.plantilla');
    }

    public function schools()
    {
        return view('data-management.schools');
    }

    public function medicalAllowance()
    {
        return view('data-management.medical-allowance');
    }

    public function enrollment()
    {
        return view('data-management.enrollment');
    }
}