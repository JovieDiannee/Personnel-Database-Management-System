@extends('layouts.app')
@section('content')<div class="mb-6"><h1 class="text-3xl font-bold">Edit Employee</h1><p class="text-slate-500">Update employee profile information.</p></div><form method="POST" action="{{ route('employees.update', $employee) }}" class="rounded-xl bg-white p-6 shadow-sm">@csrf @method('PUT') @include('employees.form', ['buttonText' => 'Update Employee'])</form>@endsection
