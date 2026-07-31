@extends('layouts.app')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div><h1 class="text-3xl font-bold">Dashboard</h1><p class="text-slate-500">Employee profile overview</p></div>
    <a href="{{ route('employees.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-white hover:bg-slate-700">+ Add Employee</a>
</div>

<div class="grid gap-5 md:grid-cols-4">
    @foreach(['Total Employees' => $stats['total'], 'Active' => $stats['active'], 'Inactive' => $stats['inactive'], 'On Leave' => $stats['on_leave']] as $label => $value)
        <div class="rounded-xl bg-white p-6 shadow-sm"><p class="text-sm text-slate-500">{{ $label }}</p><p class="mt-2 text-3xl font-bold">{{ $value }}</p></div>
    @endforeach
</div>

<div class="mt-8 rounded-xl bg-white p-6 shadow-sm">
    <div class="mb-4 flex justify-between"><h2 class="text-xl font-bold">Recently Added Employees</h2><a href="{{ route('employees.index') }}" class="text-sm text-blue-600">View all</a></div>
    <div class="overflow-x-auto"><table class="w-full text-left text-sm"><thead><tr class="border-b"><th class="px-3 py-3">Employee ID</th><th>Name</th><th>Department</th><th>Status</th></tr></thead><tbody>
        @forelse($recentEmployees as $employee)
        <tr class="border-b"><td class="px-3 py-3">{{ $employee->employee_id }}</td><td><a class="font-medium text-blue-600" href="{{ route('employees.show', $employee) }}">{{ $employee->full_name }}</a></td><td>{{ $employee->department ?? '—' }}</td><td>{{ $employee->employment_status }}</td></tr>
        @empty
        <tr><td colspan="4" class="px-3 py-8 text-center text-slate-500">No employees found.</td></tr>
        @endforelse
    </tbody></table></div>
</div>
@endsection
