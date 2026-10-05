<?php

namespace App\Http\Controllers;

use App\Models\EmploymentStatus;
use App\Models\MedicalAllowance;
use App\Models\Report;
use App\Models\SchoolDb;

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
        | Employment Status Base Query
        |--------------------------------------------------------------------------
        |
        | Dashboard personnel statistics should include ACTIVE personnel only.
        |
        | Excluded Warm Body Status:
        | - Vacant (Retired)
        | - Vacant (Resigned)
        | - Vacant (Others)
        |
        */

        $employmentQuery = EmploymentStatus::query()
            ->where(function ($query) {

                $query->whereNull('employment_status.warm_body_status')

                    ->orWhereNotIn(
                        'employment_status.warm_body_status',
                        [
                            'Vacant (Retired)',
                            'Vacant (Resigned)',
                            'Vacant (Others)',
                        ]
                    );
            });


        /*
        |--------------------------------------------------------------------------
        | Restrict Employment Records for Admin
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            if ($adminSchoolId) {

                $employmentQuery->where(
                    'employment_status.school_db_id',
                    $adminSchoolId
                );

            } else {

                /*
                |--------------------------------------------------------------------------
                | Admin Has No Assigned School
                |--------------------------------------------------------------------------
                */

                $employmentQuery->whereRaw('1 = 0');
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Plantilla-Based Employees
        |--------------------------------------------------------------------------
        |
        | Count DISTINCT ACTIVE personnel with an assigned plantilla.
        |
        */

        $plantillaEmployees = (clone $employmentQuery)
            ->whereNotNull('employment_status.plantilla_db_id')
            ->distinct()
            ->count('employment_status.users_id');


        /*
        |--------------------------------------------------------------------------
        | Other Funds Employees
        |--------------------------------------------------------------------------
        |
        | Count DISTINCT ACTIVE personnel without an assigned plantilla.
        |
        | Exclude employee ID 1000001 through:
        |
        | employment_status.users_id
        |      -> basic_information.users_id
        |
        | basic_information.id
        |      -> issued_id.basic_information_id
        |
        */

        $otherFundsEmployees = (clone $employmentQuery)

            ->whereNull('employment_status.plantilla_db_id')

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
                ? SchoolDb::where('id', $adminSchoolId)->count()
                : 0;

        } else {

            $numberOfSchools = SchoolDb::count();
        }


        /*
        |--------------------------------------------------------------------------
        | Medical Allowance Base Query
        |--------------------------------------------------------------------------
        |
        | Dashboard Medical Allowance statistics:
        | - Year = 2026
        | - Source of Fund = Plantilla
        |
        */

        $medicalQuery = MedicalAllowance::query()

            /*
            |--------------------------------------------------------------------------
            | 2026 Records Only
            |--------------------------------------------------------------------------
            */

            ->where('year', 2026)

            /*
            |--------------------------------------------------------------------------
            | Plantilla Personnel Only
            |--------------------------------------------------------------------------
            */

            ->whereHas('user.employmentStatus', function ($query) {

                $query->where(
                    'source_of_fund',
                    'Plantilla'
                );
            });


        /*
        |--------------------------------------------------------------------------
        | Restrict Medical Allowance Records for Admin
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            if ($adminSchoolId) {

                $medicalQuery->whereHas(
                    'user.employmentStatus',
                    function ($query) use ($adminSchoolId) {

                        $query->where(
                            'school_db_id',
                            $adminSchoolId
                        );
                    }
                );

            } else {

                /*
                |--------------------------------------------------------------------------
                | Admin Has No Assigned School
                |--------------------------------------------------------------------------
                */

                $medicalQuery->whereRaw('1 = 0');
            }
        }


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
            ->count();


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
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Medical Allowance Received / Disbursed - 2026
        |--------------------------------------------------------------------------
        */

        $numberOfDisbursement = (clone $medicalQuery)
            ->where(
                'disbursement_status',
                'Disbursed'
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | HR Transactions
        |--------------------------------------------------------------------------
        |
        | Temporary until the HR Transactions model/table is connected.
        |
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
            'numberOfDisbursement',
            'medicalReport'
        ));
    }
}