<x-app-layout>

    <div class="min-h-screen min-w-0 bg-gray-50 py-4 sm:py-8">

        <div class="mx-auto w-full min-w-0 max-w-7xl px-4 sm:px-6">


            {{-- =====================================================
                BREADCRUMB
            ====================================================== --}}

            <div class="mb-4">

                <nav
                    class="flex flex-wrap items-center gap-y-2 text-xs sm:text-sm"
                    aria-label="Breadcrumb"
                >

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


                    <a
                        href="{{ route('data-management') }}"
                        class="font-medium text-gray-500 transition hover:text-green-700"
                    >
                        Data Management
                    </a>


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


                    <span class="font-semibold text-green-800">
                        Employment Status
                    </span>

                </nav>

            </div>


            {{-- =====================================================
                ERROR MESSAGE
            ====================================================== --}}

            @if(session('error'))

                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 sm:p-5">

                    <p class="font-semibold text-red-800">
                        {{ session('error') }}
                    </p>

                </div>

            @endif


            {{-- =====================================================
                IMPORT RESULT
            ====================================================== --}}

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


                    @if(count(session('employment_import_result.errors', [])) > 0)

                        <div class="mt-5 rounded-lg border border-red-200 bg-red-50 p-4">

                            <h4 class="font-semibold text-red-800">
                                Import Errors
                            </h4>


                            <ul class="mt-2 list-disc break-words pl-5 text-sm text-red-700">

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

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">

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


                        <div class="flex flex-col gap-3 xl:flex-row xl:items-center">


                            {{-- EXCEL FILE LABEL --}}

                            <label
                                for="file"
                                class="shrink-0 text-sm font-semibold text-gray-700"
                            >
                                EXCEL FILE
                            </label>


                            {{-- CUSTOM FILE INPUT --}}

                            <div class="relative flex h-11 min-w-0 w-full xl:flex-1">

                                <input
                                    type="file"
                                    id="file"
                                    name="file"
                                    accept=".xlsx,.xls"
                                    required
                                    class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0"
                                    onchange="
                                        document.getElementById('file-name').textContent =
                                        this.files.length
                                            ? this.files[0].name
                                            : 'No file selected'
                                    "
                                >


                                <div
                                    class="flex h-full w-full items-center overflow-hidden
                                           rounded-lg border border-gray-300 bg-white shadow-sm"
                                >

                                    <span
                                        class="flex h-full shrink-0 items-center
                                               border-r border-green-200
                                               bg-green-50 px-4
                                               text-sm font-semibold text-green-700"
                                    >
                                        Browse...
                                    </span>


                                    <span
                                        id="file-name"
                                        class="truncate px-4 text-sm text-gray-500"
                                    >
                                        No file selected
                                    </span>

                                </div>

                            </div>


                            {{-- ACTION BUTTONS --}}

                            <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row">

                                <button
                                    type="submit"
                                    class="inline-flex min-h-11 items-center justify-center gap-2
                                           rounded-lg bg-green-700 px-5
                                           text-sm font-semibold text-white
                                           shadow-sm transition hover:bg-green-800"
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


                                <a
                                    href="{{ route('data-management.employment-status.download-template') }}"
                                    class="inline-flex min-h-11 items-center justify-center gap-2
                                           rounded-lg border border-green-700 bg-white px-4
                                           text-sm font-semibold text-green-700
                                           shadow-sm transition hover:bg-green-50"
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

            @endif


            {{-- =====================================================
                RECORD VARIABLES
            ====================================================== --}}

            @php

                $user = auth()->user();

                $isSuperAdmin = $user &&
                    (
                        method_exists($user, 'hasRole')
                            ? $user->hasRole('super_admin')
                            : $user->role === 'super_admin'
                    );

                $isAdmin = $user &&
                    (
                        method_exists($user, 'hasRole')
                            ? $user->hasRole('admin')
                            : $user->role === 'admin'
                    );

                $search = request('search', '');

                /*
                |--------------------------------------------------------------------------
                | Active / Inactive Employee Tab
                |--------------------------------------------------------------------------
                */

                $employeeTab = $employeeTab ?? request('status', 'active');

                $editRouteName = $editRouteName
                    ?? 'data-management.employment-status.edit';

                $adminSchool = $school
                    ?? data_get($user, 'school')
                    ?? collect($employmentStatuses->items())->first()?->school;

                $schoolName = $schoolName
                    ?? data_get($adminSchool, 'school_name')
                    ?? 'School not assigned';

                $districtName = $districtName
                    ?? data_get($adminSchool, 'school_district')
                    ?? data_get($adminSchool, 'district.district_name')
                    ?? 'District not assigned';

            @endphp


            {{-- =====================================================
                SUPER ADMIN RECORDS
            ====================================================== --}}

            @if($isSuperAdmin)

                <div
                    class="min-w-0 overflow-hidden rounded-xl
                           border border-gray-200 bg-white shadow-sm"
                >


                    {{-- TABLE HEADER --}}

                    <div
                        class="border-b border-green-800
                               bg-green-800 px-5 py-4 text-white"
                        style="background-color: #166534;"
                    >

                        <h2 class="text-xl font-semibold text-white">
                            Employment Profile Records
                        </h2>

                        <p class="mt-1 text-sm text-green-100">
                            List of personnel employment status records maintained in the system.
                        </p>

                    </div>


                    {{-- =====================================================
                        ACTIVE / INACTIVE EMPLOYEE TABS
                    ====================================================== --}}

                    <div class="border-b border-gray-200 bg-white px-4 py-3 sm:px-5">

                        <div
                            class="inline-flex rounded-lg border border-gray-200
                                bg-gray-100 p-1"
                        >

                            {{-- ACTIVE EMPLOYEES --}}
                            <a
                                href="{{ route(
                                    'data-management.employment-status',
                                    array_filter([
                                        'status' => 'active',
                                        'search' => $search ?: null,
                                    ])
                                ) }}"
                                class="inline-flex min-h-9 items-center justify-center
                                    gap-2 rounded-md px-4 py-2
                                    text-sm font-semibold transition-all
                                    {{ $employeeTab === 'active'
                                            ? 'bg-white text-green-700 shadow-sm ring-1 ring-black/5'
                                            : 'text-gray-500 hover:bg-white/60 hover:text-gray-700'
                                    }}"
                            >

                                {{-- ACTIVE ICON --}}
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9 12.75 11.25 15 15 9.75
                                        M21 12a9 9 0 1 1-18 0
                                        9 9 0 0 1 18 0Z"
                                    />
                                </svg>

                                Active Employees

                            </a>


                            {{-- INACTIVE EMPLOYEES --}}
                            <a
                                href="{{ route(
                                    'data-management.employment-status',
                                    array_filter([
                                        'status' => 'inactive',
                                        'search' => $search ?: null,
                                    ])
                                ) }}"
                                class="inline-flex min-h-9 items-center justify-center
                                    gap-2 rounded-md px-4 py-2
                                    text-sm font-semibold transition-all
                                    {{ $employeeTab === 'inactive'
                                            ? 'bg-white text-red-600 shadow-sm ring-1 ring-black/5'
                                            : 'text-gray-500 hover:bg-white/60 hover:text-gray-700'
                                    }}"
                            >

                                {{-- INACTIVE ICON --}}
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M6 18 18 6M6 6l12 12"
                                    />
                                </svg>

                                Inactive Employees

                            </a>

                        </div>

                    </div>


                    {{-- =====================================================
                        SEARCH
                    ====================================================== --}}

                    <div class="border-b border-gray-200 p-4">

                        <form
                            action="{{ route('data-management.employment-status') }}"
                            method="GET"
                        >

                            {{-- PRESERVE ACTIVE / INACTIVE TAB --}}
                            <input
                                type="hidden"
                                name="status"
                                value="{{ $employeeTab }}"
                            >


                            <label
                                for="search"
                                class="mb-2 block text-sm font-semibold text-gray-700"
                            >
                                Search
                                {{ $employeeTab === 'active'
                                    ? 'Active Employees'
                                    : 'Inactive Employees'
                                }}
                            </label>


                            <div class="flex flex-col gap-2 sm:flex-row">

                                <input
                                    type="text"
                                    id="search"
                                    name="search"
                                    value="{{ $search }}"
                                    placeholder="Search name, school, office unit, item no., position, status..."
                                    class="min-h-10 min-w-0 flex-1 rounded-lg
                                        border-gray-300 text-sm shadow-sm
                                        focus:border-green-600
                                        focus:ring-green-600"
                                >


                                <button
                                    type="submit"
                                    class="inline-flex min-h-10 items-center justify-center
                                        rounded-lg bg-green-700 px-5
                                        text-sm font-semibold text-white
                                        transition hover:bg-green-800"
                                >
                                    Search
                                </button>


                                @if($search !== '')

                                    <a
                                        href="{{ route(
                                            'data-management.employment-status',
                                            ['status' => $employeeTab]
                                        ) }}"
                                        class="inline-flex min-h-10 items-center justify-center
                                            rounded-lg border border-gray-300
                                            bg-white px-4
                                            text-sm font-semibold text-gray-700
                                            transition hover:bg-gray-50"
                                    >
                                        Clear
                                    </a>

                                @endif

                            </div>

                        </form>

                    </div>


                    <p
                        class="border-b border-gray-100
                               px-4 py-2 text-xs text-gray-500 lg:hidden"
                    >
                        Swipe left or right to view all columns.
                    </p>


                    {{-- =====================================================
                        SUPER ADMIN TABLE
                    ====================================================== --}}

                    <div
                        class="w-full overflow-x-auto overscroll-x-contain"
                        tabindex="0"
                        role="region"
                        aria-label="Employment status records"
                    >

                        <table
                            class="w-full min-w-[1180px]
                                   table-auto divide-y divide-gray-200"
                        >

                            {{-- =====================================================
                                TABLE HEADER
                            ====================================================== --}}

                            <thead class="bg-gray-50">

                                <tr>


                                    {{-- NUMBER --}}

                                    <th
                                        class="w-[45px] px-3 py-3.5
                                               text-left text-[13px] font-bold
                                               uppercase tracking-wide text-gray-700"
                                    >
                                        #
                                    </th>


                                    {{-- NAME --}}

                                    <th
                                        class="w-[220px] px-3 py-3.5
                                               text-left text-[13px] font-bold
                                               uppercase tracking-wide text-gray-700"
                                    >
                                        Name
                                    </th>


                                    {{-- PERSONNEL ASSIGNMENT --}}

                                    <th
                                        class="w-[390px] min-w-[390px] px-4 py-3.5
                                               text-left text-[13px] font-bold
                                               uppercase tracking-wide text-gray-700"
                                    >

                                        Personnel Assignment

                                        <span
                                            class="mt-1 block text-[11px]
                                                   font-semibold normal-case
                                                   tracking-normal text-gray-500"
                                        >
                                            Division Office / School Based
                                        </span>

                                    </th>


                                    {{-- PLANTILLA ITEM + POSITION --}}

                                    <th
                                        class="w-[245px] min-w-[245px] px-3 py-3.5
                                               text-left text-[13px] font-bold
                                               uppercase tracking-wide text-gray-700"
                                    >
                                        Plantilla Item / Position
                                    </th>


                                    {{-- NATURE OF WORK / WARM BODY STATUS--}}

                                    <th
                                        class="w-[150px] px-3 py-3.5
                                            text-left text-[13px] font-bold
                                            uppercase tracking-wide text-gray-700"
                                    >
                                        @if($employeeTab === 'inactive')
                                            Inactive Status
                                        @else
                                            Nature of Work
                                        @endif
                                    </th>


                                    {{-- PROFILE --}}

                                    <th
                                        class="w-[80px] px-2 py-3.5
                                               text-center text-[12px] font-bold
                                               uppercase tracking-wide text-gray-700"
                                    >
                                        Profile
                                    </th>


                                    {{-- EMPLOYMENT --}}

                                    <th
                                        class="w-[95px] px-2 py-3.5
                                               text-center text-[12px] font-bold
                                               uppercase tracking-wide text-gray-700"
                                    >
                                        Employment
                                    </th>

                                </tr>

                            </thead>


                            {{-- =====================================================
                                TABLE BODY
                            ====================================================== --}}

                            <tbody class="divide-y divide-gray-100 bg-white">

                                @forelse($employmentStatuses as $record)

                                    @php

                                        $basic =
                                            $record->user?->basicInformation;

                                        $plantilla =
                                            $record->plantilla;

                                        $recordSchool =
                                            $record->school;

                                        $officeUnit =
                                            $record->officeUnit;

                                        $name = trim(
                                            implode(
                                                ' ',
                                                array_filter([
                                                    $basic?->first_name,
                                                    $basic?->middle_name,
                                                    $basic?->last_name,
                                                    $basic?->extension_name,
                                                ])
                                            )
                                        );

                                    @endphp


                                    <tr
                                        class="transition-colors
                                               hover:bg-green-50/30"
                                    >


                                        {{-- =====================================================
                                            NUMBER
                                        ====================================================== --}}

                                        <td
                                            class="w-[45px] whitespace-nowrap
                                                   px-3 py-4 align-middle
                                                   text-sm text-gray-500"
                                        >
                                            {{ $employmentStatuses->firstItem() + $loop->index }}
                                        </td>


                                        {{-- =====================================================
                                            NAME
                                        ====================================================== --}}

                                        <td
                                            class="w-[220px] min-w-[220px]
                                                   px-3 py-4 align-top"
                                        >

                                            <div
                                                class="text-sm font-semibold
                                                       leading-5 text-gray-900"
                                            >
                                                {{ $name ?: '—' }}
                                            </div>


                                            <div
                                                class="mt-1 break-all
                                                       text-xs text-gray-500"
                                            >
                                                {{ $record->user?->email ?? '—' }}
                                            </div>

                                        </td>


                                        {{-- =====================================================
                                            PERSONNEL ASSIGNMENT
                                        ====================================================== --}}

                                        <td
                                            class="w-[390px] min-w-[390px]
                                                   px-4 py-4 align-top"
                                        >


                                            {{-- =================================================
                                                DIVISION OFFICE
                                            ================================================== --}}

                                            @if(
                                                $record->office_unit_id &&
                                                $officeUnit
                                            )

                                                <div>


                                                    {{-- DIVISION OFFICE BADGE --}}
                                                    <span
                                                        class="inline-flex items-center
                                                            rounded-md border px-2 py-0.5
                                                            text-[10px] font-semibold
                                                            uppercase tracking-wide"
                                                        style="
                                                            background-color: rgb(179, 227, 255);
                                                            border-color: rgba(37, 99, 235, 0.18);
                                                            color: #2563eb;
                                                        "
                                                    >
                                                        Division Office
                                                    </span>


                                                    {{-- OFFICE UNIT --}}

                                                    <div
                                                        class="mt-2 text-sm
                                                               font-bold leading-5
                                                               text-gray-900"
                                                    >
                                                        {{ $officeUnit->name }}
                                                    </div>


                                                    {{-- PARENT UNIT --}}

                                                    @if($officeUnit->parent)

                                                        <div
                                                            class="mt-0.5 text-xs
                                                                   font-medium leading-5
                                                                   text-gray-500"
                                                        >
                                                            {{ $officeUnit->parent->name }}
                                                        </div>

                                                    @endif


                                                    {{-- OFFICE GROUP / CODE --}}

                                                    @if(
                                                        $officeUnit->officeGroup ||
                                                        $officeUnit->code
                                                    )

                                                        <div
                                                            class="mt-1 text-[11px]
                                                                   font-medium text-gray-400"
                                                        >

                                                            @if($officeUnit->officeGroup)

                                                                {{ $officeUnit->officeGroup->code }}

                                                            @endif


                                                            @if(
                                                                $officeUnit->officeGroup &&
                                                                $officeUnit->code
                                                            )

                                                                <span class="mx-1">
                                                                    •
                                                                </span>

                                                            @endif


                                                            @if($officeUnit->code)

                                                                {{ $officeUnit->code }}

                                                            @endif

                                                        </div>

                                                    @endif

                                                </div>


                                            {{-- =================================================
                                                SCHOOL BASED
                                            ================================================== --}}

                                            @elseif(
                                                $record->school_db_id &&
                                                $recordSchool
                                            )

                                                <div>


                                                    {{-- SCHOOL BASED BADGE --}}
                                                    <span
                                                        class="inline-flex items-center
                                                            rounded-md border px-2 py-0.5
                                                            text-[10px] font-semibold
                                                            uppercase tracking-wide"
                                                        style="
                                                            background-color: rgba(2, 251, 93, 0.08);
                                                            border-color: rgba(21, 128, 61, 0.18);
                                                            color: #15803d;
                                                        "
                                                    >
                                                        School Based
                                                    </span>


                                                    {{-- SCHOOL NAME --}}

                                                    <div
                                                        class="mt-2 text-sm
                                                               font-bold leading-5
                                                               text-gray-900"
                                                    >
                                                        {{ $recordSchool->school_name }}
                                                    </div>


                                                    {{-- DISTRICT --}}

                                                    @if($recordSchool->school_district)

                                                        <div
                                                            class="mt-0.5 text-xs
                                                                   font-medium leading-5
                                                                   text-gray-500"
                                                        >
                                                            {{ $recordSchool->school_district }}
                                                        </div>

                                                    @endif


                                                    {{-- SCHOOL ID --}}

                                                    @if($recordSchool->school_id)

                                                        <div
                                                            class="mt-1 text-[11px]
                                                                   font-medium text-gray-400"
                                                        >
                                                            School ID:
                                                            {{ $recordSchool->school_id }}
                                                        </div>

                                                    @endif

                                                </div>


                                            {{-- =================================================
                                                NO ASSIGNMENT
                                            ================================================== --}}

                                            @else

                                                <span class="text-sm text-gray-400">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        {{-- =====================================================
                                            PLANTILLA ITEM / POSITION
                                        ====================================================== --}}

                                        <td
                                            class="w-[245px] min-w-[245px]
                                                   px-3 py-4 align-top"
                                        >

                                            {{-- POSITION TITLE --}}

                                            <div
                                                class="text-sm font-semibold
                                                       leading-5 text-gray-900"
                                            >
                                                {{ $plantilla?->position_title ?? '—' }}
                                            </div>


                                            {{-- ITEM NUMBER --}}

                                            @if($plantilla?->item_number)

                                                <div
                                                    class="mt-1 text-xs
                                                           leading-5 text-gray-500"
                                                >
                                                    {{ $plantilla->item_number }}
                                                </div>

                                            @else

                                                <div
                                                    class="mt-1 text-xs
                                                           text-gray-400"
                                                >
                                                    No Plantilla Item
                                                </div>

                                            @endif

                                        </td>


                                        {{-- =====================================================
                                            NATURE OF WORK / WARM BODY STATUS
                                        ====================================================== --}}

                                        <td
                                            class="w-[150px] min-w-[150px]
                                                px-3 py-4 align-top"
                                        >

                                            @if($employeeTab === 'inactive')

                                                @php
                                                    $inactiveStyle = match($record->warm_body_status) {
                                                        'Vacant (Retired)' =>
                                                            'background-color:#fef3c7; color:#92400e;',

                                                        'Vacant (Resigned)' =>
                                                            'background-color:#fee2e2; color:#991b1b;',

                                                        'Vacant (Others)' =>
                                                            'background-color:#f3f4f6; color:#4b5563;',

                                                        default =>
                                                            'background-color:#f3f4f6; color:#4b5563;',
                                                    };
                                                @endphp

                                                <span
                                                    class="inline-flex rounded-md px-2.5 py-1
                                                        text-xs font-semibold"
                                                    style="{{ $inactiveStyle }}"
                                                >
                                                    {{ $record->warm_body_status ?? '—' }}
                                                </span>

                                            @else

                                                <span class="text-sm leading-5 text-gray-700">
                                                    {{ $record->nature_of_work ?? '—' }}
                                                </span>

                                            @endif

                                        </td>


                                        {{-- =====================================================
                                            PROFILE ACTION
                                        ====================================================== --}}

                                        <td
                                            class="w-[80px] whitespace-nowrap
                                                   px-2 py-4 text-center
                                                   align-middle"
                                        >

                                            @if($basic)

                                                <a
                                                    href="{{ route(
                                                        'data-management.personnel.edit',
                                                        $basic->id
                                                    ) }}"
                                                    title="Update Profile Information"
                                                    class="inline-flex h-8 items-center
                                                           justify-center rounded-md
                                                           bg-green-700 px-2.5
                                                           text-xs font-semibold
                                                           text-white shadow-sm
                                                           transition hover:bg-green-800
                                                           focus:outline-none
                                                           focus:ring-2
                                                           focus:ring-green-500"
                                                >
                                                    Update
                                                </a>

                                            @else

                                                <span
                                                    class="text-[11px]
                                                           text-gray-400"
                                                >
                                                    No Profile
                                                </span>

                                            @endif

                                        </td>


                                        {{-- =====================================================
                                            EMPLOYMENT ACTION
                                        ====================================================== --}}

                                        <td
                                            class="w-[95px] whitespace-nowrap
                                                   px-2 py-4 text-center
                                                   align-middle"
                                        >

                                            <a
                                                href="{{ route(
                                                    'data-management.employment-status.edit',
                                                    $record->id
                                                ) }}"
                                                title="Update Employment & Deployment Status"
                                                class="inline-flex h-8 items-center
                                                       justify-center rounded-md
                                                       bg-green-700 px-2.5
                                                       text-xs font-semibold
                                                       text-white shadow-sm
                                                       transition hover:bg-green-800
                                                       focus:outline-none
                                                       focus:ring-2
                                                       focus:ring-green-500"
                                            >
                                                Update
                                            </a>

                                        </td>

                                    </tr>


                                @empty

                                <tr>

                                    <td
                                        colspan="7"
                                        class="px-6 py-14 text-center"
                                    >

                                        <div class="text-sm font-medium text-gray-700">

                                            @if($employeeTab === 'active')

                                                No active employee records found.

                                            @else

                                                No inactive employee records found.

                                            @endif

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


                    {{-- =====================================================
                        PAGINATION
                    ====================================================== --}}

                    <div
                        class="border-t border-gray-200
                               bg-gray-50/50 px-4 py-4 sm:px-5"
                    >

                        <div
                            class="flex flex-col gap-4
                                   sm:flex-row sm:items-center
                                   sm:justify-between"
                        >

                            <div class="text-sm text-gray-5300">

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
                                {{ $employmentStatuses->withQueryString()->links() }}
                            </div>

                        </div>

                    </div>

                </div>


            {{-- =====================================================
                ADMIN RECORDS
            ====================================================== --}}

            @elseif($isAdmin)

                <div
                    class="min-w-0 overflow-hidden rounded-xl
                           border border-gray-200 bg-white shadow-sm"
                    >


                    {{-- =====================================================
                        HEADER
                    ====================================================== --}}

                    <div
                        class="border-b border-green-800 bg-green-800 px-5 py-5 text-white"
                        style="background-color: #166534;"
                    >

                        {{-- ONE ROW: TITLE LEFT / BUTTONS RIGHT --}}
                        <div class="flex w-full items-center justify-between gap-6">

                            {{-- LEFT SIDE --}}
                            <div class="min-w-0 flex-1">

                                <h3 class="text-xl font-semibold text-white">
                                    Employment Profile Records
                                    ({{ $schoolName }} - {{ $districtName }})
                                </h3>

                                <p class="mt-1 text-sm text-green-100">
                                    List of personnel employment status records and related information.
                                </p>

                            </div>


                            {{-- RIGHT SIDE --}}
                            <div class="ml-auto flex shrink-0 items-center gap-3">

                                {{-- VIEW REQUEST STATUS --}}
                                <a
                                    href="{{ route('add-personnel-requests.index') }}"
                                    class="inline-flex h-11 items-center justify-center gap-2
                                        whitespace-nowrap rounded-lg
                                        border border-white/60
                                        bg-transparent px-5
                                        text-sm font-semibold text-white
                                        transition
                                        hover:bg-white/10"
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
                                            d="M8.25 6.75h12
                                            M8.25 12h12
                                            M8.25 17.25h12
                                            M3.75 6.75h.008v.008H3.75V6.75z
                                            M3.75 12h.008v.008H3.75V12z
                                            M3.75 17.25h.008v.008H3.75v-.008z"
                                        />
                                    </svg>

                                    View Request Status
                                </a>


                                {{-- ADD PERSONNEL - PRIMARY BUTTON --}}
                                <a
                                    href="{{ route('add-personnel-requests.create') }}"
                                    class="inline-flex h-11 items-center justify-center gap-2
                                        whitespace-nowrap rounded-lg
                                        border border-white
                                        bg-white px-5
                                        text-sm font-bold text-green-800
                                        shadow-md transition
                                        hover:bg-green-50 hover:shadow-lg"
                                    style="
                                        background-color: #ffffff;
                                        color: #166534;
                                    "
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2.5"
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

                    </div>

                    {{-- =====================================================
                        ACTIVE / INACTIVE EMPLOYEE TABS
                    ====================================================== --}}

                    <div class="border-b border-gray-200 bg-white px-4 py-3 sm:px-5">

                        <div
                            class="inline-flex rounded-lg border border-gray-200
                                bg-gray-100 p-1"
                        >

                            {{-- ACTIVE EMPLOYEES --}}
                            <a
                                href="{{ route(
                                    'data-management.employment-status',
                                    array_filter([
                                        'status' => 'active',
                                        'search' => $search ?: null,
                                    ])
                                ) }}"
                                class="inline-flex min-h-9 items-center justify-center
                                    gap-2 rounded-md px-4 py-2
                                    text-sm font-semibold transition-all
                                    {{ $employeeTab === 'active'
                                            ? 'bg-white text-green-700 shadow-sm ring-1 ring-black/5'
                                            : 'text-gray-500 hover:bg-white/60 hover:text-gray-700'
                                    }}"
                            >

                                {{-- ACTIVE ICON --}}
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9 12.75 11.25 15 15 9.75
                                        M21 12a9 9 0 1 1-18 0
                                        9 9 0 0 1 18 0Z"
                                    />
                                </svg>

                                Active Employees

                            </a>


                            {{-- INACTIVE EMPLOYEES --}}
                            <a
                                href="{{ route(
                                    'data-management.employment-status',
                                    array_filter([
                                        'status' => 'inactive',
                                        'search' => $search ?: null,
                                    ])
                                ) }}"
                                class="inline-flex min-h-9 items-center justify-center
                                    gap-2 rounded-md px-4 py-2
                                    text-sm font-semibold transition-all
                                    {{ $employeeTab === 'inactive'
                                            ? 'bg-white text-red-600 shadow-sm ring-1 ring-black/5'
                                            : 'text-gray-500 hover:bg-white/60 hover:text-gray-700'
                                    }}"
                            >

                                {{-- INACTIVE ICON --}}
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M6 18 18 6M6 6l12 12"
                                    />
                                </svg>

                                Inactive Employees

                            </a>

                        </div>

                    </div>


                    {{-- =====================================================
                        SEARCH
                    ====================================================== --}}

                    <div class="border-b border-gray-200 p-4">

                        <form
                            action="{{ route('data-management.employment-status') }}"
                            method="GET"
                        >

                            {{-- PRESERVE ACTIVE / INACTIVE TAB --}}
                            <input
                                type="hidden"
                                name="status"
                                value="{{ $employeeTab }}"
                            >


                            <label
                                for="search"
                                class="mb-2 block text-sm font-semibold text-gray-700"
                            >
                                Search
                                {{ $employeeTab === 'active'
                                    ? 'Active Employees'
                                    : 'Inactive Employees'
                                }}
                            </label>


                            <div class="flex flex-col gap-2 sm:flex-row">

                                <input
                                    type="text"
                                    id="search"
                                    name="search"
                                    value="{{ $search }}"
                                    placeholder="Search name, school, office unit, item no., position, status..."
                                    class="min-h-10 min-w-0 flex-1 rounded-lg
                                        border-gray-300 text-sm shadow-sm
                                        focus:border-green-600
                                        focus:ring-green-600"
                                >


                                <button
                                    type="submit"
                                    class="inline-flex min-h-10 items-center justify-center
                                        rounded-lg bg-green-700 px-5
                                        text-sm font-semibold text-white
                                        transition hover:bg-green-800"
                                >
                                    Search
                                </button>


                                @if($search !== '')

                                    <a
                                        href="{{ route(
                                            'data-management.employment-status',
                                            ['status' => $employeeTab]
                                        ) }}"
                                        class="inline-flex min-h-10 items-center justify-center
                                            rounded-lg border border-gray-300
                                            bg-white px-4
                                            text-sm font-semibold text-gray-700
                                            transition hover:bg-gray-50"
                                    >
                                        Clear
                                    </a>

                                @endif

                            </div>

                        </form>

                    </div>


                    <p
                        class="border-b border-gray-100
                               px-4 py-2 text-xs text-gray-500 lg:hidden"
                    >
                        Swipe left or right to view all columns.
                    </p>


                    {{-- =====================================================
                        ADMIN TABLE
                    ====================================================== --}}

                    <div
                        class="w-full overflow-x-auto overscroll-x-contain"
                        tabindex="0"
                        role="region"
                        aria-label="Admin employment status records"
                    >

                        <table
                            class="w-full min-w-[900px]
                                   table-auto divide-y divide-gray-200"
                        >

                            <thead class="bg-gray-50">

                                <tr>


                                    {{-- NUMBER --}}

                                    <th
                                        class="w-[45px] px-3 py-3.5
                                               text-left text-[13px] font-bold
                                               uppercase tracking-wide text-gray-700"
                                    >
                                        #
                                    </th>


                                    {{-- NAME --}}

                                    <th
                                        class="w-[260px] px-3 py-3.5
                                               text-left text-[13px] font-bold
                                               uppercase tracking-wide text-gray-700"
                                    >
                                        Name & Email
                                    </th>


                                    {{-- PLANTILLA / POSITION --}}

                                    <th
                                        class="w-[280px] min-w-[280px]
                                               px-3 py-3.5 text-left
                                               text-[13px] font-bold uppercase
                                               tracking-wide text-gray-700"
                                    >
                                        Plantilla Item / Position
                                    </th>


                                    {{-- NATURE OF WORK / WARD BODY STATUS --}}

                                    <th
                                        class="w-[150px] px-3 py-3.5
                                            text-left text-[13px] font-bold
                                            uppercase tracking-wide text-gray-700"
                                    >
                                        @if($employeeTab === 'inactive')
                                            Inactive Status
                                        @else
                                            Nature of Work
                                        @endif
                                    </th>


                                    {{-- PROFILE --}}

                                    <th
                                        class="w-[80px] px-2 py-3.5
                                               text-center text-[12px] font-bold
                                               uppercase tracking-wide text-gray-700"
                                    >
                                        Profile
                                    </th>


                                    {{-- EMPLOYMENT --}}

                                    <th
                                        class="w-[95px] px-2 py-3.5
                                               text-center text-[12px] font-bold
                                               uppercase tracking-wide text-gray-700"
                                    >
                                        Employment
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-100 bg-white">

                                @forelse($employmentStatuses as $record)

                                    @php

                                        $basic =
                                            $record->user?->basicInformation;

                                        $plantilla =
                                            $record->plantilla;

                                        $name = trim(
                                            implode(
                                                ' ',
                                                array_filter([
                                                    $basic?->first_name,
                                                    $basic?->middle_name,
                                                    $basic?->last_name,
                                                    $basic?->extension_name,
                                                ])
                                            )
                                        );

                                    @endphp


                                    <tr
                                        class="transition-colors
                                               hover:bg-green-50/30"
                                    >


                                        {{-- NUMBER --}}

                                        <td
                                            class="w-[45px] whitespace-nowrap
                                                   px-3 py-4 text-sm text-gray-500"
                                        >
                                            {{ $employmentStatuses->firstItem() + $loop->index }}
                                        </td>


                                        {{-- NAME --}}

                                        <td
                                            class="w-[260px] min-w-[260px]
                                                   px-3 py-4 align-top"
                                        >

                                            <div
                                                class="text-sm font-semibold
                                                       text-gray-900"
                                            >
                                                {{ $name ?: '—' }}
                                            </div>


                                            <div
                                                class="mt-1 break-all
                                                       text-xs text-gray-500"
                                            >
                                                {{ $record->user?->email ?? '—' }}
                                            </div>

                                        </td>


                                        {{-- =====================================================
                                            PLANTILLA / POSITION
                                        ====================================================== --}}

                                        <td
                                            class="w-[280px] min-w-[280px]
                                                   px-3 py-4 align-top"
                                        >

                                            <div
                                                class="text-sm font-semibold
                                                       leading-5 text-gray-900"
                                            >
                                                {{ $plantilla?->position_title ?? '—' }}
                                            </div>


                                            @if($plantilla?->item_number)

                                                <div
                                                    class="mt-1 text-xs
                                                           leading-5 text-gray-500"
                                                >
                                                    {{ $plantilla->item_number }}
                                                </div>

                                            @else

                                                <div
                                                    class="mt-1 text-xs
                                                           text-gray-400"
                                                >
                                                    No Plantilla Item
                                                </div>

                                            @endif

                                        </td>


                                        {{-- NATURE OF WORK / WARM BODY STATUS--}}

                                        <td
                                            class="w-[150px] min-w-[150px]
                                                px-3 py-4 align-top"
                                        >

                                            @if($employeeTab === 'inactive')

                                                @php
                                                    $inactiveStyle = match($record->warm_body_status) {
                                                        'Vacant (Retired)' =>
                                                            'background-color:#fef3c7; color:#92400e;',

                                                        'Vacant (Resigned)' =>
                                                            'background-color:#fee2e2; color:#991b1b;',

                                                        'Vacant (Others)' =>
                                                            'background-color:#f3f4f6; color:#4b5563;',

                                                        default =>
                                                            'background-color:#f3f4f6; color:#4b5563;',
                                                    };
                                                @endphp

                                                <span
                                                    class="inline-flex rounded-md px-2.5 py-1
                                                        text-xs font-semibold"
                                                    style="{{ $inactiveStyle }}"
                                                >
                                                    {{ $record->warm_body_status ?? '—' }}
                                                </span>

                                            @else

                                                <span class="text-sm leading-5 text-gray-700">
                                                    {{ $record->nature_of_work ?? '—' }}
                                                </span>

                                            @endif

                                        </td>


                                        {{-- PROFILE --}}

                                        <td
                                            class="w-[80px] whitespace-nowrap
                                                   px-2 py-4 text-center
                                                   align-middle"
                                        >

                                            @if($basic)

                                                <a
                                                    href="{{ route(
                                                        'data-management.personnel.edit',
                                                        $basic->id
                                                    ) }}"
                                                    class="inline-flex h-8 items-center
                                                           justify-center rounded-md
                                                           bg-green-700 px-2.5
                                                           text-xs font-semibold
                                                           text-white shadow-sm
                                                           transition hover:bg-green-800"
                                                >
                                                    Update
                                                </a>

                                            @else

                                                <span
                                                    class="text-[11px]
                                                           text-gray-400"
                                                >
                                                    No Profile
                                                </span>

                                            @endif

                                        </td>


                                        {{-- EMPLOYMENT --}}

                                        <td
                                            class="w-[95px] whitespace-nowrap
                                                   px-2 py-4 text-center
                                                   align-middle"
                                        >

                                            <a
                                                href="{{ route(
                                                    $editRouteName,
                                                    $record->id
                                                ) }}"
                                                class="inline-flex h-8 items-center
                                                       justify-center rounded-md
                                                       bg-green-700 px-2.5
                                                       text-xs font-semibold
                                                       text-white shadow-sm
                                                       transition hover:bg-green-800"
                                            >
                                                Update
                                            </a>

                                        </td>

                                    </tr>


                                @empty

                                <tr>

                                    <td
                                        colspan="7"
                                        class="px-6 py-14 text-center"
                                    >

                                        <div class="text-sm font-medium text-gray-700">

                                            @if($employeeTab === 'active')

                                                No active employee records found.

                                            @else

                                                No inactive employee records found.

                                            @endif

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


                    {{-- =====================================================
                        PAGINATION
                    ====================================================== --}}

                    <div
                        class="border-t border-gray-200
                               bg-gray-50/50 px-4 py-4 sm:px-5"
                    >

                        <div
                            class="flex flex-col gap-4
                                   sm:flex-row sm:items-center
                                   sm:justify-between"
                        >

                            <p class="text-sm text-gray-500">

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

                            </p>


                            <div>
                                {{ $employmentStatuses->withQueryString()->links() }}
                            </div>

                        </div>

                    </div>

                </div>


            @else

                @php(abort(403))

            @endif


        </div>

    </div>

</x-app-layout>