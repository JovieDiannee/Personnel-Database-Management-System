<?php

namespace App\Imports;

use App\Models\SchoolDb;
use App\Models\SchoolYear;
use App\Models\GradeLevel;
use App\Models\Enrollment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class EnrollmentImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $records = [];
        $errors = [];

        foreach ($rows->skip(1) as $index => $row) {

            $excelRow = $index + 2;

            try {

                /*
                |--------------------------------------------------------------------------
                | Read Excel Values
                |--------------------------------------------------------------------------
                */

                $schoolId = trim((string) ($row[0] ?? ''));
                $schoolYear = trim((string) ($row[1] ?? ''));
                $gradeLevel = trim((string) ($row[2] ?? ''));
                $enrollmentCount = trim((string) ($row[3] ?? ''));


                /*
                |--------------------------------------------------------------------------
                | Skip Completely Empty Rows
                |--------------------------------------------------------------------------
                */

                if (
                    $schoolId === '' &&
                    $schoolYear === '' &&
                    $gradeLevel === '' &&
                    $enrollmentCount === ''
                ) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Required Fields
                |--------------------------------------------------------------------------
                */

                if ($schoolId === '') {
                    throw new \Exception(
                        'School ID is missing.'
                    );
                }

                if ($schoolYear === '') {
                    throw new \Exception(
                        'School Year is missing.'
                    );
                }

                if ($gradeLevel === '') {
                    throw new \Exception(
                        'Grade Level is missing.'
                    );
                }

                if ($enrollmentCount === '') {
                    throw new \Exception(
                        'Enrollment Count is missing.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Find School
                |--------------------------------------------------------------------------
                */

                $school = SchoolDb::where(
                    'school_id',
                    $schoolId
                )->first();

                if (!$school) {

                    throw new \Exception(
                        "School ID {$schoolId} was not found in the School Database."
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Find or Create School Year
                |--------------------------------------------------------------------------
                */

                $schoolYearModel = SchoolYear::firstOrCreate(
                    [
                        'school_year' => $schoolYear,
                    ],
                    [
                        'is_current' => false,
                    ]
                );


                /*
                |--------------------------------------------------------------------------
                | Determine School Level
                |--------------------------------------------------------------------------
                */

                $schoolLevel = $this->determineSchoolLevel(
                    $gradeLevel
                );

                if (!$schoolLevel) {

                    throw new \Exception(
                        "Unable to determine school level for Grade Level '{$gradeLevel}'."
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Find or Create Grade Level
                |--------------------------------------------------------------------------
                */

                $gradeLevelModel = GradeLevel::firstOrCreate(
                    [
                        'name' => $gradeLevel,
                    ],
                    [
                        'school_level' => $schoolLevel,
                        'sort_order' => $this->determineSortOrder(
                            $gradeLevel
                        ),
                    ]
                );


                /*
                |--------------------------------------------------------------------------
                | Validate Enrollment Count
                |--------------------------------------------------------------------------
                */

                if (!is_numeric($enrollmentCount)) {

                    throw new \Exception(
                        'Enrollment Count must be a number.'
                    );
                }

                $enrollmentCount = (int) $enrollmentCount;

                if ($enrollmentCount < 0) {

                    throw new \Exception(
                        'Enrollment Count cannot be negative.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Find Existing Enrollment
                |--------------------------------------------------------------------------
                */

                $existing = Enrollment::where(
                    'school_db_id',
                    $school->id
                )
                ->where(
                    'school_year_id',
                    $schoolYearModel->id
                )
                ->where(
                    'grade_level_id',
                    $gradeLevelModel->id
                )
                ->first();


                /*
                |--------------------------------------------------------------------------
                | Determine Action
                |--------------------------------------------------------------------------
                */

                $action = $existing
                    ? 'Update'
                    : 'New';


                /*
                |--------------------------------------------------------------------------
                | Existing Count
                |--------------------------------------------------------------------------
                */

                $existingCount = $existing
                    ? $existing->enrollment_count
                    : 0;


                /*
                |--------------------------------------------------------------------------
                | Prepare Preview Record
                |--------------------------------------------------------------------------
                */

                $records[] = [

                    'excel_row' => $excelRow,

                    'school_id' => $school->school_id,

                    'school_name' => $school->school_name,

                    'school_year' => $schoolYearModel->school_year,

                    'grade_level' => $gradeLevelModel->name,

                    'school_level' => $gradeLevelModel->school_level,

                    'enrollment_count' => $enrollmentCount,

                    'existing_count' => $existingCount,

                    'action' => $action,

                ];

            } catch (\Throwable $e) {

                /*
                |--------------------------------------------------------------------------
                | Validation Error
                |--------------------------------------------------------------------------
                */

                $errors[] = [

                    'row' => $excelRow,

                    'message' => $e->getMessage(),

                ];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Store Preview Data in Session
        |--------------------------------------------------------------------------
        */

        session()->put(
            'enrollment_import_records',
            $records
        );

        session()->put(
            'enrollment_import_errors',
            $errors
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Determine School Level
    |--------------------------------------------------------------------------
    */

    private function determineSchoolLevel(string $gradeLevel): ?string
    {
        $grade = strtolower(
            trim($gradeLevel)
        );


        /*
        |--------------------------------------------------------------------------
        | Kindergarten
        |--------------------------------------------------------------------------
        */

        if (
            $grade === 'kindergarten' ||
            $grade === 'kinder'
        ) {
            return 'Elementary';
        }


        /*
        |--------------------------------------------------------------------------
        | Elementary
        |--------------------------------------------------------------------------
        */

        if (preg_match('/^grade\s*([1-6])$/i', $grade, $matches)) {

            return 'Elementary';
        }


        /*
        |--------------------------------------------------------------------------
        | Junior High School
        |--------------------------------------------------------------------------
        */

        if (preg_match('/^grade\s*(7|8|9|10)$/i', $grade, $matches)) {

            return 'Junior High School';
        }


        /*
        |--------------------------------------------------------------------------
        | Senior High School
        |--------------------------------------------------------------------------
        */

        if (preg_match('/^grade\s*(11|12)$/i', $grade, $matches)) {

            return 'Senior High School';
        }


        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | Determine Grade Level Sort Order
    |--------------------------------------------------------------------------
    */

    private function determineSortOrder(string $gradeLevel): int
    {
        $grade = strtolower(
            trim($gradeLevel)
        );


        /*
        |--------------------------------------------------------------------------
        | Kindergarten
        |--------------------------------------------------------------------------
        */

        if (
            $grade === 'kindergarten' ||
            $grade === 'kinder'
        ) {
            return 0;
        }


        /*
        |--------------------------------------------------------------------------
        | Grade 1 - 12
        |--------------------------------------------------------------------------
        */

        if (
            preg_match(
                '/^grade\s*(\d+)$/i',
                $grade,
                $matches
            )
        ) {

            return (int) $matches[1];
        }


        return 999;
    }
}