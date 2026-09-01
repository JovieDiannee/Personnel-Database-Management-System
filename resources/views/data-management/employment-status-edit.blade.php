<x-app-layout>

    <div class="mx-auto max-w-7xl px-6 py-8">

        {{-- =====================================================
            BREADCRUMB TRAIL
        ====================================================== --}}
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


                {{-- Employment Status --}}
                <a
                    href="{{ route('data-management.employment-status') }}"
                    class="font-medium text-gray-500
                           transition hover:text-green-700"
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
                    Employee Employment Profile
                </span>

            </nav>

        </div>


        {{-- =====================================================
            SUCCESS MESSAGE
        ====================================================== --}}
        @if(session('success'))

            <div
                class="mb-6 flex items-center gap-3 rounded-xl
                       border border-green-300 bg-green-100
                       px-5 py-4
                       text-sm font-medium text-green-900"
            >

                {{-- SUCCESS ICON --}}
                <div
                    class="flex h-9 w-9 shrink-0 items-center justify-center
                           rounded-full bg-green-600 text-white"
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
                class="mb-6 rounded-xl border border-red-200
                       bg-red-50 px-5 py-4
                       text-sm text-red-700"
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

        <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex items-center gap-4">

                <div
                    class="flex h-14 w-14 items-center justify-center
                           rounded-full bg-green-100
                           text-xl font-bold text-green-700"
                >
                    {{ strtoupper(substr($basic?->first_name ?? '?', 0, 1)) }}
                    {{ strtoupper(substr($basic?->last_name ?? '?', 0, 1)) }}
                </div>


                <div>

                    <h2 class="text-xl font-bold text-gray-900">
                        {{ $basic?->last_name ?? '—' }},
                        {{ $basic?->first_name ?? '—' }}
                        {{ $basic?->middle_name ?? '' }}
                        {{ $basic?->extension_name ?? '' }}
                    </h2>

                    <p class="text-sm text-gray-500">
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
                class="mb-6 rounded-xl border border-red-200
                       bg-red-50 px-5 py-4"
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
            <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">

                    <h3 class="font-bold text-gray-900">
                        Employment Information
                    </h3>

                    <p class="mt-1 text-xs text-gray-500">
                        Current employment, assignment, plantilla and salary details.
                    </p>

                </div>


                <div class="grid gap-5 p-6 md:grid-cols-2 lg:grid-cols-4">


                    {{-- =================================================
                        PLANTILLA ITEM NUMBER
                    ================================================== --}}
                    <div class="md:col-span-2 lg:col-span-6">

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Plantilla Item Number
                        </label>

                        <select
                            name="item_number"
                            class="w-full rounded-lg border-gray-300
                                focus:border-green-600
                                focus:ring-green-600"
                        >


                            @foreach($plantillaItems as $item)

                                <option
                                    value="{{ $item->item_number }}"
                                    @selected(
                                        old(
                                            'item_number',
                                            $record->plantilla?->item_number
                                        ) === $item->item_number
                                    )
                                >
                                    {{ $item->item_number }}
                                    - {{ $item->position_title }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- =================================================
                        SCHOOL
                    ================================================== --}}
                    <div class="md:col-span-2 lg:col-span-6">

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            School
                        </label>

                        <select
                            name="school_id"
                            class="w-full rounded-lg border-gray-300
                                focus:border-green-600
                                focus:ring-green-600"
                        >


                            @foreach($schools as $school)

                                <option
                                    value="{{ $school->school_id }}"
                                    @selected(
                                        old(
                                            'school_id',
                                            $record->school?->school_id
                                        ) === $school->school_id
                                    )
                                >
                                    {{ $school->school_id }}
                                    - {{ $school->school_name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- =================================================
                        DATE OF ORIGINAL APPOINTMENT
                    ================================================== --}}
                    <div class="lg:col-span-3">

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Date of Original Appointment
                        </label>

                        <input
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
                            class="w-full rounded-lg border-gray-300
                                focus:border-green-600
                                focus:ring-green-600"
                        >

                    </div>


                    {{-- =================================================
                        DATE OF LAST PROMOTION
                    ================================================== --}}
                    <div class="lg:col-span-3">

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Date of Last Promotion
                        </label>

                        <input
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
                            class="w-full rounded-lg border-gray-300
                                focus:border-green-600
                                focus:ring-green-600"
                        >

                    </div>


                    {{-- =================================================
                        EMPLOYMENT STATUS
                    ================================================== --}}
                    <div>

                        <label
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
                            name="employment_status"
                            class="w-full rounded-lg border-gray-300
                                focus:border-green-600
                                focus:ring-green-600"
                        >

                            @foreach($employmentStatuses as $status)

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

                    </div>


                    {{-- =================================================
                        WARM BODY STATUS
                    ================================================== --}}
                    {{-- WARM BODY STATUS --}}
                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Warm Body Status
                        </label>

                        <select
                            name="warm_body_status"
                            class="w-full rounded-lg border-gray-300
                                focus:border-green-600
                                focus:ring-green-600"
                        >

                            @foreach([
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

                    </div>


                    {{-- =================================================
                        NATURE OF WORK
                    ================================================== --}}
                    <div>

                        <label
                            for="nature_of_work"
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Nature of Work
                        </label>

                        <select
                            id="nature_of_work"
                            name="nature_of_work"
                            class="w-full rounded-lg border-gray-300
                                focus:border-green-600
                                focus:ring-green-600"
                        >
                           

                            @foreach([
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
                                        old('nature_of_work', $record->nature_of_work) === $nature
                                    )
                                >
                                    {{ $nature }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- =================================================
                        SOURCE OF FUND
                    ================================================== --}}
                    <div>

                        <label
                            for="source_of_fund"
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Source of Fund
                        </label>

                        <select
                            id="source_of_fund"
                            name="source_of_fund"
                            class="w-full rounded-lg border-gray-300
                                focus:border-green-600
                                focus:ring-green-600"
                        >
                           

                            @foreach([
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

                    </div>


                    {{-- =================================================
                        MONTHLY SALARY
                    ================================================== --}}
                    <div>

                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Monthly Salary
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="monthly_salary"
                            value="{{ old(
                                'monthly_salary',
                                $record->monthly_salary
                            ) }}"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-green-600
                                   focus:ring-green-600"
                        >

                    </div>


                    {{-- =================================================
                        CONTRACT DURATION
                    ================================================== --}}
                    <div class="lg:col-span-3">

                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Contract Duration
                        </label>

                        <input
                            type="text"
                            name="contract_duration"
                            value="{{ old(
                                'contract_duration',
                                $record->contract_duration
                            ) }}"
                            placeholder="Example: January 2026 - December 2026"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-green-600
                                   focus:ring-green-600"
                        >

                    </div>

                </div>

            </div>


            {{-- =================================================
                CURRENT ASSIGNMENT SUMMARY
            ================================================== --}}
            <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">

                    <h3 class="font-bold text-gray-900">
                        Current Assignment Summary
                    </h3>

                    <p class="mt-1 text-xs text-gray-500">
                        Quick reference of the employee's current assignment.
                    </p>

                </div>


                <div class="grid gap-5 p-6 md:grid-cols-2 lg:grid-cols-4">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            School 
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-900">
                            {{ $record->school?->school_id ?? '—' }} - {{ $record->school?->school_name ?? '—' }}
                        </p>

                    </div>


                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            District
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-900">
                            {{ $record->school?->school_name ?? '—' }}
                        </p>

                    </div>


                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Position
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-900">
                            {{ $record->plantilla?->position_title ?? '—' }}
                        </p>

                    </div>


                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Plantilla Item
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-900">
                            {{ $record->plantilla?->item_number ?? '—' }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- =================================================
                SAVE BUTTON
            ================================================== --}}
            <div
                class="sticky bottom-0
                       flex justify-end gap-3
                       border-t border-gray-200
                       bg-white/95 px-6 py-4
                       shadow-lg backdrop-blur"
            >

                <a
                    href="{{ route(
                        'data-management.employment-status'
                    ) }}"
                    class="rounded-lg border border-gray-300
                           bg-white px-5 py-2.5
                           text-sm font-semibold text-gray-700
                           hover:bg-gray-50"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="rounded-lg bg-green-700
                           px-6 py-2.5
                           text-sm font-semibold text-white
                           shadow-sm
                           transition hover:bg-green-800"
                >
                    Save Changes
                </button>

            </div>

        </form>

    </div>

</x-app-layout>