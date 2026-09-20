<x-app-layout>

    <div class="min-h-screen min-w-0 bg-gray-50 py-4 sm:py-8">

        <div class="mx-auto w-full min-w-0 max-w-7xl px-4 sm:px-6">


            {{-- BREADCRUMB TRAIL --}}
            <div class="mb-4">

                <nav
                    class="flex flex-wrap items-center gap-y-2 text-xs sm:text-sm"
                    aria-label="Breadcrumb"
                >

                    {{-- Home --}}
                    <a
                        href="{{ route('dashboard') }}"
                        class="flex items-center font-medium text-gray-500 transition hover:text-green-700"
                    >
                        <svg
                            class="mr-1.5 h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 12l9-9 9 9M5 10v10h14V10M9 20v-6h6v6"
                            />
                        </svg>

                        Dashboard
                    </a>

                    {{-- Separator --}}
                    <svg
                        class="mx-2 h-4 w-4 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7"
                        />
                    </svg>

                    {{-- Data Management --}}
                    <a
                        href="{{ route('data-management') }}"
                        class="font-medium text-gray-500 transition hover:text-green-700"
                    >
                        Data Management
                    </a>

                    {{-- Separator --}}
                    <svg
                        class="mx-2 h-4 w-4 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7"
                        />
                    </svg>

                    {{-- Current Page --}}
                    <span class="font-semibold text-green-800">
                        Employment Status
                    </span>

                </nav>

            </div>

            @if(session('error'))

                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 sm:p-5">

                    <p class="font-semibold text-red-800">
                        {{ session('error') }}
                    </p>

                </div>

            @endif


            @if(session('employment_import_result'))

                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 sm:p-5">

                    <h3 class="text-lg font-bold text-green-900">
                        Employment Import Completed
                    </h3>

                    <div class="mt-4 grid grid-cols-2 gap-4 lg:grid-cols-4">

                        <div>

                            <p class="text-sm text-gray-500">
                                New Records
                            </p>

                            <p class="text-2xl font-bold text-green-700">
                                {{ session('employment_import_result.imported') }}
                            </p>

                        </div>


                        <div>

                            <p class="text-sm text-gray-500">
                                Updated Records
                            </p>

                            <p class="text-2xl font-bold text-blue-700">
                                {{ session('employment_import_result.updated') }}
                            </p>

                        </div>


                        <div>

                            <p class="text-sm text-gray-500">
                                Skipped
                            </p>

                            <p class="text-2xl font-bold text-yellow-600">
                                {{ session('employment_import_result.skipped') }}
                            </p>

                        </div>


                        <div>

                            <p class="text-sm text-gray-500">
                                Errors
                            </p>

                            <p class="text-2xl font-bold text-red-600">
                                {{ count(session('employment_import_result.errors', [])) }}
                            </p>

                        </div>

                    </div>


                    {{-- ERROR DETAILS --}}

                    @if(count(session('employment_import_result.errors', [])) > 0)

                        <div class="mt-5 rounded-lg border border-red-200 bg-red-50 p-4">

                            <h4 class="font-semibold text-red-800">
                                Import Errors
                            </h4>

                            <ul class="mt-2 list-disc pl-5 text-sm text-red-700 break-words">

                                @foreach(session('employment_import_result.errors', []) as $error)

                                    <li>
                                        Row {{ $error['row'] }}:
                                        {{ $error['message'] }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif

                </div>

            @endif


            {{-- =====================================================
                SUPER ADMIN - IMPORT PERSONNEL
            ====================================================== --}}
            @if(auth()->user()->role === 'super_admin')
                <div class="rounded-xl border bg-white p-4 sm:p-6 shadow-sm">

                    <h2 class="text-lg font-bold text-gray-800">
                        Import Employment Status
                    </h2>

                    <p class="mt-1 text-sm text-gray-500 break-words">
                        Upload the official Employment Status Excel file.
                    </p>


                    <form
                        action="{{ route('data-management.employment-status.import') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="mt-6"
                    >

                        @csrf
                        
                                            <div class="flex flex-col gap-3 xl:flex-row xl:items-center">

                            {{-- EXCEL FILE LABEL --}}
                            <label
                                for="file"
                                class="shrink-0 text-sm font-semibold text-gray-700"
                            >
                                EXCEL FILE
                            </label>


                            {{-- CUSTOM FILE INPUT --}}
                            <div class="relative flex h-11 min-w-0 w-full shrink-0 xl:flex-1">

                                {{-- REAL FILE INPUT --}}
                                <input
                                    type="file"
                                    id="file"
                                    name="file"
                                    accept=".xlsx,.xls"
                                    required
                                    class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0"
                                    onchange="document.getElementById('file-name').textContent =
                                        this.files.length ? this.files[0].name : 'No file selected'"
                                >


                                {{-- CUSTOM FILE DISPLAY --}}
                                <div
                                    class="flex h-full w-full items-center overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm"
                                >

                                    {{-- BROWSE BUTTON --}}
                                    <span
                                        class="flex h-full shrink-0 items-center border-r border-green-200 bg-green-50 px-4 text-sm font-semibold text-green-700"
                                    >
                                        Browse...
                                    </span>


                                    {{-- FILE NAME --}}
                                    <span
                                        id="file-name"
                                        class="truncate px-4 text-sm text-gray-500"
                                    >
                                        No file selected
                                    </span>

                                </div>

                            </div>


                            {{-- ACTION BUTTONS --}}
                            <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:items-center">

                                {{-- UPLOAD BUTTON --}}
                                <button
                                    type="submit"
                                    class="flex min-h-11 w-full items-center justify-center gap-2 sm:w-auto rounded-lg bg-green-700 px-5 text-sm font-semibold text-white shadow-sm transition duration-200 hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                    >

                                    {{-- UPLOAD ICON --}}
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 3v10m0-10L8 7m4-4l4 4"
                                        />

                                    </svg>

                                    Upload & Preview

                                </button>

                                {{-- DOWNLOAD TEMPLATE --}}
                                <a
                                    href="{{ route('data-management.employment-status.download-template') }}"
                                    class="flex min-h-11 w-full items-center justify-center gap-2 sm:w-auto rounded-lg border border-green-700 bg-white px-4 text-sm font-semibold text-green-700 shadow-sm transition duration-200 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                    >

                                    {{-- DOWNLOAD ICON --}}
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 3v12m0 0l-4-4m4 4l4-4"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M5 21h14"
                                        />
                                    </svg>

                                    Download Template

                                </a>

                            </div>

                        </div>

                        {{-- HELP TEXT --}}
                        <p class="mt-1.5 text-xs text-gray-500">

                            Accepted formats:
                            <span class="font-medium">.xlsx</span>
                            and
                            <span class="font-medium">.xls</span>.
                            Maximum file size:
                            <span class="font-medium">10 MB</span>.

                        </p>

                    </form>

                </div>
            {{-- =====================================================
                ADMIN - MANUAL PERSONNEL ENTRY
            ====================================================== --}}
            @elseif(auth()->user()->role === 'admin')
            
                <div
                    class="rounded-2xl border border-green-200 bg-white p-4 sm:p-6 shadow-sm"
                    >

                    <div
                        class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between"
                    >

                        <div class="flex items-start gap-4">

                            <div>

                                <h2 class="text-lg font-bold text-gray-900">
                                    Add Personnel Information
                                </h2>

                                <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">
                                    Excel import is available only to the Super Admin.
                                    To add a personnel record, complete the personnel information form.
                                </p>

                            </div>

                        </div>


                        <a
                            href="https://forms.gle/zrz8AGM3bdvAWoJ67"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex min-h-11 w-full shrink-0 md:w-auto items-center justify-center gap-2 rounded-lg bg-green-700 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 4.5v15m7.5-7.5h-15"
                                />
                            </svg>

                            Add Personnel

                        </a>

                    </div>

                </div>

            @endif

            <br>

            {{-- RECORDS TABLE --}}

            {{-- Pass school-scoped $employmentStatuses to admins; super admins may receive all records. --}}
                @php
                    $user = auth()->user();
                    $isSuperAdmin = $user && (method_exists($user, 'hasRole') ? $user->hasRole('super_admin') : $user->role === 'super_admin');
                    $isAdmin = $user && (method_exists($user, 'hasRole') ? $user->hasRole('admin') : $user->role === 'admin');
                    $search = request('search', '');
                    $editRouteName = $editRouteName ?? 'data-management.employment-status.edit';
                    $adminSchool = $school
                        ?? data_get($user, 'school')
                        ?? collect($employmentStatuses->items())->first()?->school;
                    $schoolName = $schoolName ?? data_get($adminSchool, 'school_name') ?? 'School not assigned';
                    $districtName = $districtName
                        ?? data_get($adminSchool, 'school_district')
                        ?? data_get($adminSchool, 'district.district_name')
                        ?? 'District not assigned';
                @endphp

                @if($isSuperAdmin)
                {{-- RECORDS TABLE --}}
                            <div class="min-w-0 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">


                                {{-- TABLE HEADER --}}
                                <div class="flex items-center justify-between border-b border-green-800 bg-green-800 p-4 text-white sm:px-2 sm:py-4" style="background-color: #166534;">

                                    <div>

                                        <h2 class="text-xl font-semibold text-white sm:text-2xl">
                                            Employment Status Records
                                        </h2>

                                        <p class="mt-2 text-sm text-green-100 sm:text-base">
                                            List of personnel employment status records
                                            maintained in the system. 
                                        </p>

                                    </div>
                                </div>


                                {{-- SEARCH --}}
                                <div class="border-b border-gray-200 p-4 sm:p-6">

                                    <form
                                        action="{{ route('data-management.employment-status') }}"
                                        method="GET"
                                    >

                                        <div class="flex flex-col gap-3 md:flex-row">

                                            {{-- SEARCH INPUT --}}
                                            <div class="min-w-0 flex-1">

                                                <label
                                                    for="search"
                                                    class="mb-2 block text-sm font-medium text-gray-700"
                                                >
                                                    Search Employment Records
                                                </label>

                                                <input
                                                    type="text"
                                                    id="search"
                                                    name="search"
                                                    value="{{ $search }}"
                                                    placeholder="Search name, school, item no., position, status..."
                                                    class="min-h-11 w-full min-w-0 rounded-md border-gray-300 text-sm shadow-sm focus:border-green-600 focus:ring-green-600"
                                                >

                                            </div>


                                            {{-- BUTTONS --}}
                                            <div class="flex flex-wrap items-end gap-2 [&>*]:min-h-11 [&>*]:flex-1 [&>*]:text-center md:[&>*]:flex-none">

                                                {{-- SEARCH --}}
                                                <button
                                                    type="submit"
                                                    class="rounded-md bg-green-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-green-800"
                                                >
                                                    Search
                                                </button>


                                                {{-- CLEAR --}}
                                                @if($search !== '')

                                                    <a
                                                        href="{{ route('data-management.employment-status') }}"
                                                        class="rounded-md border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                                                    >
                                                        Clear
                                                    </a>

                                                @endif

                                            </div>

                                        </div>

                                    </form>

                                </div>


                                {{-- TABLE --}}
                                <p class="border-b border-gray-100 px-4 py-2 text-xs text-gray-500 lg:hidden">
                                    Swipe left or right to view all columns and the Update button.
                                </p>
                                <div class="w-full min-w-0 max-w-full overflow-x-auto overscroll-x-contain" tabindex="0" role="region" aria-label="Employment status records, horizontally scrollable">

                                    <table class="min-w-full divide-y divide-gray-200">

                                        <thead class="bg-white">

                                            <tr>

                                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                    #
                                                </th>

                                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                    Name
                                                </th>

                                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                    School Name
                                                </th>

                                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                    Item From School Level
                                                </th>

                                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                    Plantilla Item No.
                                                </th>

                                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                    Position Title
                                                </th>

                                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                    Employment Status
                                                </th>

                                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                    Date of Original Appointment
                                                </th>

                                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                    Date of Last Promotion
                                                </th>

                                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                    Warm Body Status
                                                </th>

                                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                    Nature of Work
                                                </th>

                                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                    Action
                                                </th>

                                            </tr>

                                        </thead>


                                        <tbody class="divide-y divide-gray-200 bg-white">

                                            @forelse($employmentStatuses as $record)

                                                @php

                                                    $basic = $record->user?->basicInformation;

                                                    $plantilla = $record->plantilla;

                                                    $school = $record->school;

                                                    $name = trim(
                                                        ($basic?->first_name ?? '') . ' ' .
                                                        ($basic?->middle_name ?? '') . ' ' .
                                                        ($basic?->last_name ?? '') . ' ' .
                                                        ($basic?->extension_name ?? '')
                                                    );

                                                @endphp


                                                <tr class="hover:bg-gray-50">


                                                    {{-- # --}}
                                                    <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">

                                                        {{ $employmentStatuses->firstItem() + $loop->index }}

                                                    </td>


                                                    {{-- NAME --}}
                                                    <td class="min-w-[220px] px-4 py-4">

                                                        <div class="text-sm font-semibold text-gray-900">

                                                            {{ $name ?: '—' }}

                                                        </div>

                                                        <div class="mt-1 text-sm text-gray-500 break-words">

                                                            {{ $record->user?->email ?? '—' }}

                                                        </div>

                                                    </td>


                                                    {{-- SCHOOL NAME --}}
                                                    <td class="min-w-[220px] px-4 py-4 text-sm text-gray-700">

                                                        {{ $school?->school_name ?? '—' }} <br> {{ $school?->school_district ?? '—' }}

                                                    </td>


                                                    {{-- ITEM FROM SCHOOL LEVEL --}}
                                                    <td class="min-w-[180px] px-4 py-4 text-sm text-gray-700">

                                                        {{ $plantilla?->item_from_school_level ?? '—' }}

                                                    </td>


                                                    {{-- PLANTILLA ITEM NUMBER --}}
                                                    <td class="min-w-[180px] px-4 py-4 text-sm text-gray-700">

                                                        {{ $plantilla?->item_number ?? '—' }}

                                                    </td>


                                                    {{-- POSITION TITLE --}}
                                                    <td class="min-w-[180px] px-4 py-4 text-sm font-medium text-gray-900">

                                                        {{ $plantilla?->position_title ?? '—' }}

                                                    </td>


                                                    {{-- EMPLOYMENT STATUS --}}
                                                    <td class="whitespace-nowrap px-4 py-4">

                                                        @if($record->employment_status)

                                                            <span
                                                                class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800"
                                                            >

                                                                {{ $record->employment_status }}

                                                            </span>

                                                        @else

                                                            <span class="text-sm text-gray-400">
                                                                —
                                                            </span>

                                                        @endif

                                                    </td>


                                                    {{-- DATE OF ORIGINAL APPOINTMENT --}}
                                                    <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-700">

                                                        {{ $record->date_of_original_appointment
                                                            ? $record->date_of_original_appointment->format('M d, Y')
                                                            : '—'
                                                        }}

                                                    </td>


                                                    {{-- DATE OF LAST PROMOTION --}}
                                                    <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-700">

                                                        {{ $record->date_of_last_promotion
                                                            ? $record->date_of_last_promotion->format('M d, Y')
                                                            : '—'
                                                        }}

                                                    </td>


                                                    {{-- WARM BODY STATUS --}}
                                                    <td class="whitespace-nowrap px-4 py-4">

                                                        @if($record->warm_body_status)

                                                            <span
                                                                class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                                                    {{ $record->warm_body_status === 'Detailed'
                                                                        ? 'bg-red-100 text-red-700'
                                                                        : 'bg-blue-100 text-blue-800'
                                                                    }}"
                                                            >
                                                                {{ $record->warm_body_status }}
                                                            </span>

                                                        @else

                                                            <span class="text-sm text-gray-400">
                                                                —
                                                            </span>

                                                        @endif

                                                    </td>


                                                    {{-- NATURE OF WORK --}}
                                                    <td class="min-w-[160px] px-4 py-4 text-sm text-gray-700">

                                                        {{ $record->nature_of_work ?? '—' }}

                                                    </td>


                                                    {{-- ACTION --}}
                                                    <td class="whitespace-nowrap px-4 py-4 text-right">
                                                        <a
                                                            href="{{ route(
                                                                'data-management.employment-status.edit',
                                                                $record->id
                                                            ) }}"
                                                            class="inline-flex min-h-11 items-center rounded-md bg-green-700 px-3 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                                        >
                                                            Update
                                                        </a>

                                                    </td>

                                                </tr>


                                            @empty

                                                <tr>

                                                    <td
                                                        colspan="12"
                                                        class="px-4 sm:px-6 py-12 text-center"
                                                    >

                                                        <div class="text-sm font-medium text-gray-700">

                                                            No employment records found.

                                                        </div>

                                                        @if($search !== '')

                                                            <div class="mt-1 text-sm text-gray-500 break-words">

                                                                No records matched your search for
                                                                <span class="font-semibold">
                                                                    "{{ $search }}"
                                                                </span>.

                                                            </div>

                                                        @endif

                                                    </td>

                                                </tr>

                                            @endforelse

                                        </tbody>

                                    </table>

                                </div>

                                {{-- PAGINATION --}}
                                    <div class="border-t border-gray-200 px-4 sm:px-6 py-4">

                                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                                            <div class="text-sm text-gray-500">

                                                @if($employmentStatuses->total() > 0)

                                                    Showing
                                                    <span class="font-semibold text-gray-700">
                                                        {{ $employmentStatuses->firstItem() }}
                                                    </span>

                                                    to

                                                    <span class="font-semibold text-gray-700">
                                                        {{ $employmentStatuses->lastItem() }}
                                                    </span>

                                                    of

                                                    <span class="font-semibold text-gray-700">
                                                        {{ $employmentStatuses->total() }}
                                                    </span>

                                                    records

                                                @else

                                                    Showing 0 records

                                                @endif

                                            </div>


                                            <div>

                                                {{ $employmentStatuses->links() }}

                                            </div>

                                        </div>

                                    </div>

                            </div>
                @elseif($isAdmin)
                <div class="min-w-0 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-green-800 bg-green-800 p-4 text-white sm:px-2 sm:py-4" style="background-color: #166534;">
                        <h3 class="text-xl font-semibold text-white sm:text-xl">
                            Employment Status Records ({{ $schoolName }} - {{ $districtName }})
                        </h3>
                        <p class="mt-2 text-sm text-green-100 sm:text-base">
                            List of personnel employment status records and related information.
                        </p>
                    </div>

                    <div class="border-b border-gray-200 p-4 sm:p-6">
                        <form action="{{ url()->current() }}" method="GET">
                            <label for="admin-employment-search" class="mb-2 block text-sm font-medium text-gray-700">Search employment records</label>
                            <div class="flex flex-col gap-3 sm:flex-row">
                                <input
                                    type="search"
                                    id="admin-employment-search"
                                    name="search"
                                    value="{{ $search }}"
                                    placeholder="Search name, email, item no., position, or nature of work"
                                    class="min-h-11 min-w-0 flex-1 rounded-md border-gray-300 text-sm shadow-sm focus:border-green-600 focus:ring-green-600"
                                >
                                <div class="flex gap-2">
                                    <button type="submit" class="min-h-11 rounded-md bg-green-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-800">Search</button>
                                    @if($search !== '')
                                        <a href="{{ url()->current() }}" class="inline-flex min-h-11 items-center rounded-md border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Clear</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>

                    <p class="border-b border-gray-100 px-4 py-2 text-xs text-gray-500 lg:hidden">Swipe left or right to view all columns and the Update button.</p>
                    <div class="w-full min-w-0 max-w-full overflow-x-auto overscroll-x-contain" tabindex="0" role="region" aria-label="Admin employment status records, horizontally scrollable">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-white">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">#</th>
                                    <th scope="col" class="min-w-[220px] px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Name &amp; Email Address</th>
                                    <th scope="col" class="min-w-[160px] px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Plantilla Item No.</th>
                                    <th scope="col" class="min-w-[180px] px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Position Title</th>
                                    <th scope="col" class="min-w-[160px] px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Nature of Work</th>
                                    <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-700">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($employmentStatuses as $record)
                                    @php
                                        $basic = $record->user?->basicInformation;
                                        $name = trim(implode(' ', array_filter([
                                            $basic?->first_name,
                                            $basic?->middle_name,
                                            $basic?->last_name,
                                            $basic?->extension_name,
                                        ])));
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">{{ $employmentStatuses->firstItem() + $loop->index }}</td>
                                        <td class="min-w-[220px] px-4 py-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $name ?: '—' }}</div>
                                            <div class="mt-1 break-words text-sm text-gray-500">{{ $record->user?->email ?? '—' }}</div>
                                        </td>
                                        <td class="min-w-[160px] px-4 py-4 text-sm text-gray-700">{{ $record->plantilla?->item_number ?? '—' }}</td>
                                        <td class="min-w-[180px] px-4 py-4 text-sm font-medium text-gray-900">{{ $record->plantilla?->position_title ?? '—' }}</td>
                                        <td class="min-w-[160px] px-4 py-4 text-sm text-gray-700">{{ $record->nature_of_work ?? '—' }}</td>
                                        <td class="whitespace-nowrap px-4 py-4 text-right">
                                            <a href="{{ route($editRouteName, $record->id) }}" class="inline-flex min-h-11 items-center rounded-md bg-green-700 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">Update</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-12 text-center text-sm text-gray-600">
                                            @if($search !== '')
                                                No records matched your search for "{{ $search }}".
                                            @else
                                                No employment records found.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="border-t border-gray-200 px-4 py-4 sm:px-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-sm text-gray-500">
                                @if($employmentStatuses->total() > 0)
                                    Showing {{ $employmentStatuses->firstItem() }} to {{ $employmentStatuses->lastItem() }} of {{ $employmentStatuses->total() }} records
                                @else
                                    Showing 0 records
                                @endif
                            </p>
                            {{ $employmentStatuses->withQueryString()->links() }}
                        </div>
                    </div>
                </div>

                @else
                    @php(abort(403))
                @endif


        </div>

    </div>

</x-app-layout>