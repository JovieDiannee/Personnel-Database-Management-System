<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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