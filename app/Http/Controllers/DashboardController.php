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
        */

        $employmentQuery = EmploymentStatus::query();

        if ($user->role === 'admin') {
            if ($adminSchoolId) {
                $employmentQuery->where(
                    'employment_status.school_db_id',
                    $adminSchoolId
                );
            } else {
                // Admin has no assigned school.
                $employmentQuery->whereRaw('1 = 0');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Plantilla-Based Employees
        |--------------------------------------------------------------------------
        | Count distinct personnel with an assigned plantilla.
        */

        $plantillaEmployees = (clone $employmentQuery)
            ->whereNotNull('employment_status.plantilla_db_id')
            ->distinct()
            ->count('employment_status.users_id');

        /*
        |--------------------------------------------------------------------------
        | Other Funds Employees
        |--------------------------------------------------------------------------
        | Count distinct personnel without an assigned plantilla.
        | Exclude employee ID 1000001 through:
        | employment_status.users_id -> basic_information.users_id
        | basic_information.id -> issued_id.basic_information_id
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
                    ->where('issued_id.employee_id', '1000001');
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
                        $query->where('school_db_id', $adminSchoolId);
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
            ->where('mode_of_availment', 'Group Availment (HMO)')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Individual Availment
        |--------------------------------------------------------------------------
        */

        $individualAvailment = (clone $medicalQuery)
            ->where('mode_of_availment', 'Individual Availment (HMO)')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Medical Allowance Received / Disbursed
        |--------------------------------------------------------------------------
        */

        $numberOfDisbursement = (clone $medicalQuery)
            ->where('disbursement_status', 'Disbursed')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | HR Transactions
        |--------------------------------------------------------------------------
        | Temporary until the HR Transactions model/table is connected.
        */

        $hrTransactions = 0;

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