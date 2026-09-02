<?php

namespace App\Http\Controllers;

use App\Models\EmploymentStatus;
use App\Models\SchoolDb;
use App\Models\MedicalAllowance;

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
        |
        | Super Admin = null because Super Admin can view all records.
        | Admin       = school_db_id of the logged-in Admin.
        |
        */

        $adminSchoolId = null;

        if ($user->role === 'admin') {
            $adminSchoolId = $user->employmentStatus?->school_db_id;
        }


        /*
        |--------------------------------------------------------------------------
        | Employment Status Base Query
        |--------------------------------------------------------------------------
        */

        $employmentQuery = EmploymentStatus::query();


        /*
        |--------------------------------------------------------------------------
        | Restrict Admin to Same School
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            if ($adminSchoolId) {

                $employmentQuery->where(
                    'school_db_id',
                    $adminSchoolId
                );

            } else {

                /*
                | Admin has no school assignment.
                | Return no records.
                */

                $employmentQuery->whereRaw('1 = 0');
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Plantilla-Based Employees
        |--------------------------------------------------------------------------
        */

        $plantillaEmployees = (clone $employmentQuery)
            ->where(
                'source_of_fund',
                'Plantilla'
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Other Funds Employees
        |--------------------------------------------------------------------------
        */

        $otherFundsEmployees = (clone $employmentQuery)
            ->whereNotNull('source_of_fund')
            ->where(
                'source_of_fund',
                '!=',
                'Plantilla'
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Number of Schools
        |--------------------------------------------------------------------------
        |
        | Super Admin = Count all schools
        | Admin       = Count only Admin's assigned school
        |
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
        | MedicalAllowance -> User -> EmploymentStatus -> School
        |
        */

        $medicalQuery = MedicalAllowance::query();


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

                $medicalQuery->whereRaw('1 = 0');
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Group Availment
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
        | Individual Availment
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
        | Medical Allowance Received / Disbursed
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
        | Temporary until HR Transactions model/table is connected.
        |
        */

        $hrTransactions = 0;


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
            'numberOfDisbursement'
        ));
    }
}