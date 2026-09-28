<x-app-layout>
    <div class="py-6">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- PAGE HEADER --}}
            <div>
                <p class="mb-2 text-sm text-gray-500">
                    Danger Zone / Delete Employee
                </p>

                <h1 class="text-2xl font-bold text-gray-900">
                    Delete Employee
                </h1>

                <p class="mt-1 text-sm text-gray-600">
                    @if($isSuperAdmin)
                        Manage deletion requests, move employees to Trash Bin,
                        and restore employee records.
                    @else
                        Request deletion of employees from your assigned school.
                        Employees remain active until your request is approved.
                    @endif
                </p>
            </div>

            {{-- SUCCESS MESSAGE --}}
            @if(session('success'))
                <div
                    role="status"
                    class="rounded-xl border border-green-200
                           bg-green-50 p-4 text-sm text-green-800"
                >
                    {{ session('success') }}
                </div>
            @endif

            {{-- VALIDATION ERRORS --}}
            @if($errors->any())
                <div
                    role="alert"
                    class="rounded-xl border border-red-200
                           bg-red-50 p-4 text-sm text-red-800"
                >
                    <p class="mb-2 font-semibold">
                        Please check the following:
                    </p>

                    <ul class="list-inside list-disc space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ADMIN WITHOUT SCHOOL --}}
            @if(!$isSuperAdmin && !$schoolId)
                <div
                    role="alert"
                    class="rounded-xl border border-yellow-200
                           bg-yellow-50 p-4 text-sm text-yellow-800"
                >
                    Your account has no assigned school.
                    Contact the Personnel Unit before requesting employee deletion.
                </div>
            @endif

            {{-- SECTION LINKS --}}
            <nav
                aria-label="Employee deletion sections"
                class="flex flex-wrap gap-3 text-sm font-semibold"
            >
                <a
                    href="#employees"
                    class="rounded-lg bg-green-50 px-4 py-2
                           text-green-800 hover:bg-green-100"
                >
                    Employees
                </a>

                <a
                    href="#requests"
                    class="rounded-lg bg-green-50 px-4 py-2
                           text-green-800 hover:bg-green-100"
                >
                    {{ $isSuperAdmin ? 'Deletion Requests' : 'My Requests' }}
                </a>

                @if($isSuperAdmin)
                    <a
                        href="#trash"
                        class="rounded-lg bg-red-50 px-4 py-2
                               text-red-700 hover:bg-red-100"
                    >
                        Trash Bin
                    </a>
                @endif
            </nav>

            {{-- =====================================================
                EMPLOYEES
            ====================================================== --}}
            <section
                id="employees"
                class="overflow-hidden rounded-2xl border
                       border-gray-200 bg-white shadow-sm"
            >
                <div class="bg-green-800 px-6 py-4 text-white">
                    <h2 class="text-lg font-bold">
                        Employees
                    </h2>

                    <p class="mt-1 text-sm text-green-100">
                        @if($isSuperAdmin)
                            Regular employee and admin accounts can be moved to Trash Bin.
                            Super admin accounts are protected.
                        @else
                            You can request deletion of employee and admin accounts
                            within your assigned school. Super admin approval is required.
                        @endif
                    </p>
                </div>

                {{-- SEARCH --}}
                <form
                    method="GET"
                    action="{{ route('danger-zone.delete-employee') }}"
                    class="flex flex-col gap-3 border-b
                           border-gray-100 p-4 sm:flex-row"
                >
                    <label for="employee-search" class="sr-only">
                        Search employees
                    </label>

                    <input
                        id="employee-search"
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        maxlength="200"
                        placeholder="Search employee name or email..."
                        class="min-w-0 flex-1 rounded-lg border-gray-300
                               focus:border-green-600 focus:ring-green-600"
                    >

                    <button
                        type="submit"
                        class="rounded-lg bg-green-700 px-5 py-2
                               text-sm font-semibold text-white
                               hover:bg-green-800"
                    >
                        Search
                    </button>

                    @if($search !== '')
                        <a
                            href="{{ route('danger-zone.delete-employee') }}"
                            class="rounded-lg border border-gray-300
                                   px-5 py-2 text-center text-sm
                                   font-semibold text-gray-600
                                   hover:bg-gray-50"
                        >
                            Clear
                        </a>
                    @endif
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th scope="col" class="px-6 py-3">Employee</th>
                                <th scope="col" class="px-6 py-3">Role</th>
                                <th scope="col" class="px-6 py-3">School</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($employees as $employee)
                                @php
                                    $employeeFormKey = 'employee-' . $employee->id;
                                    $employeeFormHasError =
                                        $errors->any() &&
                                        old('form_key') === $employeeFormKey;
                                @endphp

                                <tr class="align-top hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-gray-900">
                                            {{ $employee->name }}
                                        </p>

                                        <p class="mt-1 text-gray-500">
                                            {{ $employee->email }}
                                        </p>
                                    </td>

                                    {{-- USER ROLE --}}
                                    <td class="px-6 py-4">
                                        @php
                                            $roleLabel = match ($employee->role) {
                                                'super_admin' => 'Super Admin',
                                                'admin' => 'Admin',
                                                'user' => 'User',
                                                default => 'Unknown',
                                            };

                                            $roleStyle = match ($employee->role) {
                                                'super_admin' => 'background-color: #f3e8ff; color: #7e22ce;',
                                                'admin' => 'background-color: #dbeafe; color: #1d4ed8;',
                                                'user' => 'background-color: #dcfce7; color: #166534;',
                                                default => 'background-color: #f3f4f6; color: #4b5563;',
                                            };
                                        @endphp

                                        <span
                                            class="inline-flex whitespace-nowrap rounded-full
                                                px-3 py-1 text-xs font-semibold"
                                            style="{{ $roleStyle }}"
                                        >
                                            {{ $roleLabel }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $employee->employmentStatus?->school
                                            ? implode(' - ', array_filter([
                                                $employee->employmentStatus->school->school_name,
                                                $employee->employmentStatus->school->school_district,
                                            ]))
                                            : 'No assigned school' }}
                                    </td>

                                    <td class="min-w-72 px-6 py-4">
                                        @if(in_array($employee->id, $pendingIds))
                                            <span
                                                class="inline-flex rounded-full
                                                       bg-yellow-100 px-3 py-1
                                                       text-xs font-semibold
                                                       text-yellow-800"
                                            >
                                                Pending deletion request
                                            </span>
                                        @else
                                            <details @if($employeeFormHasError) open @endif>
                                                <summary
                                                    class="cursor-pointer
                                                           font-semibold text-red-700"
                                                >
                                                    {{ $isSuperAdmin ? 'Move to Trash Bin' : 'Request Deletion' }}
                                                </summary>

                                                <form
                                                    method="POST"
                                                    action="{{ route('danger-zone.employees.delete', $employee->id) }}"
                                                    class="mt-3 space-y-3"
                                                    onsubmit="return confirm('Submit this action for the selected employee?');"
                                                >
                                                    @csrf

                                                    <input
                                                        type="hidden"
                                                        name="form_key"
                                                        value="{{ $employeeFormKey }}"
                                                    >

                                                    <label
                                                        for="remarks-{{ $employee->id }}"
                                                        class="block text-sm
                                                               font-medium text-gray-700"
                                                    >
                                                        Deletion remarks
                                                        <span class="text-red-600">*</span>
                                                    </label>

                                                    <textarea
                                                        id="remarks-{{ $employee->id }}"
                                                        name="remarks"
                                                        required
                                                        maxlength="2000"
                                                        rows="3"
                                                        placeholder="Explain why this employee should be moved to Trash Bin."
                                                        class="w-full rounded-lg
                                                               border-gray-300 text-sm
                                                               focus:border-red-600
                                                               focus:ring-red-600"
                                                    >{{ old('form_key') === $employeeFormKey ? old('remarks') : '' }}</textarea>

                                                    <p class="text-xs text-gray-500">
                                                        @if($isSuperAdmin)
                                                            Login will be blocked.
                                                            Related employee records will
                                                            be retained for restoration.
                                                        @else
                                                            The employee remains active
                                                            until a super admin approves.
                                                        @endif
                                                    </p>

                                                    <button
                                                        type="submit"
                                                        class="rounded-lg px-4 py-2 text-sm font-semibold"
                                                        style="background-color: #b91c1c; color: #ffffff; border: 1px solid #991b1b;"
                                                    >
                                                        {{ $isSuperAdmin ? 'Confirm Move to Trash Bin' : 'Submit Request' }}
                                                    </button>
                                                </form>
                                            </details>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="4"
                                        class="px-6 py-10 text-center text-gray-500"
                                    >
                                        No eligible employees found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($employees->hasPages())
                    <div class="border-t border-gray-100 px-6 py-4">
                        {{ $employees->fragment('employees')->links() }}
                    </div>
                @endif
            </section>

            {{-- =====================================================
                REQUESTS AND HISTORY
            ====================================================== --}}
            <section
                id="requests"
                class="overflow-hidden rounded-2xl border
                       border-gray-200 bg-white shadow-sm"
            >
                <div class="bg-green-800 px-6 py-4 text-white">
                    <h2 class="text-lg font-bold">
                        {{ $isSuperAdmin ? 'Deletion Requests and History' : 'My Deletion Requests' }}
                    </h2>

                    <p class="mt-1 text-sm text-green-100">
                        Pending requests appear first.
                    </p>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($requests as $item)
                        @php
                            $reviewFormKey = 'review-' . $item->id;
                        @endphp

                        <article class="space-y-3 p-6">
                            <div
                                class="flex flex-wrap items-center
                                       justify-between gap-3"
                            >
                                <div>
                                    <h3 class="font-semibold text-gray-900">
                                        #{{ $item->id }} —
                                        {{ $item->employee?->name ?? 'Unavailable employee' }}
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $item->employee?->email }}
                                    </p>
                                </div>

                                <span
                                    class="rounded-full px-3 py-1
                                           text-xs font-semibold
                                           {{ $item->status === 'pending'
                                                ? 'bg-yellow-100 text-yellow-800'
                                                : ($item->status === 'approved'
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-red-100 text-red-700') }}"
                                >
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>

                            <p class="text-xs text-gray-500">
                                {{ $item->source === 'direct' ? 'Direct move by' : 'Requested by' }}
                                {{ $item->requester?->name ?? 'Unavailable account' }}
                                · {{ $item->created_at->copy()->timezone('Asia/Manila')->format('M d, Y h:i A') }}
                            </p>

                            <div class="rounded-lg bg-gray-50 p-3">
                                <p class="text-xs font-semibold text-gray-600">
                                    Deletion remarks
                                </p>

                                <p class="mt-1 whitespace-pre-wrap break-words text-sm text-gray-800">{{ $item->request_remarks }}</p>
                            </div>

                            @if($item->reviewed_at)
                                <p class="text-xs text-gray-500">
                                    Reviewed by
                                    {{ $item->reviewer?->name ?? 'Unavailable account' }}
                                    · {{ $item->reviewed_at->copy()->timezone('Asia/Manila')->format('M d, Y h:i A') }}
                                </p>

                                @if($item->review_remarks)
                                    <div>
                                        <p class="text-xs font-semibold text-gray-600">
                                            Review remarks
                                        </p>

                                        <p class="mt-1 whitespace-pre-wrap break-words text-sm text-gray-700">{{ $item->review_remarks }}</p>
                                    </div>
                                @endif
                            @endif

                            @if($item->restored_at)
                                <p class="text-sm font-medium text-green-700">
                                    Restored by
                                    {{ $item->restorer?->name ?? 'Unavailable account' }}
                                    · {{ $item->restored_at->copy()->timezone('Asia/Manila')->format('M d, Y h:i A') }}
                                </p>
                            @endif

                            {{-- SUPER ADMIN REVIEW --}}
                            @if($isSuperAdmin && $item->status === 'pending')
                                <form
                                    method="POST"
                                    action="{{ route('danger-zone.requests.review', $item->id) }}"
                                    class="space-y-3 border-t border-gray-100 pt-4"
                                    onsubmit="return confirm('Save this review decision? Approval moves the employee to Trash Bin and blocks login.');"
                                >
                                    @csrf

                                    <input
                                        type="hidden"
                                        name="form_key"
                                        value="{{ $reviewFormKey }}"
                                    >

                                    <label
                                        for="review-{{ $item->id }}"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Review remarks
                                        <span class="text-red-600">*</span>
                                    </label>

                                    <textarea
                                        id="review-{{ $item->id }}"
                                        name="remarks"
                                        required
                                        maxlength="2000"
                                        rows="2"
                                        placeholder="Enter the reason for your decision."
                                        class="w-full rounded-lg border-gray-300
                                               text-sm focus:border-green-600
                                               focus:ring-green-600"
                                    >{{ old('form_key') === $reviewFormKey ? old('remarks') : '' }}</textarea>

                                    <div class="flex flex-wrap gap-3">
                                        <button
                                            type="submit"
                                            name="decision"
                                            value="approve"
                                            class="rounded-lg px-4 py-2 text-sm font-semibold"
                                            style="background-color: #15803d; color: #ffffff; border: 1px solid #166534;"
                                        >
                                            Approve and Move to Trash Bin
                                        </button>

                                        <button
                                            type="submit"
                                            name="decision"
                                            value="disapprove"
                                            class="rounded-lg px-4 py-2 text-sm font-semibold"
                                            style="background-color: #ffffff; color: #b91c1c; border: 1px solid #b91c1c;"
                                        >
                                            Disapprove
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </article>
                    @empty
                        <p class="p-8 text-center text-sm text-gray-500">
                            No deletion requests yet.
                        </p>
                    @endforelse
                </div>

                @if($requests->hasPages())
                    <div class="border-t border-gray-100 px-6 py-4">
                        {{ $requests->fragment('requests')->links() }}
                    </div>
                @endif
            </section>

            {{-- =====================================================
                TRASH BIN — SUPER ADMIN ONLY
            ====================================================== --}}
            @if($isSuperAdmin)
                <section
                    id="trash"
                    class="overflow-hidden rounded-2xl border
                           border-red-200 bg-white shadow-sm"
                >
                    <div class="px-6 py-4" style="background-color: #b91c1c; color: #ffffff;">
                        <h2 class="text-lg font-bold" style="color: #ffffff;">
                            Trash Bin
                        </h2>

                        <p class="mt-1 text-sm" style="color: #fee2e2;">
                            Records are retained. Restoration preserves
                            the employee's previous account status.
                        </p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-red-50 text-red-800">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Employee</th>
                                    <th scope="col" class="px-6 py-3">Role</th>
                                    <th scope="col" class="px-6 py-3">School</th>
                                    <th scope="col" class="px-6 py-3">Moved to Trash</th>
                                    <th scope="col" class="px-6 py-3">Action</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">
                                @forelse($trashed as $employee)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <p class="font-semibold text-gray-900">
                                                {{ $employee->name }}
                                            </p>

                                            <p class="mt-1 text-gray-500">
                                                {{ $employee->email }}
                                            </p>
                                        </td>

                                        {{-- USER ROLE --}}
                                        <td class="px-6 py-4">
                                            @php
                                                $roleLabel = match ($employee->role) {
                                                    'super_admin' => 'Super Admin',
                                                    'admin' => 'Admin',
                                                    'user' => 'User',
                                                    default => 'Unknown',
                                                };

                                                $roleStyle = match ($employee->role) {
                                                    'super_admin' => 'background-color: #f3e8ff; color: #7e22ce;',
                                                    'admin' => 'background-color: #dbeafe; color: #1d4ed8;',
                                                    'user' => 'background-color: #dcfce7; color: #166534;',
                                                    default => 'background-color: #f3f4f6; color: #4b5563;',
                                                };
                                            @endphp

                                            <span
                                                class="inline-flex whitespace-nowrap rounded-full
                                                    px-3 py-1 text-xs font-semibold"
                                                style="{{ $roleStyle }}"
                                            >
                                                {{ $roleLabel }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-gray-600">
                                            {{ $employee->employmentStatus?->school
                                            ? implode(' - ', array_filter([
                                                $employee->employmentStatus->school->school_name,
                                                $employee->employmentStatus->school->school_district,
                                            ]))
                                            : 'No assigned school' }}
                                        </td>

                                        <td class="px-6 py-4 text-gray-600">
                                            {{ $employee->deleted_at->copy()->timezone('Asia/Manila')->format('M d, Y h:i A') }}
                                        </td>

                                        <td class="px-6 py-4">
                                            <form
                                                method="POST"
                                                action="{{ route('danger-zone.employees.restore', $employee->id) }}"
                                                onsubmit="return confirm('Restore this employee from Trash Bin?');"
                                            >
                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="rounded-lg px-4 py-2 font-semibold"
                                                    style="background-color: #ffffff; color: #15803d; border: 1px solid #15803d;"
                                                >
                                                    Restore Employee
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td
                                            colspan="3"
                                            class="px-6 py-10 text-center text-gray-500"
                                        >
                                            Trash Bin is empty.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($trashed->hasPages())
                        <div class="border-t border-gray-100 px-6 py-4">
                            {{ $trashed->fragment('trash')->links() }}
                        </div>
                    @endif
                </section>
            @endif

        </div>
    </div>
</x-app-layout>