<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataManagementController extends Controller
{
    public function index()
    {
        return view('data-management.index');
    }
}