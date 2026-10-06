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

                {{-- Personnel Information --}}

                <a

                    href="{{ route('data-management.personnel') }}"

                    class="font-medium text-gray-500 transition hover:text-green-700"

                >

                    Personnel Information

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

        {{-- SUCCESS MESSAGE --}}

        @if(session('success'))

            <div

                class="mb-6 flex items-center gap-3 rounded-xl border border-green-300 bg-green-100 px-5 py-4 text-sm font-medium text-green-900"

            >

                {{-- SUCCESS ICON --}}

                <div

                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-green-600 text-white"

                >

                    <svg

                        xmlns="http\://www\.w3.org/2000/svg"

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

        {{-- VALIDATION ERRORS --}}

        @if ($errors->any())

            <div

                role="alert"

                class="mb-6 rounded-xl border border-red-300 bg-red-100 px-5 py-4 text-sm text-red-900 break-words"

            >

                <p class="font-bold">

                    Please check the following fields:

                </p>

                <ul class="mt-2 list-inside list-disc space-y-1">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        {{-- =====================================================

        PERSONNEL SUMMARY

        ====================================================== --}}

        <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm">

            <div class="flex min-w-0 items-start gap-3 sm:items-center sm:gap-4 [&>div]:min-w-0">

                <div

                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-green-100 text-xl font-bold text-green-700"

                >

                    {{ strtoupper(substr($person->first_name, 0, 1)) }}

                    {{ strtoupper(substr($person->last_name, 0, 1)) }}

                </div>

                <div>

                    <h2 class="break-words text-lg font-bold text-gray-900 sm:text-xl">

                        {{ $person->last_name }},

                        {{ $person->first_name }}

                        {{ $person->middle_name }}

                        {{ $person->extension_name }}

                    </h2>

                    <p class="text-sm text-gray-500 break-words">

                        {{ $person->user?->email ?? 'No email address' }}

                    </p>

                </div>

            </div>

        </div>

        {{-- =====================================================

        FORM

        ====================================================== --}}

        <form

            method="POST"

            action="{{ route('data-management.personnel.update', $person->id) }}"

            >

            @csrf

            @method('PUT')

            {{-- =================================================

            ACCOUNT INFORMATION

            ================================================== --}}

            <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-200 bg-gray-50 px-4 sm:px-6 py-4">

                    <h3 class="font-bold text-gray-900">

                        Account Information

                    </h3>

                    <p class="mt-1 text-xs text-gray-500">

                        Personnel account and employee identification.

                    </p>

                </div>

                <div class="grid min-w-0 grid-cols-1 gap-4 sm:gap-5 p-4 sm:p-6 md:grid-cols-2 [&>div]:min-w-0">

                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">

                            Email Address

                        </label>

                        <input

                            type="email"

                            name="email"

                            value="{{ old('email', $person->user?->email) }}"

                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"

                        >

                    </div>

                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">

                            Employee ID

                        </label>

                        <input

                            type="text"

                            name="employee_id"

                            value="{{ old('employee_id', $person->issuedId?->employee_id) }}"

                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"

                        >

                    </div>

                </div>

            </div>

            {{-- =================================================

            BASIC INFORMATION

            ================================================== --}}

            <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-200 bg-gray-50 px-4 sm:px-6 py-4">

                    <h3 class="font-bold text-gray-900">

                        Basic Information

                    </h3>

                    <p class="mt-1 text-xs text-gray-500">

                        Personal and demographic information.

                    </p>

                </div>

                <div class="grid min-w-0 grid-cols-1 gap-4 sm:gap-5 p-4 sm:p-6 md:grid-cols-2 xl:grid-cols-4 [&>div]:min-w-0">

                    {{-- FIRST NAME --}}

                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">

                            First Name

                        </label>

                        <input

                            type="text"

                            name="first_name"

                            value="{{ old('first_name', $person->first_name) }}"

                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"

                        >

                    </div>

                    {{-- MIDDLE NAME --}}

                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">

                            Middle Name

                        </label>

                        <input

                            type="text"

                            name="middle_name"

                            value="{{ old('middle_name', $person->middle_name) }}"

                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"

                        >

                    </div>

                    {{-- LAST NAME --}}

                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">

                            Last Name

                        </label>

                        <input

                            type="text"

                            name="last_name"

                            value="{{ old('last_name', $person->last_name) }}"

                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"

                        >

                    </div>

                    {{-- EXTENSION --}}

                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">

                            Extension Name

                        </label>

                        <input

                            type="text"

                            name="extension_name"

                            value="{{ old('extension_name', $person->extension_name) }}"

                            placeholder="Jr., Sr., III"

                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"

                        >

                    </div>

                    {{-- SEX --}}

                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">

                            Sex

                        </label>

                        <select

                            name="sex"

                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"

                        >

                            <option

                                value="Male"

                                @selected(old('sex', $person->sex) === 'Male')

                            >

                                Male

                            </option>

                            <option

                                value="Female"

                                @selected(old('sex', $person->sex) === 'Female')

                            >

                                Female

                            </option>

                        </select>

                    </div>

                    {{-- BIRTH DATE --}}
                    <div>

                        <label
                            for="birth_date"
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Birth Date
                        </label>

                        <input
                            id="birth_date"
                            type="date"
                            name="birth_date"
                            value="{{ old(
                                'birth_date',
                                $person->birth_date
                                    ? \Carbon\Carbon::parse($person->birth_date)->format('Y-m-d')
                                    : ''
                            ) }}"
                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"
                        >

                        @error('birth_date')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- BIRTH PLACE --}}

                    <div class="xl:col-span-2">

                        <label class="mb-1 block text-sm font-medium text-gray-700">

                            Birth Place

                        </label>

                        <input

                            type="text"

                            name="birth_place"

                            value="{{ old('birth_place', $person->birth_place) }}"

                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"

                        >

                    </div>

                    {{-- =================================================
                        CIVIL STATUS
                    ================================================== --}}
                    <div>

                        <label
                            for="civil_status"
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Civil Status
                        </label>

                        @php
                            $civilStatuses = [
                                'Single',
                                'Married',
                                'Widowed',
                                'Separated',
                                'Others',
                            ];
                        @endphp

                        <select
                            id="civil_status"
                            name="civil_status"
                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"
                        >

                            <option value="">
                                Select Civil Status
                            </option>

                            @foreach ($civilStatuses as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        old(
                                            'civil_status',
                                            $person->civil_status
                                        ) === $status
                                    )
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                        @error('civil_status')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- RELIGION --}}

                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">

                            Religion

                        </label>

                        <input

                            type="text"

                            name="religion"

                            value="{{ old('religion', $person->religion) }}"

                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"

                        >

                    </div>

                    {{-- CITIZENSHIP --}}

                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">

                            Citizenship

                        </label>

                        <input

                            type="text"

                            name="citizenship"

                            value="{{ old('citizenship', $person->citizenship) }}"

                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"

                        >

                    </div>

                    {{-- =================================================
                        CITIZENSHIP MODE
                    ================================================== --}}
                    <div>

                        <label
                            for="citizenship_mode"
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Citizenship Mode
                        </label>

                        @php
                            $citizenshipModes = [
                                'By Birth',
                                'By Naturalization',
                            ];
                        @endphp

                        <select
                            id="citizenship_mode"
                            name="citizenship_mode"
                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"
                        >

                            <option value="">
                                Select Citizenship Mode
                            </option>

                            @foreach ($citizenshipModes as $mode)

                                <option
                                    value="{{ $mode }}"
                                    @selected(
                                        old(
                                            'citizenship_mode',
                                            $person->citizenship_mode
                                        ) === $mode
                                    )
                                >
                                    {{ $mode }}
                                </option>

                            @endforeach

                        </select>

                        @error('citizenship_mode')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- BLOOD TYPE --}}

                    <div>

                        <label for="blood_type" class="mb-1 block text-sm font-medium text-gray-700">

                            Blood Type

                        </label>

                        <select

                            id="blood_type"

                            name="blood_type"

                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"

                        >

                            @foreach ([

                                'A+',

                                'A-',

                                'B+',

                                'B-',

                                'AB+',

                                'AB-',

                                'O+',

                                'O-',

                                'Unknown',

                            ] as $bloodType)

                                <option

                                    value="{{ $bloodType }}"

                                    @selected(old('blood_type', $person->blood_type) === $bloodType)

                                >

                                    {{ $bloodType }}

                                </option>

                            @endforeach

                        </select>

                        @error('blood_type')

                            <p class="mt-1 text-sm text-red-600 break-words">{{ $message }}</p>

                        @enderror

                    </div>

                    {{-- HEIGHT --}}

                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">

                            Height (m)

                        </label>

                        <input

                            type="number"

                            step="0.01"

                            name="height_m"

                            value="{{ old('height_m', $person->height_m) }}"

                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"

                        >

                    </div>

                    {{-- WEIGHT --}}

                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">

                            Weight (kg)

                        </label>

                        <input

                            type="number"

                            step="0.01"

                            name="weight_kg"

                            value="{{ old('weight_kg', $person->weight_kg) }}"

                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"

                        >

                    </div>                    

                    {{-- SPECIALIZATION --}}

                    <div>

                        <label for="specialization" class="mb-1 block text-sm font-medium text-gray-700">

                            Specialization

                        </label>

                        <select

                            id="specialization"

                            name="specialization"

                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"

                        >

                            @foreach ([

                                'Early Childhood Education',

                                'English',

                                'Filipino',

                                'General Education',

                                'Information and Communication Technology (ICT)',

                                'Mathematics',

                                'Music, Arts, PE, and Health (MAPEH)',

                                'Science - Biology',

                                'Science - Chemistry',

                                'Science - General Science',

                                'Science - Physical Science',

                                'Science - Physics',

                                'Social Studies / Social Sciences',

                                'Special Education (SPED)',

                                'Technology and Livelihood Education (TLE)',

                                'TVL - Agricultural Crops Production',

                                'TVL - Agroentrepreneurship',

                                'TVL - Animal Production',

                                'TVL - Bartending',

                                'TVL - Beauty Care Services',

                                'TVL - Bookkeeping',

                                'TVL - Bread and Pastry Production',

                                'TVL - Caregiving',

                                'TVL - Carpentry',

                                'TVL - Computer Systems Servicing',

                                'TVL - Contact Center Services',

                                'TVL - Cookery',

                                'TVL - Creative Web Design',

                                'TVL - Dressmaking',

                                'TVL - Driving',

                                'TVL - Electrical Installation and Maintenance',

                                'TVL - Electronic Products Assembly and Servicing',

                                'TVL - Events Management Services',

                                'TVL - Food and Beverage Services',

                                'TVL - Food Processing',

                                'TVL - Front Office Services',

                                'TVL - Hairdressing',

                                'TVL - Household Services',

                                'TVL - Housekeeping',

                                'TVL - Landscape Installation and Maintenance',

                                'TVL - Masonry',

                                'TVL - Organic Agriculture Production',

                                'TVL - Plumbing',

                                'TVL - RAC Servicing (DomRAC)',

                                'TVL - Rice Machinery Operations',

                                'TVL - Security Services',

                                'TVL - Shielded Metal Arc Welding',

                                'TVL - Technical Drafting',

                                'TVL - Technology and Livelihood Education (TLE)',

                                'TVL - Tour Guiding Services',

                                'TVL - Wellness Massage',

                                'Values Education',

                                'Guidance and Counseling',

                                'Accountancy',

                                'Architecture',

                                'Statistics',

                                'Civil Engineering',

                                'Computer Engineering',

                                'Electrical Engineering',

                                'Electronics Engineering',

                                'Mechanical Engineering',

                                'Agricultural Engineering',

                                'Human Resource Management',

                                'Legal Management',

                                'Management Accounting',

                                'Marketing Management',

                                'Nursing',

                                'Office Administration',

                                'Public Administration',

                                'Business Administration',

                                'Psychology',

                                'Criminology',

                                'Commerce',

                                'Other',

                                'Not Applicable',

                            ] as $specialization)

                                <option

                                    value="{{ $specialization }}"

                                    @selected(old('specialization', $person->specialization) === $specialization)

                                >

                                    {{ $specialization }}

                                </option>

                            @endforeach

                        </select>

                        @error('specialization')

                            <p class="mt-1 text-sm text-red-600 break-words">{{ $message }}</p>

                        @enderror

                    </div>

                    {{-- MOBILE --}}

                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">

                            Mobile Number

                        </label>

                        <input

                            type="text"

                            name="mobile_number"

                            value="{{ old(

                                'mobile_number',

                                $person->mobile_number

                            ) }}"

                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"

                        >

                    </div>

                    {{-- TELEPHONE --}}

                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">

                            Telephone Number

                        </label>

                        <input

                            type="text"

                            name="telephone_number"

                            value="{{ old(

                                'telephone_number',

                                $person->telephone_number

                            ) }}"

                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"

                        >

                    </div>

                </div>

            </div>

            {{-- =================================================

            GOVERNMENT IDs

            ================================================== --}}

            <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm">

                <h3 class="mb-4 font-bold text-gray-900">Government IDs</h3>

                <div class="grid min-w-0 grid-cols-1 gap-4 sm:gap-5 md:grid-cols-2 xl:grid-cols-3 [&>div]:min-w-0">

                    @foreach ([

                        'umid_no' => 'UMID Number',

                        'gsis_no' => 'GSIS Number',

                        'philsys_no' => 'PhilSys Number',

                        'pagibig_no' => 'Pag-IBIG Number',

                        'tin_no' => 'TIN',

                        'philhealth_no' => 'PhilHealth Number',

                    ] as $field => $label)

                        <div>

                            <label for="{{ $field }}"

                                class="mb-1 block text-sm font-medium text-gray-700">

                                {{ $label }}

                            </label>

                            <input

                                id="{{ $field }}"

                                type="text"

                                name="{{ $field }}"

                                value="{{ old($field, data_get($person->issuedId, $field)) }}"

                                class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"

                            >

                            @error($field)

                                <p class="mt-1 text-sm text-red-600 break-words">{{ $message }}</p>

                            @enderror

                        </div>

                    @endforeach

                </div>

            </div>

            {{-- =================================================

            ADDRESS

            ================================================== --}}

            @php

                $addressLabels = [

                    'street' => 'Street',

                    'brgy' => 'Barangay',

                    'subd_village' => 'Subdivision / Village',

                    'municipality_city' => 'Municipality / City',

                    'province' => 'Province',

                    'zip_postal' => 'ZIP / Postal Code',

                ];

            @endphp

            @forelse ($addresses as $address)

                @php

                    $addressKey = $address->id;

                @endphp

                <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm">

                    <h3 class="mb-4 font-bold text-gray-900">

                        Home Address

                    </h3>

                    {{-- Keep this hidden ID to identify the address being updated. --}}

                    <input

                        type="hidden"

                        name="addresses[{{ $addressKey }}][id]"

                        value="{{ $address->id }}"

                    >

                    @error("addresses.$addressKey.id")

                        <p class="mb-3 text-sm text-red-600 break-words">{{ $message }}</p>

                    @enderror

                    <div class="grid min-w-0 grid-cols-1 gap-4 sm:gap-5 md:grid-cols-2 xl:grid-cols-4 [&>div]:min-w-0">

                        {{-- ADDRESS TYPE --}}

                        <div>

                            <label

                                for="address_{{ $addressKey }}_type"

                                class="mb-1 block text-sm font-medium text-gray-700"

                            >

                                Address Type

                            </label>

                            <select

                                id="address_{{ $addressKey }}_type"

                                name="addresses[{{ $addressKey }}][type]"

                                required

                                class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"

                            >

                                @foreach ([

                                    'permanent' => 'Permanent',

                                    'residential' => 'Residential',

                                ] as $value => $label)

                                    <option

                                        value="{{ $value }}"

                                        @selected(

                                            old(

                                                'addresses.'.$addressKey.'.type',

                                                $address->type

                                            ) === $value

                                        )

                                    >

                                        {{ $label }}

                                    </option>

                                @endforeach

                            </select>

                            @error("addresses.$addressKey.type")

                                <p class="mt-1 text-sm text-red-600 break-words">{{ $message }}</p>

                            @enderror

                        </div>

                        {{-- OTHER ADDRESS FIELDS --}}

                        @foreach ($addressLabels as $field => $label)

                            <div>

                                <label

                                    for="address_{{ $addressKey }}_{{ $field }}"

                                    class="mb-1 block text-sm font-medium text-gray-700"

                                >

                                    {{ $label }}

                                </label>

                                <input

                                    id="address_{{ $addressKey }}_{{ $field }}"

                                    type="text"

                                    name="addresses[{{ $addressKey }}][{{ $field }}]"

                                    value="{{ old(

                                        'addresses.'.$addressKey.'.'.$field,

                                        data_get($address, $field)

                                    ) }}"

                                    class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"

                                >

                                @error("addresses.$addressKey.$field")

                                    <p class="mt-1 text-sm text-red-600 break-words">{{ $message }}</p>

                                @enderror

                            </div>

                        @endforeach

                    </div>

                </div>

            @empty

                <p class="mb-6 text-sm text-gray-500">

                    No address is currently recorded for this person.

                </p>

            @endforelse

            @if ($record)
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
                            <span
                                id="plantilla-required-marker"
                                class="text-red-600"
                                @if(old('source_of_fund', $record->source_of_fund) !== 'Plantilla') hidden @endif
                                aria-hidden="true"
                            >*</span>
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
                            Enter the 6-digit item number, for example 540126. You can also paste the full item number.
                        </p>

                        <select
                            id="item_number"
                            name="item_number"
                            @required(old('source_of_fund', $record->source_of_fund) === 'Plantilla')
                            aria-required="{{ old('source_of_fund', $record->source_of_fund) === 'Plantilla' ? 'true' : 'false' }}"
                            class="employment-search-select w-full min-w-0"
                            data-placeholder="Search by item number or position title..."
                            aria-describedby="plantilla-requirement-help plantilla-assignment-remarks"
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

                        <p id="plantilla-requirement-help" class="mt-2 text-sm text-gray-600">
                            {{ old('source_of_fund', $record->source_of_fund) === 'Plantilla'
                                ? 'Required when Source of Fund is Plantilla.'
                                : 'Optional for this Source of Fund.' }}
                        </p>

                        @error('item_number')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- =====================================================
                        PERSONNEL ASSIGNMENT
                    ====================================================== --}}

                    @if(in_array(auth()->user()?->role, ['super_admin', 'admin']))

                        <div class="min-w-0 md:col-span-2 xl:col-span-4">

                            <div class="rounded-xl border border-green-200 bg-green-50/30 p-4">

                                {{-- =====================================================
                                    HEADER
                                ====================================================== --}}

                                <div class="mb-4 flex items-start gap-3 border-b border-green-100 pb-3">

                                    <div
                                        class="flex h-9 w-9 shrink-0 items-center justify-center
                                            rounded-lg bg-green-100 text-green-700"
                                    >
                                        <svg
                                            class="h-5 w-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857
                                                M17 20H7
                                                M17 20v-2c0-.656-.126-1.283-.356-1.857
                                                M7 20H2v-2a3 3 0 015.356-1.857
                                                M7 20v-2c0-.656.126-1.283.356-1.857
                                                m0 0a5.002 5.002 0 019.288 0
                                                M15 7a3 3 0 11-6 0
                                                3 3 0 016 0z"
                                            />
                                        </svg>
                                    </div>

                                    <div class="min-w-0">

                                        <h3 class="text-sm font-bold text-gray-900">
                                            Personnel Assignment
                                        </h3>

                                        <p class="mt-0.5 text-xs leading-5 text-gray-500">

                                            @if(auth()->user()?->role === 'super_admin')

                                                Select whether the personnel is assigned to a
                                                school or to a Division Office unit.

                                            @else

                                                Select the school where the personnel is currently assigned.

                                            @endif

                                        </p>

                                    </div>

                                </div>

                                {{-- =====================================================
                                    SUPER ADMIN
                                ====================================================== --}}

                                @if(auth()->user()?->role === 'super_admin')

                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                                        {{-- =============================================
                                            ASSIGNMENT TYPE
                                        ============================================== --}}

                                        <div class="min-w-0">

                                            <label
                                                for="personnel_assignment"
                                                class="mb-1.5 block text-sm font-medium text-gray-700"
                                            >
                                                Assignment Type
                                                <span class="text-red-500">*</span>
                                            </label>

                                            <select
                                                id="personnel_assignment"
                                                name="personnel_assignment"
                                                class="block h-11 w-full rounded-lg
                                                    border-gray-300 bg-white
                                                    px-3 text-sm text-gray-900
                                                    shadow-sm
                                                    focus:border-green-600
                                                    focus:ring-green-600"
                                            >

                                                <option value="">
                                                    Select Assignment Type
                                                </option>

                                                <option
                                                    value="school_based"
                                                    @selected(
                                                        old(
                                                            'personnel_assignment',
                                                            $personnelAssignment
                                                        ) === 'school_based'
                                                    )
                                                >
                                                    School Based
                                                </option>

                                                <option
                                                    value="division_office"
                                                    @selected(
                                                        old(
                                                            'personnel_assignment',
                                                            $personnelAssignment
                                                        ) === 'division_office'
                                                    )
                                                >
                                                    Division Office
                                                </option>

                                            </select>

                                            @error('personnel_assignment')
                                                <p class="mt-1 text-xs text-red-600">
                                                    {{ $message }}
                                                </p>
                                            @enderror

                                        </div>

                                        {{-- =============================================
                                            SCHOOL
                                        ============================================== --}}

                                        <div
                                            id="school-assignment-container"
                                            class="min-w-0"
                                        >

                                            <label
                                                for="school_id"
                                                class="mb-1.5 block text-sm font-medium text-gray-700"
                                            >
                                                School
                                                <span class="text-red-500">*</span>
                                            </label>

                                            <select
                                                id="school_id"
                                                name="school_id"
                                                class="employment-search-select w-full min-w-0"
                                                data-placeholder="Search or select school..."
                                            >

                                                <option value="">
                                                    Select School
                                                </option>

                                                @foreach($schools as $school)

                                                    <option
                                                        value="{{ $school->school_id }}"
                                                        @selected(
                                                            (string) old(
                                                                'school_id',
                                                                $record->school?->school_id
                                                            ) === (string) $school->school_id
                                                        )
                                                    >

                                                        {{ $school->school_id }}
                                                        — {{ $school->school_name }}

                                                        @if($school->school_district)
                                                            — {{ $school->school_district }}
                                                        @endif

                                                    </option>

                                                @endforeach

                                            </select>

                                            @error('school_id')
                                                <p class="mt-1 text-xs text-red-600">
                                                    {{ $message }}
                                                </p>
                                            @enderror

                                        </div>

                                        {{-- =============================================
                                            DIVISION OFFICE
                                        ============================================== --}}

                                        <div
                                            id="office-assignment-container"
                                            class="hidden min-w-0"
                                        >

                                            <label
                                                for="office_unit_id"
                                                class="mb-1.5 block text-sm font-medium text-gray-700"
                                            >
                                                Office Unit
                                                <span class="text-red-500">*</span>
                                            </label>

                                            <select
                                                id="office_unit_id"
                                                name="office_unit_id"
                                                class="employment-search-select w-full min-w-0"
                                                data-placeholder="Search or select office unit..."
                                            >

                                                <option value="">
                                                    Select Office Unit
                                                </option>

                                                @foreach(
                                                    $officeUnits->groupBy('office_group_id')
                                                    as $units
                                                )

                                                    @php
                                                        $officeGroup =
                                                            $units->first()?->officeGroup;
                                                    @endphp

                                                    <optgroup
                                                        label="{{ $officeGroup?->code }} — {{ $officeGroup?->name }}"
                                                    >

                                                        @foreach($units as $officeUnit)

                                                            <option
                                                                value="{{ $officeUnit->id }}"
                                                                @selected(
                                                                    (string) old(
                                                                        'office_unit_id',
                                                                        $record->office_unit_id
                                                                    ) === (string) $officeUnit->id
                                                                )
                                                            >
                                                                {{ $officeUnit->name }}

                                                                @if($officeUnit->parent)
                                                                    — {{ $officeUnit->parent->name }}
                                                                @endif
                                                            </option>

                                                        @endforeach

                                                    </optgroup>

                                                @endforeach

                                            </select>

                                            @error('office_unit_id')
                                                <p class="mt-1 text-xs text-red-600">
                                                    {{ $message }}
                                                </p>
                                            @enderror

                                        </div>

                                    </div>

                                {{-- =====================================================
                                    ADMIN
                                    SCHOOL ASSIGNMENT ONLY
                                ====================================================== --}}

                                @elseif(auth()->user()?->role === 'admin')

                                    <div class="grid grid-cols-1">

                                        <div class="min-w-0">

                                            <label
                                                for="school_id"
                                                class="mb-1.5 block text-sm font-medium text-gray-700"
                                            >
                                                School Assignment
                                                <span class="text-red-500">*</span>
                                            </label>

                                            <select
                                                id="school_id"
                                                name="school_id"
                                                class="employment-search-select w-full min-w-0"
                                                data-placeholder="Search or select school..."
                                                required
                                            >

                                                <option value="">
                                                    Search or select school
                                                </option>

                                                @foreach($schools as $school)

                                                    <option
                                                        value="{{ $school->school_id }}"
                                                        @selected(
                                                            (string) old(
                                                                'school_id',
                                                                $record->school?->school_id
                                                            ) === (string) $school->school_id
                                                        )
                                                    >

                                                        {{ $school->school_id }}
                                                        — {{ $school->school_name }}

                                                        @if($school->school_district)
                                                            — {{ $school->school_district }}
                                                        @endif

                                                    </option>

                                                @endforeach

                                            </select>

                                            <p class="mt-2 text-xs text-gray-500">
                                                Select the new school assignment of this employee.
                                            </p>

                                            @error('school_id')
                                                <p class="mt-1 text-xs text-red-600">
                                                    {{ $message }}
                                                </p>
                                            @enderror

                                        </div>

                                    </div>

                                @endif

                            </div>

                        </div>

                    @endif

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
                            Source of Fund <span class="text-red-600" aria-hidden="true">*</span>
                        </label>

                        <select
                            id="source_of_fund"
                            name="source_of_fund"
                            required
                            aria-required="true"
                            class="min-h-11 w-full min-w-0 max-w-full rounded-lg border-gray-300 text-base sm:text-sm focus:border-green-600 focus:ring-green-600"
                        >
                            <option value="" @selected((string) old('source_of_fund', $record->source_of_fund) === '')>
                                Select Source of Fund
                            </option>
                            @foreach ([
                                'Plantilla',
                                'MOOE/GMS',
                                'LGU Funds',
                                'LGU SEFs',
                                'Program Support Funds',
                                'Others',
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

@else
                <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                    No employment record is available for this personnel.
                </div>
            @endif

            {{-- =================================================

            SAVE BUTTON

            ================================================== --}}

            <div

                class="relative flex flex-col gap-3 sm:sticky sm:bottom-0 sm:flex-row sm:justify-end border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 shadow-lg backdrop-blur"

            >

                <a

                    href="{{ route('data-management.personnel') }}"

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
                    $record ? route('data-management.employment-status.plantilla-search', $record->id) : null
                ) }};
    
                const assignmentUrl = {{ Illuminate\Support\Js::from(
                    $record ? route('data-management.employment-status.plantilla-assignments', $record->id) : null
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
                    'Enter the 6-digit item number, for example 540126. '
                    + 'You can also paste the full item number.';
    
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
    
                // =====================================================
                // LOAD CURRENT PLANTILLA ASSIGNMENT REMARKS ON PAGE LOAD
                // =====================================================
    
                updateGuide(initialValue);
    
                if (initialValue) {
                    showAssignments(initialValue);
                }
    
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
                    placeholder: 'Enter the 6-digit number, e.g. 540126',
    
                    shouldLoad(query) {
                        return /(?:^|\D)(\d{6})(?!\d)/.test(query);
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
                        const match = query.match(/(?:^|\D)(\d{6})(?!\d)/);
                        if (!match) {
                            callback([]);
                            return;
                        }
                        const key = match[1];
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
    
                const school = document.getElementById('school_id');
    
                if (school && !school.tomselect) {
    
                    new TomSelect(school, {
                        create: false,
                        allowEmptyOption: true,
                        maxOptions: 50,
                        placeholder: 'Search or select school...'
                    });
    
                }

                const officeUnit = document.getElementById('office_unit_id');
    
                if (officeUnit && !officeUnit.tomselect) {
    
                    new TomSelect(officeUnit, {
                        create: false,
                        allowEmptyOption: true,
                        maxOptions: 50,
                        placeholder: 'Search or select office unit...'
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
    
        <script>
        document.addEventListener('DOMContentLoaded', function () {
    
            /*
            |--------------------------------------------------------------------------
            | SUPER ADMIN PERSONNEL ASSIGNMENT SWITCHER
            |--------------------------------------------------------------------------
            |
            | Admin users do not have an Assignment Type selector.
            | Therefore this script only runs when the selector exists.
            |
            */
    
            const assignmentType =
                document.getElementById('personnel_assignment');
    
            if (!assignmentType) {
                return;
            }

            const schoolContainer =
                document.getElementById('school-assignment-container');
    
            const officeContainer =
                document.getElementById('office-assignment-container');
    
            const schoolSelect =
                document.getElementById('school_id');
    
            const officeSelect =
                document.getElementById('office_unit_id');

            if (!schoolContainer || !officeContainer) {
                return;
            }

            function updatePersonnelAssignment() {
    
                const type = assignmentType.value;

                /*
                |--------------------------------------------------------------------------
                | SCHOOL BASED
                |--------------------------------------------------------------------------
                */
    
                if (type === 'school_based') {
    
                    schoolContainer.classList.remove('hidden');
                    officeContainer.classList.add('hidden');
    
                    if (schoolSelect) {
                        schoolSelect.disabled = false;
                        schoolSelect.tomselect?.enable();
                        schoolSelect.required = true;
                    }
    
                    if (officeSelect) {
                        officeSelect.disabled = true;
                        officeSelect.tomselect?.disable();
                        officeSelect.required = false;
                    }
    
                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | DIVISION OFFICE
                |--------------------------------------------------------------------------
                */
    
                if (type === 'division_office') {
    
                    schoolContainer.classList.add('hidden');
                    officeContainer.classList.remove('hidden');
    
                    if (schoolSelect) {
                        schoolSelect.disabled = true;
                        schoolSelect.tomselect?.disable();
                        schoolSelect.required = false;
                    }
    
                    if (officeSelect) {
                        officeSelect.disabled = false;
                        officeSelect.tomselect?.enable();
                        officeSelect.required = true;
                    }
    
                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | NO ASSIGNMENT
                |--------------------------------------------------------------------------
                */
    
                schoolContainer.classList.add('hidden');
                officeContainer.classList.add('hidden');
    
                if (schoolSelect) {
                    schoolSelect.disabled = true;
                        schoolSelect.tomselect?.disable();
                    schoolSelect.required = false;
                }
    
                if (officeSelect) {
                    officeSelect.disabled = true;
                        officeSelect.tomselect?.disable();
                    officeSelect.required = false;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | INITIAL LOAD
            |--------------------------------------------------------------------------
            */
    
            updatePersonnelAssignment();

            /*
            |--------------------------------------------------------------------------
            | ASSIGNMENT TYPE CHANGED
            |--------------------------------------------------------------------------
            */
    
            assignmentType.addEventListener(
                'change',
                updatePersonnelAssignment
            );
    
        });
        </script>

        <script>
        (() => {
            function initPlantillaRequirement() {
                const fund = document.getElementById('source_of_fund');
                const item = document.getElementById('item_number');
                const marker = document.getElementById('plantilla-required-marker');
                const help = document.getElementById('plantilla-requirement-help');

                if (!fund || !item) return;

                const form = item.form;
                const error = document.createElement('p');
                error.id = 'plantilla-required-error';
                error.className = 'mt-2 text-sm font-semibold text-red-600';
                error.setAttribute('role', 'alert');
                error.hidden = true;
                (help || item).insertAdjacentElement('afterend', error);
                item.setAttribute('aria-describedby',
                    ((item.getAttribute('aria-describedby') || '') + ' ' + error.id).trim());

                function clearError() {
                    error.hidden = true;
                    error.textContent = '';
                    item.removeAttribute('aria-invalid');
                    item.tomselect?.control_input.removeAttribute('aria-invalid');
                }

                function validatePlantilla() {
                    const value = item.tomselect ? item.tomselect.getValue() : item.value;
                    if (fund.value !== 'Plantilla' || String(value || '').trim() !== '') {
                        clearError();
                        return true;
                    }

                    error.textContent = 'Please select a Plantilla Item Number before saving changes.';
                    error.hidden = false;
                    item.setAttribute('aria-invalid', 'true');
                    if (item.tomselect) {
                        item.tomselect.control_input.setAttribute('aria-invalid', 'true');
                        item.tomselect.focus();
                        item.tomselect.open();
                        item.tomselect.wrapper.scrollIntoView({block: 'center', behavior: 'smooth'});
                    } else {
                        item.focus();
                        item.scrollIntoView({block: 'center', behavior: 'smooth'});
                    }
                    return false;
                }

                function guardSave(event) {
                    if (!validatePlantilla()) {
                        event.preventDefault();
                        event.stopImmediatePropagation();
                    }
                }

                if (form) {
                    form.addEventListener('submit', guardSave, true);
                    form.addEventListener('click', function (event) {
                        const button = event.target.closest('button, input');
                        if (button && button.form === form && button.type === 'submit') {
                            guardSave(event);
                        }
                    }, true);
                }

                item.addEventListener('change', clearError);

                function updateRequirement() {
                    const required = fund.value === 'Plantilla';
                    item.required = required;
                    item.setAttribute('aria-required', String(required));
                    item.setCustomValidity('');
                    clearError();

                    if (marker) marker.hidden = !required;
                    if (help) {
                        help.textContent = required
                            ? 'Required when Source of Fund is Plantilla.'
                            : 'Optional for this Source of Fund.';
                    }

                    const input = item.tomselect?.control_input;
                    if (input) input.setAttribute('aria-required', String(required));
                }

                // Tom Select hides the native select; focus its visible input on error.
                item.addEventListener('invalid', function (event) {
                    if (fund.value === 'Plantilla' && !item.value) {
                        event.preventDefault();
                        validatePlantilla();
                    }
                });

                fund.addEventListener('change', updateRequirement);
                updateRequirement();
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initPlantillaRequirement);
            } else {
                initPlantillaRequirement();
            }
        })();
        </script>

</x-app-layout>