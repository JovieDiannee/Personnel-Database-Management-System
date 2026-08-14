<x-app-layout>

    <div class="min-h-screen bg-gray-50 py-8">

        <div class="mx-auto max-w-7xl px-6">


            {{-- PAGE HEADER --}}
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-950 via-green-900 to-green-800 py-6 text-white shadow-lg">

                <div class="relative flex items-center justify-between px-6 py-5">

                    <div>

                        <h1 class="text-2xl font-bold tracking-tight text-white">
                            Medical Allowance Records
                        </h1>

                        <p class="mt-1 text-sm text-green-100">
                            Manage and monitor official personnel records
                            related to medical allowance benefits.
                        </p>

                    </div>

                    <a
                        href="{{ route('data-management') }}"
                        class="rounded-md border border-white/30
                            bg-white/10 px-4 py-2
                            text-sm font-semibold text-white
                            backdrop-blur-sm
                            transition duration-200
                            hover:bg-white hover:text-green-800"
                    >
                        ← Back to Data Management
                    </a>

                </div>

            </div>
           
            {{-- TAB NAVIGATION --}}
            <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                <div class="flex w-full">

                    {{-- TAB 1: RECORDS --}}
                    <a
                        href="{{ route('data-management.medical-allowance') }}"
                        class="flex flex-1 items-center justify-center gap-2
                            border-b-2 border-green-700
                            bg-green-50 px-5 py-4
                            text-center text-sm font-semibold
                            text-green-800
                            transition duration-200
                            hover:bg-green-100"
                    >

                        {{-- ICON --}}
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 20h5v-2a4 4 0 00-4-4h-1
                                M9 20H4v-2a4 4 0 014-4h1
                                M12 12a4 4 0 100-8
                                4 4 0 000 8z"
                            />
                        </svg>

                        <div>
                            <span class="block">
                                Personnel Records
                            </span>

                            <span class="mt-0.5 block text-xs font-normal text-gray-500">
                                Individual records
                            </span>
                        </div>

                    </a>


                    {{-- TAB 2: REPORT --}}
                    <a
                        href="{{ route('data-management.medical-allowance.report') }}"
                        class="flex flex-1 items-center justify-center gap-2
                            border-b-2 border-transparent
                            bg-white px-5 py-4
                            text-center text-sm font-semibold
                            text-gray-700
                            transition duration-200
                            hover:bg-green-50
                            hover:text-green-800"
                    >

                        {{-- ICON --}}
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 17v-2a4 4 0 014-4h4
                                a4 4 0 014 4v2
                                M9 17H5a2 2 0 01-2-2V7
                                a2 2 0 012-2h10
                                a2 2 0 012 2v2
                                M7 9h6
                                M7 13h2"
                            />
                        </svg>

                        <div>
                            <span class="block">
                                Medical Allowance Report
                            </span>

                            <span class="mt-0.5 block text-xs font-normal text-gray-500">
                                School-level summary
                            </span>
                        </div>

                    </a>
                </div>

            </div>

            
            {{-- GENERAL ERROR --}}
            @if(session('error'))

                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-5">

                    <p class="font-semibold text-red-800">
                        {{ session('error') }}
                    </p>

                </div>

            @endif


            {{-- MEDICAL ALLOWANCE IMPORT RESULT --}}
            @if(session('medical_allowance_import_result'))

                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-5">

                    <h3 class="text-lg font-bold text-green-900">
                        Medical Allowance Import Completed
                    </h3>

                    <div class="mt-4 grid gap-4 md:grid-cols-4">

                        {{-- NEW RECORDS --}}
                        <div>

                            <p class="text-sm text-gray-500">
                                New Records
                            </p>

                            <p class="text-2xl font-bold text-green-700">
                                {{ session('medical_allowance_import_result.imported') }}
                            </p>

                        </div>


                        {{-- UPDATED RECORDS --}}
                        <div>

                            <p class="text-sm text-gray-500">
                                Updated Records
                            </p>

                            <p class="text-2xl font-bold text-blue-700">
                                {{ session('medical_allowance_import_result.updated') }}
                            </p>

                        </div>


                        {{-- SKIPPED --}}
                        <div>

                            <p class="text-sm text-gray-500">
                                Skipped
                            </p>

                            <p class="text-2xl font-bold text-yellow-600">
                                {{ session('medical_allowance_import_result.skipped') }}
                            </p>

                        </div>


                        {{-- ERRORS --}}
                        <div>

                            <p class="text-sm text-gray-500">
                                Errors
                            </p>

                            <p class="text-2xl font-bold text-red-600">
                                {{ count(session('medical_allowance_import_result.errors', [])) }}
                            </p>

                        </div>

                    </div>


                    {{-- ERROR DETAILS --}}
                    @if(count(session('medical_allowance_import_result.errors', [])) > 0)

                        <div class="mt-5 rounded-lg border border-red-200 bg-red-50 p-4">

                            <h4 class="font-semibold text-red-800">
                                Import Errors
                            </h4>

                            <ul class="mt-2 list-disc pl-5 text-sm text-red-700">

                                @foreach(session('medical_allowance_import_result.errors', []) as $error)

                                    <li>

                                        Row {{ $error['row'] ?? 'N/A' }}:
                                        {{ $error['message'] ?? 'Unknown error' }}

                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- IMPORT SECTION --}}
            {{-- ========================================================= --}}

            <div id="import" class="mb-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                <div class="mb-5">

                    <h2 class="text-lg font-semibold text-gray-800">
                        Import Medical Allowance Records
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Upload an Excel file containing personnel medical
                        allowance records.
                    </p>

                </div>


                <form
                    action="{{ route('data-management.medical-allowance.import') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    >

                    @csrf

                    <div class="flex flex-col gap-3 md:flex-row md:items-center">

                        {{-- EXCEL FILE LABEL --}}
                        <label
                            for="file"
                            class="shrink-0 text-sm font-semibold text-gray-700"
                        >
                            EXCEL FILE
                        </label>


                        {{-- CUSTOM FILE INPUT --}}
                        <div class="relative flex h-10 flex-1">

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
                                class="flex h-full w-full items-center overflow-hidden
                                    rounded-lg border border-gray-300
                                    bg-white shadow-sm"
                            >

                                {{-- BROWSE BUTTON --}}
                                <span
                                    class="flex h-full shrink-0 items-center
                                        border-r border-green-200
                                        bg-green-50
                                        px-4
                                        text-sm font-semibold
                                        text-green-700"
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
                        <div class="flex shrink-0 items-center gap-2">

                            {{-- UPLOAD BUTTON --}}
                            <button
                                type="submit"
                                class="flex h-10 items-center justify-center gap-2
                                    rounded-lg
                                    bg-green-700
                                    px-5
                                    text-sm
                                    font-semibold
                                    text-white
                                    shadow-sm
                                    transition
                                    duration-200
                                    hover:bg-green-800
                                    focus:outline-none
                                    focus:ring-2
                                    focus:ring-green-500
                                    focus:ring-offset-2"
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
                                href="{{ route('data-management.medical-allowance.template') }}"
                                class="flex h-10 items-center justify-center gap-2
                                    rounded-lg
                                    border border-green-700
                                    bg-white
                                    px-4
                                    text-sm
                                    font-semibold
                                    text-green-700
                                    shadow-sm
                                    transition
                                    duration-200
                                    hover:bg-green-50
                                    focus:outline-none
                                    focus:ring-2
                                    focus:ring-green-500
                                    focus:ring-offset-2"
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


            {{-- ========================================================= --}}
            {{-- MEDICAL ALLOWANCE RECORDS --}}
            {{-- ========================================================= --}}

            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

                {{-- HEADER --}}
                <div class="flex flex-col gap-4 border-b border-gray-200 p-6 md:flex-row md:items-center md:justify-between bg-green-400">

                    <div>

                        <h2 class="text-lg font-semibold text-gray-800">
                            Medical Allowance Records
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            List of personnel medical allowance records and
                            related employment information.
                        </p>

                    </div>

                </div>


                {{-- SEARCH --}}
                <div class="border-b border-gray-200 p-6">

                    <form
                        action="{{ route('data-management.medical-allowance') }}"
                        method="GET"
                    >

                        <div class="flex flex-col gap-3 md:flex-row">

                            <div class="flex-1">

                                <label
                                    for="search"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Search Personnel
                                </label>

                                <input
                                    type="text"
                                    id="search"
                                    name="search"
                                    value="{{ $search }}"
                                    placeholder="Search by name, email, school, or status..."
                                    class="w-full rounded-md border-gray-300
                                        text-sm shadow-sm
                                        focus:border-green-600
                                        focus:ring-green-600"
                                >

                            </div>


                            <div class="flex items-end gap-2">

                                <button
                                    type="submit"
                                    class="rounded-md bg-green-700
                                        px-5 py-2.5
                                        text-sm font-semibold text-white
                                        hover:bg-green-800"
                                >
                                    Search
                                </button>


                                @if($search !== '')

                                    <a
                                        href="{{ route('data-management.medical-allowance') }}"
                                        class="rounded-md border border-gray-300
                                            bg-white px-5 py-2.5
                                            text-sm font-semibold text-gray-700
                                            hover:bg-gray-50"
                                    >
                                        Clear
                                    </a>

                                @endif

                            </div>

                        </div>

                    </form>

                </div>


                {{-- TABLE --}}
                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-green-50">

                            <tr>

                                {{-- NAME --}}
                                <th class="min-w-[220px] px-4 py-3 text-left text-xs
                                        font-semibold uppercase tracking-wider text-gray-600">
                                    Name and Email
                                </th>


                                {{-- SCHOOL --}}
                                <th class="min-w-[220px] px-4 py-3 text-left text-xs
                                        font-semibold uppercase tracking-wider text-gray-600">
                                    School Name
                                </th>


                                {{-- DISTRICT --}}
                                <th class="min-w-[140px] px-4 py-3 text-left text-xs
                                        font-semibold uppercase tracking-wider text-gray-600">
                                    District
                                </th>


                                {{-- SCHOOL LEVEL --}}
                                <th class="min-w-[180px] px-4 py-3 text-left text-xs
                                        font-semibold uppercase tracking-wider text-gray-600">
                                    Item From School Level
                                </th>


                                {{-- ITEM NUMBER --}}
                                <th class="min-w-[180px] px-4 py-3 text-left text-xs
                                        font-semibold uppercase tracking-wider text-gray-600">
                                    Plantilla Item No.
                                </th>


                                {{-- POSITION --}}
                                <th class="min-w-[200px] px-4 py-3 text-left text-xs
                                        font-semibold uppercase tracking-wider text-gray-600">
                                    Position Title
                                </th>


                                {{-- EMPLOYMENT STATUS --}}
                                <th class="min-w-[160px] px-4 py-3 text-left text-xs
                                        font-semibold uppercase tracking-wider text-gray-600">
                                    Employment Status
                                </th>


                                {{-- ORIGINAL APPOINTMENT --}}
                                <th class="min-w-[190px] px-4 py-3 text-left text-xs
                                        font-semibold uppercase tracking-wider text-gray-600">
                                    Date of Original Appointment
                                </th>


                                {{-- GROUP AVAILMENT --}}
                                <th class="min-w-[200px] px-4 py-3 text-left text-xs
                                        font-semibold uppercase tracking-wider text-gray-600">
                                    Group Availment
                                </th>


                                {{-- DISBURSEMENT --}}
                                <th class="min-w-[180px] px-4 py-3 text-left text-xs
                                        font-semibold uppercase tracking-wider text-gray-600">
                                    Disbursement Status
                                </th>


                                {{-- ACTION --}}
                                <th class="min-w-[100px] px-4 py-3 text-right text-xs
                                        font-semibold uppercase tracking-wider text-gray-600">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-200 bg-white">

                            @forelse($medicalAllowances as $record)

                                @php

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Personnel
                                    |--------------------------------------------------------------------------
                                    */

                                    $basic = $record->user?->basicInformation;


                                    $name = trim(
                                        ($basic?->first_name ?? '') . ' ' .
                                        ($basic?->middle_name ?? '') . ' ' .
                                        ($basic?->last_name ?? '') . ' ' .
                                        ($basic?->extension_name ?? '')
                                    );


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Employment
                                    |--------------------------------------------------------------------------
                                    */

                                    $employment = $record->user?->employmentStatus;


                                    /*
                        |--------------------------------------------------------------------------
                        | Plantilla
                        |--------------------------------------------------------------------------
                        */

                        $plantilla = $employment?->plantilla;


                        /*
                        |--------------------------------------------------------------------------
                        | School
                        |--------------------------------------------------------------------------
                        */

                        $school = $employment?->school;

                        @endphp


                                <tr class="hover:bg-gray-50">


                                    {{-- NAME + EMAIL --}}
                                    <td class="min-w-[220px] px-4 py-4">

                                        <div class="text-sm font-semibold text-gray-900">

                                            {{ $name ?: '—' }}

                                        </div>


                                        <div class="mt-1 break-words text-sm text-gray-500">

                                            {{ $record->user?->email ?? '—' }}

                                        </div>

                                    </td>


                                    {{-- SCHOOL NAME --}}
                                    <td class="min-w-[220px] px-4 py-4 text-sm text-gray-700">

                                        {{ $school?->school_name ?? '—' }}

                                    </td>


                                    {{-- DISTRICT --}}
                                    <td class="px-4 py-4 text-sm text-gray-700">

                                        {{ $school?->district ?? '—' }}

                                    </td>


                                    {{-- ITEM FROM SCHOOL LEVEL --}}
                                    <td class="px-4 py-4 text-sm text-gray-700">

                                        {{ $plantilla?->item_from_school_level ?? '—' }}

                                    </td>


                                    {{-- PLANTILLA ITEM NO. --}}
                                    <td class="px-4 py-4 text-sm text-gray-700">

                                        {{ $plantilla?->item_number ?? '—' }}

                                    </td>


                                    {{-- POSITION TITLE --}}
                                    <td class="px-4 py-4 text-sm font-medium text-gray-900">

                                        {{ $plantilla?->position_title ?? '—' }}

                                    </td>


                                    {{-- EMPLOYMENT STATUS --}}
                                    <td class="px-4 py-4">

                                        @if($employment?->employment_status)

                                            <span
                                                class="inline-flex rounded-full
                                                    bg-green-100 px-3 py-1
                                                    text-xs font-semibold
                                                    text-green-700"
                                            >

                                                {{ $employment->employment_status }}

                                            </span>

                                        @else

                                            <span class="text-sm text-gray-400">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- DATE OF ORIGINAL APPOINTMENT --}}
                                    <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-700">

                                        {{ $employment?->date_of_original_appointment
                                            ? \Carbon\Carbon::parse(
                                                $employment->date_of_original_appointment
                                            )->format('M d, Y')
                                            : '—'
                                        }}

                                    </td>


                                    {{-- GROUP AVAILMENT --}}
                                    <td class="min-w-[200px] px-4 py-4 text-sm text-gray-700">

                                        {{ $record->mode_of_availment ?? '—' }}

                                    </td>


                                    {{-- DISBURSEMENT STATUS --}}
                                    <td class="px-4 py-4">

                                        @if($record->disbursement_status)

                                            @php

                                                $status = strtolower(
                                                    trim($record->disbursement_status)
                                                );

                                            @endphp


                                            @if($status === 'paid')

                                                <span
                                                    class="inline-flex rounded-full
                                                        bg-green-100 px-3 py-1
                                                        text-xs font-semibold
                                                        text-green-700"
                                                >
                                                    {{ $record->disbursement_status }}
                                                </span>

                                            @elseif($status === 'pending')

                                                <span
                                                    class="inline-flex rounded-full
                                                        bg-yellow-100 px-3 py-1
                                                        text-xs font-semibold
                                                        text-yellow-700"
                                                >
                                                    {{ $record->disbursement_status }}
                                                </span>

                                            @else

                                                <span
                                                    class="inline-flex rounded-full
                                                        bg-gray-100 px-3 py-1
                                                        text-xs font-semibold
                                                        text-gray-700"
                                                >
                                                    {{ $record->disbursement_status }}
                                                </span>

                                            @endif

                                        @else

                                            <span class="text-sm text-gray-400">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ACTION --}}
                                    <td class="whitespace-nowrap px-4 py-4 text-right">

                                        <a
                                            href="#"
                                            class="inline-flex items-center
                                                rounded-md border
                                                border-green-700
                                                px-3 py-2
                                                text-sm font-semibold
                                                text-green-700
                                                hover:bg-green-50"
                                        >
                                            View
                                        </a>

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td
                                        colspan="11"
                                        class="px-6 py-12 text-center"
                                    >

                                        <div class="text-sm font-medium text-gray-700">
                                            No medical allowance records found.
                                        </div>

                                        @if($search !== '')

                                            <p class="mt-1 text-sm text-gray-500">

                                                No records matched your search for
                                                <span class="font-semibold">
                                                    "{{ $search }}"
                                                </span>.

                                            </p>

                                        @endif

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- PAGINATION --}}
                @if($medicalAllowances->hasPages())

                    <div class="border-t border-gray-200 px-6 py-4">

                        {{ $medicalAllowances->links() }}

                    </div>

                @endif


                {{-- RECORD COUNT --}}
                <div class="border-t border-gray-200 px-6 py-4">

                    <p class="text-sm text-gray-500">

                        Showing
                        <span class="font-medium text-gray-700">
                            {{ $medicalAllowances->firstItem() ?? 0 }}
                        </span>

                        to
                        <span class="font-medium text-gray-700">
                            {{ $medicalAllowances->lastItem() ?? 0 }}
                        </span>

                        of
                        <span class="font-medium text-gray-700">
                            {{ $medicalAllowances->total() }}
                        </span>

                        medical allowance records.

                    </p>

                </div>


            </div>
        </div>
    </div>

</x-app-layout>