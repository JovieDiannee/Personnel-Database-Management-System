<x-app-layout>

    <div class="min-h-screen bg-gray-50 py-8">

        <div class="mx-auto max-w-7xl px-6">


            {{-- BREADCRUMB TRAIL --}}
            <div class="mb-4">

                <nav
                    class="flex items-center text-sm"
                    aria-label="Breadcrumb"
                >

                    {{-- Home --}}
                    <a
                        href="{{ route('dashboard') }}"
                        class="flex items-center font-medium text-gray-500
                            transition hover:text-green-700"
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
                        class="font-medium text-gray-500
                            transition hover:text-green-700"
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

                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-5">

                    <p class="font-semibold text-red-800">
                        {{ session('error') }}
                    </p>

                </div>

            @endif


            @if(session('employment_import_result'))

                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-5">

                    <h3 class="text-lg font-bold text-green-900">
                        Employment Import Completed
                    </h3>

                    <div class="mt-4 grid gap-4 md:grid-cols-4">

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

                            <ul class="mt-2 list-disc pl-5 text-sm text-red-700">

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
                <div class="rounded-xl border bg-white p-6 shadow-sm">

                    <h2 class="text-lg font-bold text-gray-800">
                        Import Employment Status
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Upload the official Employment Status Excel file.
                    </p>


                    <form
                        action="{{ route('data-management.employment-status.import') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="mt-6"
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
                                    href="{{ route('data-management.employment-status.download-template') }}"
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
            {{-- =====================================================
                ADMIN - MANUAL PERSONNEL ENTRY
            ====================================================== --}}
            @elseif(auth()->user()->role === 'admin')
            
                <div
                    class="rounded-2xl border border-green-200
                        bg-white p-6 shadow-sm"
                    >

                    <div
                        class="flex flex-col gap-5
                            md:flex-row md:items-center
                            md:justify-between"
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
                            class="inline-flex h-10 shrink-0
                                items-center justify-center gap-2
                                rounded-lg bg-green-700
                                px-5 text-sm font-semibold
                                text-white shadow-sm
                                transition hover:bg-green-800
                                focus:outline-none
                                focus:ring-2 focus:ring-green-500
                                focus:ring-offset-2"
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
            <div class="overflow-hidden rounded-xl
                        border border-gray-200 bg-white shadow-sm">


                {{-- TABLE HEADER --}}
                <div class="flex items-center justify-between
                            border-b border-gray-200 p-6">

                    <div>

                        <h2 class="text-lg font-semibold text-gray-800">
                            Employment Status Records
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            List of personnel employment status records
                            maintained in the system.
                        </p>

                    </div>
                </div>


                {{-- SEARCH --}}
                <div class="border-b border-gray-200 p-6">

                    <form
                        action="{{ route('data-management.employment-status') }}"
                        method="GET"
                    >

                        <div class="flex flex-col gap-3 md:flex-row">

                            {{-- SEARCH INPUT --}}
                            <div class="flex-1">

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
                                    class="w-full rounded-md border-gray-300
                                        text-sm shadow-sm
                                        focus:border-green-600
                                        focus:ring-green-600"
                                >

                            </div>


                            {{-- BUTTONS --}}
                            <div class="flex items-end gap-2">

                                {{-- SEARCH --}}
                                <button
                                    type="submit"
                                    class="rounded-md bg-green-700
                                        px-5 py-2.5
                                        text-sm font-semibold text-white
                                        transition
                                        hover:bg-green-800"
                                >
                                    Search
                                </button>


                                {{-- CLEAR --}}
                                @if($search !== '')

                                    <a
                                        href="{{ route('data-management.employment-status') }}"
                                        class="rounded-md border border-gray-300
                                            bg-white px-5 py-2.5
                                            text-sm font-semibold text-gray-700
                                            transition
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

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-4 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    #
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    Name
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    School Name
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    Item From School Level
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    Plantilla Item No.
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    Position Title
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    Employment Status
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    Date of Original Appointment
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    Date of Last Promotion
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    Warm Body Status
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    Nature of Work
                                </th>

                                <th class="px-4 py-3 text-right text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
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

                                        <div class="mt-1 text-sm text-gray-500">

                                            {{ $record->user?->email ?? '—' }}

                                        </div>

                                    </td>


                                    {{-- SCHOOL NAME --}}
                                    <td class="min-w-[220px] px-4 py-4 text-sm text-gray-700">

                                        {{ $school?->school_name ?? '—' }}

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
                                                class="inline-flex rounded-full
                                                    bg-green-100 px-3 py-1
                                                    text-xs font-semibold
                                                    text-green-800"
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

                                        @if((string) auth()->user()?->basicInformation?->issuedId?->employee_id !== '1000001')
                                            <a
                                                href="{{ route(
                                                    'data-management.employment-status.edit',
                                                    $record->id
                                                ) }}"
                                                class="inline-flex items-center
                                                    rounded-md
                                                    bg-green-700
                                                    px-3 py-2
                                                    text-sm font-semibold
                                                    text-white
                                                    shadow-sm
                                                    transition
                                                    hover:bg-green-800
                                                    focus:outline-none
                                                    focus:ring-2
                                                    focus:ring-green-500
                                                    focus:ring-offset-2"
                                            >
                                                Update
                                            </a>
                                        @endif

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td
                                        colspan="12"
                                        class="px-6 py-12 text-center"
                                    >

                                        <div class="text-sm font-medium text-gray-700">

                                            No employment records found.

                                        </div>

                                        @if($search !== '')

                                            <div class="mt-1 text-sm text-gray-500">

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
                    <div class="border-t border-gray-200 px-6 py-4">

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

        </div>

    </div>

</x-app-layout>