@push('styles')
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css"
    >
    <style>
        .employment-profile .ts-wrapper {
            width: 100%;
            min-width: 0;
            max-width: 100%;
        }
        .employment-profile .ts-control {
            min-height: 44px;
            border-radius: 0.5rem;
            align-items: center;
        }
        .employment-profile .ts-control > .item,
        .employment-profile .ts-dropdown .option {
            white-space: normal;
            overflow-wrap: anywhere;
            max-width: 100%;
        }
        .employment-profile .ts-control > input {
            min-width: 0 !important;
            max-width: 100% !important;
        }
        .employment-profile .ts-dropdown {
            max-width: 100%;
        }
        .employment-profile .ts-dropdown-content {
            max-height: 240px;
            max-height: min(240px, 40dvh);
            overscroll-behavior: contain;
        }
        @media (max-width: 639px) {
            .employment-profile .ts-control,
            .employment-profile .ts-control > input,
            .employment-profile .ts-dropdown {
                font-size: 16px;
            }
        }
    </style>
@endpush

<x-app-layout>

    <div class="employment-profile mx-auto w-full min-w-0 max-w-7xl px-4 py-4 sm:px-6 sm:py-8">

        {{-- =====================================================
            BREADCRUMB TRAIL
        ====================================================== --}}
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


                {{-- Employment Status --}}
                <a
                    href="{{ route('data-management.employment-status') }}"
                    class="font-medium text-gray-500 transition hover:text-green-700"
                >
                    Employment Status
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
                    Update
                </span>

            </nav>

        </div>


        {{-- =====================================================
            SUCCESS MESSAGE
        ====================================================== --}}
        @if(session('success'))

            <div
                class="mb-6 flex items-center gap-3 rounded-xl border border-green-300 bg-green-100 px-5 py-4 text-sm font-medium text-green-900"
            >

                {{-- SUCCESS ICON --}}
                <div
                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-green-600 text-white"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2.5"
                        class="h-5 w-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>
                </div>


                {{-- MESSAGE --}}
                <div>

                    <p class="font-bold text-green-900">
                        Update Successful
                    </p>

                    <p class="mt-0.5 text-sm font-normal text-green-800">
                        {{ session('success') }}
                    </p>

                </div>

            </div>

        @endif


        {{-- =====================================================
            ERROR MESSAGE
        ====================================================== --}}
        @if(session('error'))

            <div
                class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700"
            >
                {{ session('error') }}
            </div>

        @endif


        {{-- =====================================================
            PERSONNEL SUMMARY
        ====================================================== --}}
        @php
            $basic = $record->user?->basicInformation;
        @endphp

        <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm">

            <div class="flex min-w-0 items-start gap-3 sm:items-center sm:gap-4 [&>div]:min-w-0">

                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-green-100 text-xl font-bold text-green-700"
                >
                    {{ strtoupper(substr($basic?->first_name ?? '?', 0, 1)) }}
                    {{ strtoupper(substr($basic?->last_name ?? '?', 0, 1)) }}
                </div>


                <div>

                    <h2 class="break-words text-lg font-bold text-gray-900 sm:text-xl">
                        {{ $basic?->last_name ?? '—' }},
                        {{ $basic?->first_name ?? '—' }}
                        {{ $basic?->middle_name ?? '' }}
                        {{ $basic?->extension_name ?? '' }}
                    </h2>

                    <p class="text-sm text-gray-500 break-words">
                        {{ $record->user?->email ?? 'No email address' }}
                    </p>

                </div>

            </div>

        </div>


        {{-- =====================================================
            VALIDATION ERRORS
        ====================================================== --}}
        @if($errors->any())

            <div
                class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4"
            >

                <p class="font-semibold text-red-700">
                    Please correct the following:
                </p>

                <ul class="mt-2 list-disc pl-5 text-sm text-red-600">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =====================================================
            FORM
        ====================================================== --}}
        <form
            method="POST"
            action="{{ route(
                'data-management.employment-status.update',
                $record->id
            ) }}"
        >

            @csrf
            @method('PUT')


            {{-- =================================================
                EMPLOYMENT INFORMATION
            ================================================== --}}
            <div class="mb-6 min-w-0 rounded-2xl border border-gray-200 bg-white shadow-sm">

                {{-- SECTION HEADER --}}
                <div class="border-b border-gray-200 bg-gray-50 px-4 sm:px-6 py-4">
                    <h3 class="font-bold text-gray-900">
                        Employment Information
                    </h3>

                    <p class="mt-1 text-xs text-gray-500">
                        Current employment, assignment, plantilla and salary details.
                    </p>
                </div>

                {{-- FORM GRID --}}
                <div class="grid grid-cols-1 gap-5 p-4 sm:p-6 md:grid-cols-2 xl:grid-cols-4 [&>div]:min-w-0">

                    {{-- =================================================
                        PLANTILLA ITEM NUMBER — FULL WIDTH
                    ================================================== --}}
                    <div class="min-w-0 md:col-span-2 xl:col-span-4">
                        <label
                            for="item_number"
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Plantilla Item Number
                        </label>

                        <p
                            id="plantilla-search-guide"
                            hidden
                            style="margin: 8px 0 0; padding: 12px 16px;
                                background: #f0fdf4; border: 1px solid #bbf7d0;
                                border-radius: 8px; color: #166534;
                                font-size: 16px; line-height: 1.5;
                                white-space: nowrap; overflow-x: auto;"
                        >
                            Enter the position code and 6-digit number, for example TCH1-123456 or MTCHR2-123456
                        </p>

                        <select
                            id="item_number"
                            name="item_number"
                            class="employment-search-select w-full min-w-0"
                            data-placeholder="Search by item number or position title..."
                            aria-describedby="plantilla-assignment-remarks"
                        >
                            <option
                                value=""
                                data-assignment-remark="No plantilla item selected."
                                @selected(
                                    (string) old(
                                        'item_number',
                                        $record->plantilla?->item_number
                                    ) === ''
                                )
                            >
                                Search a plantilla item number
                            </option>

                            @foreach ($plantillaItems as $item)
                                <option value="{{ $item->item_number }}"
                                    @selected((string) old('item_number', $record->plantilla?->item_number) === (string) $item->item_number)>
                                    {{ $item->item_number }} - {{ $item->position_title }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Assignment remarks --}}
                        <div
                            id="plantilla-assignment-remarks"
                            class="mt-2 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700"
                            role="status"
                            aria-live="polite"
                            aria-atomic="true"
                        >
                            <p class="font-semibold">Remarks</p>

                            <p
                                id="plantilla-assignment-text"
                                class="mt-1 break-words"
                            >
                                Loading assignment details…
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Assignment results are cached for one minute. Multiple employees may
                                share this item. Changes apply after saving.
                            </p>
                        </div>

                        @error('item_number')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- =================================================
                        SCHOOL — FULL WIDTH
                    ================================================== --}}
                    <div class="min-w-0 md:col-span-2 xl:col-span-4">
                        <label
                            for="school_id"
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            School
                        </label>

                        <select
                            id="school_id"
                            name="school_id"
                            class="employment-search-select w-full min-w-0"
                            data-placeholder="Search by school ID or school name..."
                        >

                            @foreach ($schools as $school)
                                <option
                                    value="{{ $school->school_id }}"
                                    @selected(
                                        (string) old(
                                            'school_id',
                                            $record->school?->school_id
                                        ) === (string) $school->school_id
                                    )
                                >
                                    {{ $school->school_id }} - {{ $school->school_name }}- {{ $school->school_district }}
                                </option>
                            @endforeach
                        </select>

                        @error('school_id')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- =================================================
                        DATE OF ORIGINAL APPOINTMENT — 25%
                    ================================================== --}}
                    <div class="min-w-0 xl:col-span-1">
                        <label
                            for="date_of_original_appointment"
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Date of Original Appointment
                        </label>

                        <input
                            id="date_of_original_appointment"
                            type="date"
                            name="date_of_original_appointment"
                            value="{{ old(
                                'date_of_original_appointment',
                                $record->date_of_original_appointment
                                    ? \Carbon\Carbon::parse(
                                        $record->date_of_original_appointment
                                    )->format('Y-m-d')
                                    : ''
                            ) }}"
                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"
                        >

                        @error('date_of_original_appointment')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- =================================================
                        DATE OF LAST PROMOTION — 25%
                    ================================================== --}}
                    <div class="min-w-0 xl:col-span-1">
                        <label
                            for="date_of_last_promotion"
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Date of Last Promotion
                        </label>

                        <input
                            id="date_of_last_promotion"
                            type="date"
                            name="date_of_last_promotion"
                            value="{{ old(
                                'date_of_last_promotion',
                                $record->date_of_last_promotion
                                    ? \Carbon\Carbon::parse(
                                        $record->date_of_last_promotion
                                    )->format('Y-m-d')
                                    : ''
                            ) }}"
                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"
                        >

                        @error('date_of_last_promotion')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- =================================================
                        EMPLOYMENT STATUS — 25%
                    ================================================== --}}
                    <div class="min-w-0 xl:col-span-1">
                        <label
                            for="employment_status"
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Employment Status
                        </label>

                        @php
                            $employmentStatuses = [
                                'Permanent',
                                'Provisional',
                                'Temporary',
                                'Contractual',
                                'Casual',
                                'Contract of Service',
                                'Job Order',
                                'LGU Deployed',
                            ];
                        @endphp

                        <select
                            id="employment_status"
                            name="employment_status"
                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"
                        >
                            @foreach ($employmentStatuses as $status)
                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        old(
                                            'employment_status',
                                            $record->employment_status
                                        ) === $status
                                    )
                                >
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>

                        @error('employment_status')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- =================================================
                        WARM BODY STATUS — 25%
                    ================================================== --}}
                    <div class="min-w-0 xl:col-span-1">
                        <label
                            for="warm_body_status"
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Warm Body Status
                        </label>

                        <select
                            id="warm_body_status"
                            name="warm_body_status"
                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"
                        >
                            @foreach ([
                                'Original',
                                'Borrowed',
                                'Detailed',
                                'TIC',
                                'ALS',
                                'SNED',
                                'Vacant (Retired)',
                                'Vacant (Resigned)',
                                'Vacant (Others)',
                            ] as $status)
                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        old(
                                            'warm_body_status',
                                            $record->warm_body_status
                                        ) === $status
                                    )
                                >
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>

                        @error('warm_body_status')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- =================================================
                        NATURE OF WORK — 25%
                    ================================================== --}}
                    <div class="min-w-0 xl:col-span-1">
                        <label
                            for="nature_of_work"
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Nature of Work
                        </label>

                        <select
                            id="nature_of_work"
                            name="nature_of_work"
                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"
                        >
                            @foreach ([
                                'District Supervisor',
                                'Teaching Services',
                                'School Administration',
                                'Administrative Support',
                                'Clerical Services',
                                'Driving Services',
                                'Engineering Services',
                                'Health and Allied Services',
                                'IT Services',
                                'Janitorial Services',
                                'Legal Services',
                                'Security Services',
                                'Technical Services',
                                'Labor Services',
                                'Executive or Management Services',
                                'Others',
                            ] as $nature)
                                <option
                                    value="{{ $nature }}"
                                    @selected(
                                        old(
                                            'nature_of_work',
                                            $record->nature_of_work
                                        ) === $nature
                                    )
                                >
                                    {{ $nature }}
                                </option>
                            @endforeach
                        </select>

                        @error('nature_of_work')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- =================================================
                        SOURCE OF FUND — 25%
                    ================================================== --}}
                    <div class="min-w-0 xl:col-span-1">
                        <label
                            for="source_of_fund"
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Source of Fund
                        </label>

                        <select
                            id="source_of_fund"
                            name="source_of_fund"
                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"
                        >
                            @foreach ([
                                'Plantilla',
                                'MOOE/GMS',
                                'LGU Funds',
                                'LGU SEFs',
                                'Program Support Funds',
                            ] as $fund)
                                <option
                                    value="{{ $fund }}"
                                    @selected(
                                        old(
                                            'source_of_fund',
                                            $record->source_of_fund
                                        ) === $fund
                                    )
                                >
                                    {{ $fund }}
                                </option>
                            @endforeach
                        </select>

                        @error('source_of_fund')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- =================================================
                        MONTHLY SALARY — 25%
                    ================================================== --}}
                    <div class="min-w-0 xl:col-span-1">
                        <label
                            for="monthly_salary"
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Monthly Salary
                        </label>

                        <input
                            id="monthly_salary"
                            type="number"
                            step="0.01"
                            min="0"
                            name="monthly_salary"
                            value="{{ old(
                                'monthly_salary',
                                $record->monthly_salary
                            ) }}"
                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"
                        >

                        @error('monthly_salary')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- =================================================
                        CONTRACT DURATION — 25%
                    ================================================== --}}
                    <div class="min-w-0 xl:col-span-1">
                        <label
                            for="contract_duration"
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Contract Duration
                        </label>

                        <input
                            id="contract_duration"
                            type="text"
                            name="contract_duration"
                            value="{{ old(
                                'contract_duration',
                                $record->contract_duration
                            ) }}"
                            placeholder="Example: Jan 2026 - Dec 2026"
                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"
                        >

                        @error('contract_duration')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>
            </div>


            {{-- =================================================
                CURRENT ASSIGNMENT SUMMARY
            ================================================== --}}
            <div class="mb-6 min-w-0 rounded-2xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-200 bg-gray-50 px-4 sm:px-6 py-4">

                    <h3 class="font-bold text-gray-900">
                        Current Assignment Summary
                    </h3>

                    <p class="mt-1 text-xs text-gray-500">
                        Quick reference of the employee's current assignment.
                    </p>

                </div>


                <div class="grid grid-cols-1 gap-5 p-4 sm:p-6 md:grid-cols-2 xl:grid-cols-4 [&>div]:min-w-0">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            School 
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-900 break-words">
                            {{ $record->school?->school_id ?? '—' }} - {{ $record->school?->school_name ?? '—' }}
                        </p>

                    </div>


                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            District
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-900 break-words">
                            {{ $record->school?->school_district ?? '—' }}
                        </p>

                    </div>


                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Position
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-900 break-words">
                            {{ $record->plantilla?->position_title ?? '—' }}
                        </p>

                    </div>


                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Plantilla Item
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-900 break-words">
                            {{ $record->plantilla?->item_number ?? '—' }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- =================================================
                SAVE BUTTON
            ================================================== --}}
            <div
                class="relative flex flex-col gap-3 sm:sticky sm:bottom-0 sm:flex-row sm:justify-end border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 shadow-lg backdrop-blur"
            >

                <a
                    href="{{ route(
                        'data-management.employment-status'
                    ) }}"
                    class="inline-flex min-h-11 w-full items-center justify-center text-center sm:w-auto rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="inline-flex min-h-11 w-full items-center justify-center text-center sm:w-auto rounded-lg bg-green-700 px-4 sm:px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-green-800"
                >
                    Save Changes
                </button>

            </div>

        </form>

    </div>

    {{-- These controls are initialized only by this page. --}}
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>
    <script>
    (() => {
        function init() {
            const select = document.getElementById('item_number');
            const box = document.getElementById('plantilla-assignment-remarks');
            const message = document.getElementById('plantilla-assignment-text');

            if (!select || !box || !message) return;

            // Prevent duplicate initialization.
            if (select.dataset.plantillaInitialized === 'true') return;
            select.dataset.plantillaInitialized = 'true';

            const searchUrl = {{ Illuminate\Support\Js::from(
                route('data-management.employment-status.plantilla-search', $record->id)
            ) }};

            const assignmentUrl = {{ Illuminate\Support\Js::from(
                route('data-management.employment-status.plantilla-assignments', $record->id)
            ) }};

            const initialValue = select.value;

            // Preserve the preloaded current item and search option.
            let emptyOption = Array.from(select.options)
                .find(option => option.value === '');

            if (!emptyOption) {
                emptyOption = new Option(
                    'Search a plantilla item number',
                    ''
                );
                select.insertBefore(emptyOption, select.firstChild);
            }

            emptyOption.textContent = 'Search a plantilla item number';
            select.value = initialValue;

            const initialOptions = Array.from(select.options).map(option => ({
                value: option.value,
                text: option.textContent.trim()
            }));

            // Reuse the guide if it already exists.
            let guide = document.getElementById('plantilla-search-guide');

            if (!guide) {
                guide = document.createElement('p');
                guide.id = 'plantilla-search-guide';
                select.insertAdjacentElement('afterend', guide);
            }

            guide.textContent =
                'Enter the position code and 6-digit number, '
                + 'for example TCH1-123456 or MTCHR2-123456';

            guide.style.cssText = `
                margin: 8px 0 0;
                padding: 12px 16px;
                background: #f0fdf4;
                border: 1px solid #bbf7d0;
                border-radius: 8px;
                color: #166534;
                font-size: 16px;
                line-height: 1.5;
                white-space: nowrap;
                overflow-x: auto;
            `;

            const describedBy = new Set(
                (select.getAttribute('aria-describedby') || '')
                    .split(/\s+/)
                    .filter(Boolean)
            );

            describedBy.add(guide.id);
            select.setAttribute(
                'aria-describedby',
                Array.from(describedBy).join(' ')
            );

            // Search errors are separate from assignment remarks.
            const searchError = document.createElement('p');
            searchError.setAttribute('role', 'status');
            searchError.style.cssText =
                'margin-top:8px;font-size:14px;color:#b91c1c;';
            searchError.hidden = true;
            guide.insertAdjacentElement('afterend', searchError);

            const searchCache = new Map();
            const assignmentCache = new Map();

            let assignmentRequest;
            let assignmentVersion = 0;
            let searchRequest;
            let searchVersion = 0;

            function cachePut(cache, key, data) {
                cache.delete(key);

                if (cache.size >= 100) {
                    cache.delete(cache.keys().next().value);
                }

                cache.set(key, {
                    data,
                    expires: Date.now() + 60000
                });
            }

            function cacheGet(cache, key) {
                const entry = cache.get(key);

                if (entry && entry.expires > Date.now()) {
                    return entry.data;
                }

                cache.delete(key);
                return null;
            }

            function display(text, state = 'neutral') {
                message.textContent = text;

                const colors = state === 'warning'
                    ? ['#fffbeb', '#f59e0b', '#92400e']
                    : state === 'clear'
                        ? ['#f0fdf4', '#86efac', '#166534']
                        : ['#f9fafb', '#e5e7eb', '#374151'];

                box.style.backgroundColor = colors[0];
                box.style.borderColor = colors[1];
                message.style.color = colors[2];
            }

            function updateGuide(value) {
                const searching = String(value ?? '') === '';

                guide.hidden = !searching;
                box.hidden = searching;
            }

            async function getJson(url, signal) {
                const response = await fetch(url, {
                    signal,
                    credentials: 'same-origin',
                    headers: {
                        Accept: 'application/json'
                    },
                    cache: 'no-store'
                });

                if (!response.ok) {
                    throw new Error('Request failed');
                }

                return response.json();
            }

            async function showAssignments(value) {
                const version = ++assignmentVersion;
                assignmentRequest?.abort();

                updateGuide(value);

                if (!value) {
                    message.textContent = '';
                    return;
                }

                display('Checking assigned employees...');

                try {
                    let data = cacheGet(assignmentCache, value);

                    if (!data) {
                        assignmentRequest = new AbortController();

                        const url = new URL(
                            assignmentUrl,
                            window.location.origin
                        );

                        url.searchParams.set('item_number', value);

                        data = await getJson(
                            url,
                            assignmentRequest.signal
                        );

                        cachePut(assignmentCache, value, data);
                    }

                    if (version !== assignmentVersion) return;

                    display(
                        data.names.length
                            ? 'Warning: this item is already assigned to: '
                                + data.names.join('; ')
                            : 'This item is not currently assigned to another employee.',
                        data.names.length ? 'warning' : 'clear'
                    );
                } catch (error) {
                    if (
                        error.name !== 'AbortError'
                        && version === assignmentVersion
                    ) {
                        display(
                            'Unable to check assignments. '
                            + 'Select the item again to retry.'
                        );
                    }
                }
            }

            updateGuide(initialValue);

            if (typeof window.TomSelect === 'undefined') {
                searchError.textContent =
                    'Search could not load. Refresh the page to try again.';
                searchError.hidden = false;
                return;
            }

            // This page owns initialization of these controls.
            if (select.tomselect) {
                select.tomselect.destroy();
            }

            const control = new TomSelect(select, {
                options: initialOptions,
                items: [initialValue],
                create: false,
                maxItems: 1,
                maxOptions: 40,
                allowEmptyOption: true,
                hideSelected: false,
                loadThrottle: 300,
                searchField: [],
                preload: false,
                placeholder: 'Enter item code, e.g. TCH1-123456',

                shouldLoad(query) {
                    return /^[A-Z]+\d*-\d{6}$/i.test(query.trim());
                },

                onType(query) {
                    ++searchVersion;
                    searchRequest?.abort();
                    searchError.hidden = true;

                    // Remove previous search results, retaining the selection.
                    this.clearOptions();
                    this.loadedSearches = {};

                    // With no search text, show the original choices again.
                    if (query.trim() === '') {
                        this.addOptions(initialOptions);
                    }

                    this.refreshOptions(false);
                },

                load(query, callback) {
                    const version = searchVersion;
                    const key = query.trim().toUpperCase();
                    const cached = cacheGet(searchCache, key);

                    if (cached) {
                        callback(cached);
                        return;
                    }

                    searchRequest = new AbortController();

                    const url = new URL(
                        searchUrl,
                        window.location.origin
                    );

                    url.searchParams.set('q', key);

                    getJson(url, searchRequest.signal)
                        .then(data => {
                            cachePut(searchCache, key, data);
                            callback(
                                version === searchVersion ? data : []
                            );
                        })
                        .catch(error => {
                            callback();

                            if (
                                error.name !== 'AbortError'
                                && version === searchVersion
                            ) {
                                searchError.textContent =
                                    'Search failed. Check your connection '
                                    + 'and type again.';
                                searchError.hidden = false;
                            }
                        });
                },

                onChange(value) {
                    searchError.hidden = true;
                    showAssignments(value);

                    if (value === '') {
                        // Allow typing immediately after choosing Search.
                        this.setTextboxValue('');
                        this.focus();
                    }
                },

                render: {
                    // The guide is below the field, not inside the list.
                    not_loading() {
                        return '<div style="display:none"></div>';
                    },

                    no_results() {
                        return '<div style="padding:12px 16px;font-size:16px;">'
                            + 'No matching items found.'
                            + '</div>';
                    }
                }
            });

            showAssignments(control.getValue());

            const school = document.getElementById('school_id');

            if (school && !school.tomselect) {
                new TomSelect(school, {
                    create: false,
                    allowEmptyOption: true,
                    maxOptions: 50,
                    placeholder: 'Search school...'
                });
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })();
    </script>
</x-app-layout>