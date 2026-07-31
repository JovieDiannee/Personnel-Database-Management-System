@extends('layouts.app')
@section('content')
<div class="mb-6 flex items-center justify-between"><div><h1 class="text-3xl font-bold">Employees</h1><p class="text-slate-500">Manage employee profiles</p></div><a href="{{ route('employees.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-white">+ Add Employee</a></div>
<form class="mb-6 grid gap-3 rounded-xl bg-white p-4 shadow-sm md:grid-cols-4">
    <input name="search" value="{{ request('search') }}" placeholder="Search employee..." class="rounded-lg border px-3 py-2">
    <select name="department" class="rounded-lg border px-3 py-2"><option value="">All Departments</option>@foreach($departments as $department)<option value="{{ $department }}" @selected(request('department') === $department)>{{ $department }}</option>@endforeach</select>
    <select name="employment_status" class="rounded-lg border px-3 py-2"><option value="">All Status</option>@foreach(['Active','Inactive','On Leave'] as $status)<option value="{{ $status }}" @selected(request('employment_status') === $status)>{{ $status }}</option>@endforeach</select>
    <button class="rounded-lg bg-slate-800 px-4 py-2 text-white">Search</button>
</form>
<div class="overflow-hidden rounded-xl bg-white shadow-sm"><div class="overflow-x-auto"><table class="w-full text-left text-sm"><thead class="bg-slate-50"><tr><th class="px-5 py-4">Employee ID</th><th>Name</th><th>Department</th><th>Position</th><th>Status</th><th>Action</th></tr></thead><tbody>
@forelse($employees as $employee)<tr class="border-t"><td class="px-5 py-4">{{ $employee->employee_id }}</td><td class="font-medium">{{ $employee->full_name }}</td><td>{{ $employee->department ?? '—' }}</td><td>{{ $employee->position ?? '—' }}</td><td>{{ $employee->employment_status }}</td><td><a class="text-blue-600" href="{{ route('employees.show', $employee) }}">View</a> <a class="ml-3 text-slate-600" href="{{ route('employees.edit', $employee) }}">Edit</a></td></tr>@empty<tr><td colspan="6" class="px-5 py-10 text-center text-slate-500">No employees found.</td></tr>@endforelse
</tbody></table></div><div class="p-4">{{ $employees->links() }}</div></div>
@endsection
