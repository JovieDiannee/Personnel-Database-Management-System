<?php

namespace App\Http\Controllers;

use App\Models\Employee;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => Employee::count(),
            'active' => Employee::where('employment_status', 'Active')->count(),
            'inactive' => Employee::where('employment_status', 'Inactive')->count(),
            'on_leave' => Employee::where('employment_status', 'On Leave')->count(),
        ];

        $recentEmployees = Employee::latest()->take(5)->get();

        return view('dashboard.index', compact('stats', 'recentEmployees'));
    }
}
