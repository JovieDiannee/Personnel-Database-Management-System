<?php

namespace App\Http\Controllers;

use App\Models\EmploymentStatus;
use App\Models\MedicalAllowance;
use App\Models\Report;
use App\Models\SchoolDb;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Logged-in User
        |--------------------------------------------------------------------------
        */

        $user = auth()->user();

        abort_unless($user, 401);

        /*
        |--------------------------------------------------------------------------
        | Admin School
        |--------------------------------------------------------------------------
        */

        $adminSchoolId = null;

        if ($user->role === 'admin') {
            $adminSchoolId = $user->employmentStatus?->school_db_id;
        }

        /*
        |--------------------------------------------------------------------------
        | Inactive Warm Body Statuses
        |--------------------------------------------------------------------------
        */

        $vacantStatuses = [
            'Vacant (Retired)',
            'Vacant (Resigned)',
            'Vacant (Others)',
        ];

        /*
        |--------------------------------------------------------------------------
        | Active, Non-Deleted Personnel
        |--------------------------------------------------------------------------
        |
        | Assumes deleted employees are soft-deleted
        | in the users table using deleted_at.
        |
        | NULL warm body status is considered active.
        |
        */

        $employmentQuery = EmploymentStatus::query()

            ->whereExists(function ($query) {

                $query->selectRaw('1')
                    ->from('users')
                    ->whereColumn(
                        'users.id',
                        'employment_status.users_id'
                    )
                    ->whereNull('users.deleted_at');

            })

            ->where(function ($query) use ($vacantStatuses) {

                $query
                    ->whereNull(
                        'employment_status.warm_body_status'
                    )
                    ->orWhereNotIn(
                        'employment_status.warm_body_status',
                        $vacantStatuses
                    );

            });

        /*
        |--------------------------------------------------------------------------
        | Restrict Personnel to Admin School
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            if ($adminSchoolId) {

                $employmentQuery->where(
                    'employment_status.school_db_id',
                    $adminSchoolId
                );

            } else {

                $employmentQuery->whereRaw('1 = 0');

            }
        }

        /*
        |--------------------------------------------------------------------------
        | Plantilla-Based Employees
        |--------------------------------------------------------------------------
        |
        | Active + Non-Deleted + Assigned Plantilla Item
        |
        */

        $plantillaEmployees = (clone $employmentQuery)

            ->whereNotNull(
                'employment_status.plantilla_db_id'
            )

            ->distinct()

            ->count('employment_status.users_id');

        /*
        |--------------------------------------------------------------------------
        | Other Funds Employees
        |--------------------------------------------------------------------------
        |
        | Active + Non-Deleted + No Assigned Plantilla
        | Exclude employee ID 1000001.
        |
        */

        $otherFundsEmployees = (clone $employmentQuery)

            ->whereNull(
                'employment_status.plantilla_db_id'
            )

            ->whereNotExists(function ($query) {

                $query->selectRaw('1')

                    ->from('basic_information')

                    ->join(
                        'issued_id',
                        'issued_id.basic_information_id',
                        '=',
                        'basic_information.id'
                    )

                    ->whereColumn(
                        'basic_information.users_id',
                        'employment_status.users_id'
                    )

                    ->where(
                        'issued_id.employee_id',
                        '1000001'
                    );

            })

            ->distinct()

            ->count('employment_status.users_id');

        /*
        |--------------------------------------------------------------------------
        | Number of Schools
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            $numberOfSchools = $adminSchoolId
                ? SchoolDb::where(
                    'id',
                    $adminSchoolId
                )->count()
                : 0;

        } else {

            $numberOfSchools = SchoolDb::count();

        }

        /*
        |--------------------------------------------------------------------------
        | Medical Allowance Base Query
        |--------------------------------------------------------------------------
        |
        | Include only:
        |
        | 1. Year = 2026
        | 2. Source of Fund = Plantilla
        | 3. Non-deleted personnel
        | 4. Admin's assigned school, if applicable
        |
        */

        $medicalQuery = MedicalAllowance::query()

            ->where('year', 2026)

            ->whereHas('user', function ($query) {

                $query->whereNull('users.deleted_at');

            })

            ->whereHas(
                'user.employmentStatus',
                function ($query) use (
                    $vacantStatuses,
                    $user,
                    $adminSchoolId
                ) {

                    $query->where(
                        'source_of_fund',
                        'Plantilla'
                    );

                    /*
                    |----------------------------------------------
                    | Active Personnel Only
                    |----------------------------------------------
                    */

                    // $query->where(
                    //     function ($subQuery) use ($vacantStatuses) {

                    //         $subQuery
                    //             ->whereNull('warm_body_status')
                    //             ->orWhereNotIn(
                    //                 'warm_body_status',
                    //                 $vacantStatuses
                    //             );

                    //     }
                    // );

                    /*
                    |----------------------------------------------
                    | Admin School Restriction
                    |----------------------------------------------
                    */

                    if ($user->role === 'admin') {

                        if ($adminSchoolId) {

                            $query->where(
                                'school_db_id',
                                $adminSchoolId
                            );

                        } else {

                            $query->whereRaw('1 = 0');

                        }
                    }
                }
            );

        /*
        |--------------------------------------------------------------------------
        | Group Availment - 2026
        |--------------------------------------------------------------------------
        */

        $groupAvailment = (clone $medicalQuery)

            ->where(
                'mode_of_availment',
                'Group Availment (HMO)'
            )

            ->distinct()

            ->count('users_id');

        /*
        |--------------------------------------------------------------------------
        | Individual Availment - 2026
        |--------------------------------------------------------------------------
        */

        $individualAvailment = (clone $medicalQuery)

            ->where(
                'mode_of_availment',
                'Individual Availment (HMO)'
            )

            ->distinct()

            ->count('users_id');

        /*
        |--------------------------------------------------------------------------
        | Not Eligible - 2026
        |--------------------------------------------------------------------------
        |
        | Assumes Not Eligible is stored as an exact
        | mode_of_availment value.
        |
        */

        $notEligible = (clone $medicalQuery)

            ->where(
                'mode_of_availment',
                'Not Eligible'
            )

            ->distinct()

            ->count('users_id');

        /*
        |--------------------------------------------------------------------------
        | HR Transactions
        |--------------------------------------------------------------------------
        */

        $hrTransactions = 3110;

        /*
        |--------------------------------------------------------------------------
        | Medical Allowance Report Deadline
        |--------------------------------------------------------------------------
        */

        $medicalReport = Report::where(
            'name_of_report',
            'Medical Allowance Report'
        )
            ->latest('id')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Return Dashboard
        |--------------------------------------------------------------------------
        */

        return view('dashboard', compact(
            'plantillaEmployees',
            'otherFundsEmployees',
            'numberOfSchools',
            'hrTransactions',
            'groupAvailment',
            'individualAvailment',
            'notEligible',
            'medicalReport'
        ));
    }
}