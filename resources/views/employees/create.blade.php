@extends('layouts.app')
@section('content')<div class="mb-6"><h1 class="text-3xl font-bold">Add Employee</h1><p class="text-slate-500">Create a new employee profile.</p></div><form method="POST" action="{{ route('employees.store') }}" class="rounded-xl bg-white p-6 shadow-sm">@csrf @include('employees.form', ['buttonText' => 'Save Employee'])</form>@endsection
