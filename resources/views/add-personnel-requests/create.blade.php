<x-app-layout>

    <div class="py-6">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


            {{-- ============================================================
                BREADCRUMB
            ============================================================ --}}

            <div class="mb-6">

                <div class="flex flex-wrap items-center gap-2 text-sm text-gray-500">

                    <a
                        href="{{ route('dashboard') }}"
                        class="hover:text-green-700"
                    >
                        Dashboard
                    </a>

                    <span>/</span>

                    <span>Data Management</span>

                    <span>/</span>

                    <a
                        href="{{ route('add-personnel-requests.index') }}"
                        class="hover:text-green-700"
                    >
                        Personnel Requests
                    </a>

                    <span>/</span>

                    <span class="font-medium text-green-700">
                        Add Personnel
                    </span>

                </div>

            </div>



            {{-- ============================================================
                MAIN CARD
            ============================================================ --}}

            <div
                class="overflow-hidden rounded-2xl border
                       border-gray-200 bg-white shadow-sm"
            >


                {{-- HEADER --}}

                <div class="bg-green-800 px-6 py-5 text-white">

                    <h1 class="text-xl font-bold">
                        Add Personnel Request
                    </h1>

                    <p class="mt-1 text-sm text-green-100">
                        Submit personnel information for review and approval
                        by the Personnel Unit.
                    </p>

                </div>



                {{-- ========================================================
                    VALIDATION ERRORS
                ======================================================== --}}

                @if ($errors->any())

                    <div
                        class="m-6 rounded-xl border border-red-200
                               bg-red-50 p-4"
                    >

                        <p class="font-semibold text-red-800">
                            Please correct the following:
                        </p>

                        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">

                            @foreach ($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif



                @if (session('error'))

                    <div
                        class="m-6 rounded-xl border border-red-200
                               bg-red-50 p-4 text-sm text-red-700"
                    >
                        {{ session('error') }}
                    </div>

                @endif



                {{-- ========================================================
                    FORM
                ======================================================== --}}

                <form
                    method="POST"
                    action="{{ route('add-personnel-requests.store') }}"
                    class="p-6"
                >

                    @csrf



                    {{-- ====================================================
                        ASSIGNED SCHOOL
                    ==================================================== --}}

                    <div
                        class="mb-8 rounded-xl border border-green-200
                               bg-green-50 p-5"
                    >

                        <p
                            class="text-xs font-semibold uppercase
                                   tracking-wide text-green-700"
                        >
                            Assigned School
                        </p>

                        <p class="mt-1 text-lg font-bold text-gray-900">

                            {{
                                $school
                                    ? $school->school_name . ' - ' . $school->school_district
                                    : 'Not Assigned'
                            }}

                        </p>

                        @if ($school)

                            <p class="mt-1 text-sm text-gray-600">

                                School ID:
                                {{ $school->school_id }}

                            </p>

                        @endif

                        <p class="mt-3 text-xs text-gray-500">
                            The school is automatically determined from your
                            PDMS account and cannot be changed.
                        </p>

                    </div>



                    {{-- ====================================================
                        PERSONAL INFORMATION
                    ==================================================== --}}

                    <div class="mb-8">

                        <div class="mb-5 border-b border-gray-200 pb-3">

                            <h2 class="text-lg font-bold text-gray-900">
                                Personal Information
                            </h2>

                            <p class="text-sm text-gray-500">
                                Enter the basic information of the personnel.
                            </p>

                        </div>


                        <div
                            class="grid grid-cols-1 gap-5
                                   md:grid-cols-2 lg:grid-cols-4"
                        >


                            {{-- FIRST NAME --}}

                            <div>

                                <label
                                    for="first_name"
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                >
                                    First Name
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="first_name"
                                    name="first_name"
                                    value="{{ old('first_name') }}"
                                    required
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-green-600
                                           focus:ring-green-600"
                                >

                                @error('first_name')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>



                            {{-- MIDDLE NAME --}}

                            <div>

                                <label
                                    for="middle_name"
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                >
                                    Middle Name
                                </label>

                                <input
                                    type="text"
                                    id="middle_name"
                                    name="middle_name"
                                    value="{{ old('middle_name') }}"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-green-600
                                           focus:ring-green-600"
                                >

                            </div>



                            {{-- LAST NAME --}}

                            <div>

                                <label
                                    for="last_name"
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                >
                                    Last Name
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="last_name"
                                    name="last_name"
                                    value="{{ old('last_name') }}"
                                    required
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-green-600
                                           focus:ring-green-600"
                                >

                                @error('last_name')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>



                            {{-- EXTENSION --}}

                            <div>

                                <label
                                    for="extension_name"
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                >
                                    Extension
                                </label>

                                <input
                                    type="text"
                                    id="extension_name"
                                    name="extension_name"
                                    value="{{ old('extension_name') }}"
                                    placeholder="Jr., Sr., III"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-green-600
                                           focus:ring-green-600"
                                >

                            </div>



                            {{-- EMAIL --}}

                            <div class="md:col-span-2">

                                <label
                                    for="email"
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                >
                                    Email Address
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="email"
                                    placeholder="name@deped.gov.ph"

                                    pattern="^[^@\s]+@[^@\s]+\.[^@\s]+$"

                                    title="Please enter a valid email address. Example: juan.delaña@deped.gov.ph"

                                    class="w-full rounded-lg border-gray-300
                                        focus:border-green-600
                                        focus:ring-green-600"
                                >

                                <p class="mt-1 text-xs text-gray-500">
                                    Example: juan.delaña@deped.gov.ph
                                </p>

                                @error('email')

                                    <p class="mt-1 text-sm font-medium text-red-600">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>



                            {{-- SEX --}}

                            <div>

                                <label
                                    for="sex"
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                >
                                    Sex
                                </label>

                                <select
                                    id="sex"
                                    name="sex"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-green-600
                                           focus:ring-green-600"
                                >

                                    <option value="">
                                        Select Sex
                                    </option>

                                    <option
                                        value="Male"
                                        @selected(old('sex') === 'Male')
                                    >
                                        Male
                                    </option>

                                    <option
                                        value="Female"
                                        @selected(old('sex') === 'Female')
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
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="date"
                                    id="birth_date"
                                    name="birth_date"
                                    value="{{ old('birth_date') }}"
                                    required
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-green-600
                                           focus:ring-green-600"
                                >

                                @error('birth_date')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>



                            {{-- BIRTH PLACE --}}

                            <div class="md:col-span-2">

                                <label
                                    for="birth_place"
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                >
                                    Birth Place
                                </label>

                                <input
                                    type="text"
                                    id="birth_place"
                                    name="birth_place"
                                    value="{{ old('birth_place') }}"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-green-600
                                           focus:ring-green-600"
                                >

                            </div>



                            {{-- MOBILE NUMBER --}}

                            <div>

                                <label
                                    for="mobile_number"
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                >
                                    Mobile Number
                                </label>

                                <input
                                    type="text"
                                    id="mobile_number"
                                    name="mobile_number"
                                    value="{{ old('mobile_number') }}"
                                    placeholder="09XXXXXXXXX"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-green-600
                                           focus:ring-green-600"
                                >

                            </div>



                            {{-- SPECIALIZATION --}}

                            <div>

                                <label
                                    for="specialization"
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                >
                                    Specialization
                                </label>

                                <select
                                    id="specialization"
                                    name="specialization"
                                    autocomplete="off"
                                    class="w-full"
                                >

                                    <option value="">
                                        Select Specialization
                                    </option>

                                    @foreach ($specializations as $specialization)

                                        <option
                                            value="{{ $specialization }}"
                                            @selected(old('specialization') === $specialization)
                                        >
                                            {{ $specialization }}
                                        </option>

                                    @endforeach

                                </select>

                                @error('specialization')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                        </div>

                    </div>



                    {{-- ====================================================
                        EMPLOYMENT INFORMATION
                    ==================================================== --}}

                    <div class="mb-8">

                        <div class="mb-5 border-b border-gray-200 pb-3">

                            <h2 class="text-lg font-bold text-gray-900">
                                Employment Information
                            </h2>

                            <p class="text-sm text-gray-500">
                                Enter the personnel's employment details.
                            </p>

                        </div>


                        <div
                            class="grid grid-cols-1 gap-5
                                   md:grid-cols-2 lg:grid-cols-3"
                        >


                            {{-- ORIGINAL APPOINTMENT --}}

                            <div>

                                <label
                                    for="date_of_original_appointment"
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                >
                                    Original Appointment
                                </label>

                                <input
                                    type="date"
                                    id="date_of_original_appointment"
                                    name="date_of_original_appointment"
                                    value="{{ old('date_of_original_appointment') }}"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-green-600
                                           focus:ring-green-600"
                                >

                            </div>



                            {{-- LAST PROMOTION --}}

                            <div>

                                <label
                                    for="date_of_last_promotion"
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                >
                                    Last Promotion
                                </label>

                                <input
                                    type="date"
                                    id="date_of_last_promotion"
                                    name="date_of_last_promotion"
                                    value="{{ old('date_of_last_promotion') }}"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-green-600
                                           focus:ring-green-600"
                                >

                            </div>



                            {{-- EMPLOYMENT STATUS --}}

                            <div>

                                <label
                                    for="employment_status"
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                >
                                    Employment Status
                                </label>

                                <select
                                    id="employment_status"
                                    name="employment_status"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-green-600
                                           focus:ring-green-600"
                                >

                                    <option value="">
                                        Select Employment Status
                                    </option>

                                    @foreach ($employmentStatuses as $employmentStatus)

                                        <option
                                            value="{{ $employmentStatus }}"
                                            @selected(old('employment_status') === $employmentStatus)
                                        >
                                            {{ $employmentStatus }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>



                            {{-- WARM BODY STATUS --}}

                            <div>

                                <label
                                    for="warm_body_status"
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                >
                                    Warm Body Status
                                </label>

                                <select
                                    id="warm_body_status"
                                    name="warm_body_status"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-green-600
                                           focus:ring-green-600"
                                >

                                    <option value="">
                                        Select Warm Body Status
                                    </option>

                                    @foreach ($warmBodyStatuses as $warmBodyStatus)

                                        <option
                                            value="{{ $warmBodyStatus }}"
                                            @selected(old('warm_body_status') === $warmBodyStatus)
                                        >
                                            {{ $warmBodyStatus }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>



                            {{-- NATURE OF WORK --}}

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

                                    <option value="">
                                        Select Nature of Work
                                    </option>

                                    @foreach ($natureOfWorks as $natureOfWork)

                                        <option
                                            value="{{ $natureOfWork }}"
                                            @selected(old('nature_of_work') === $natureOfWork)
                                        >
                                            {{ $natureOfWork }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>



                            {{-- SOURCE OF FUND --}}

                            <div>

                                <label
                                    for="source_of_fund"
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                >
                                    Source of Fund
                                    <span class="text-red-500">*</span>
                                </label>

                                <select
                                    id="source_of_fund"
                                    name="source_of_fund"
                                    required
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-green-600
                                           focus:ring-green-600"
                                >

                                    <option value="">
                                        Select Source of Fund
                                    </option>

                                    @foreach ($sourceOfFunds as $sourceOfFund)

                                        <option
                                            value="{{ $sourceOfFund }}"
                                            @selected(old('source_of_fund') === $sourceOfFund)
                                        >
                                            {{ $sourceOfFund }}
                                        </option>

                                    @endforeach

                                </select>

                                @error('source_of_fund')

                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>



                            {{-- ====================================================
                                PLANTILLA ITEM
                            ==================================================== --}}

                            <div class="md:col-span-2 lg:col-span-3">

                                <label
                                    for="plantilla_db_id"
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                >

                                    Plantilla Item

                                    <span
                                        id="plantilla-required-mark"
                                        class="hidden text-red-500"
                                    >
                                        *
                                    </span>

                                </label>


                                <select
                                    id="plantilla_db_id"
                                    name="plantilla_db_id"
                                    autocomplete="off"
                                    class="w-full"
                                >

                                    <option value="">
                                        Search Plantilla Item
                                    </option>

                                </select>


                                <p
                                    id="plantilla-help"
                                    class="mt-1 text-xs text-gray-500"
                                >
                                    Plantilla Item is required only when
                                    Source of Fund is Plantilla.
                                </p>


                                @error('plantilla_db_id')

                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>



                            {{-- MONTHLY SALARY --}}

                            <div>

                                <label
                                    for="monthly_salary"
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                >
                                    Monthly Salary
                                </label>

                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    id="monthly_salary"
                                    name="monthly_salary"
                                    value="{{ old('monthly_salary') }}"
                                    placeholder="0.00"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-green-600
                                           focus:ring-green-600"
                                >

                            </div>



                            {{-- CONTRACT DURATION --}}

                            <div>

                                <label
                                    for="contract_duration"
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                >
                                    Contract Duration
                                </label>

                                <input
                                    type="text"
                                    id="contract_duration"
                                    name="contract_duration"
                                    value="{{ old('contract_duration') }}"
                                    placeholder="Example: 6 months"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-green-600
                                           focus:ring-green-600"
                                >

                            </div>

                        </div>

                    </div>



                    {{-- ====================================================
                        REMARKS
                    ==================================================== --}}

                    <div class="mb-8">

                        <label
                            for="request_remarks"
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Remarks
                        </label>

                        <textarea
                            id="request_remarks"
                            name="request_remarks"
                            rows="4"
                            placeholder="Optional remarks for the Personnel Unit..."
                            class="w-full rounded-lg border-gray-300
                                   focus:border-green-600
                                   focus:ring-green-600"
                        >{{ old('request_remarks') }}</textarea>

                    </div>



                    {{-- ====================================================
                        BUTTONS
                    ==================================================== --}}

                    <div
                        class="flex items-center justify-end gap-3
                               border-t border-gray-200 pt-6"
                    >

                        <a
                            href="{{ route('add-personnel-requests.index') }}"
                            class="rounded-lg border border-gray-300
                                   bg-white px-5 py-2.5
                                   text-sm font-semibold text-gray-700
                                   transition hover:bg-gray-50"
                        >
                            Cancel
                        </a>


                        <button
                            type="submit"
                            class="rounded-lg bg-green-700
                                   px-6 py-2.5
                                   text-sm font-semibold text-white
                                   shadow-sm transition
                                   hover:bg-green-800"
                        >
                            Submit for Approval
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>



    {{-- ============================================================
        TOM SELECT
    ============================================================ --}}

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css"
    >

    <script
        src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"
    ></script>



    {{-- ============================================================
        TOM SELECT DESIGN
    ============================================================ --}}

    <style>

        .ts-wrapper {
            width: 100%;
        }

        .ts-control {
            min-height: 42px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            padding: 9px 12px !important;
            font-size: 0.875rem !important;
            background-color: #ffffff !important;
            box-shadow: none !important;
        }

        .ts-wrapper.focus .ts-control {
            border-color: #16a34a !important;
            box-shadow: 0 0 0 1px #16a34a !important;
        }

        .ts-dropdown {
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            margin-top: 4px !important;
            overflow: hidden !important;
            box-shadow:
                0 10px 15px -3px rgb(0 0 0 / 0.1),
                0 4px 6px -4px rgb(0 0 0 / 0.1) !important;
        }

        .ts-dropdown .option {
            padding: 10px 12px !important;
            font-size: 0.875rem !important;
        }

        .ts-dropdown .option.active {
            background-color: #f0fdf4 !important;
            color: #166534 !important;
        }

        .ts-dropdown .option.selected {
            background-color: #dcfce7 !important;
            color: #166534 !important;
        }

        .ts-dropdown-content {
            max-height: 300px;
        }

    </style>



    {{-- ============================================================
        JAVASCRIPT
    ============================================================ --}}

    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {


                /*
                |--------------------------------------------------------------------------
                | Specialization Tom Select
                |--------------------------------------------------------------------------
                */

                const specializationElement =
                    document.getElementById('specialization');


                if (specializationElement) {

                    new TomSelect(
                        specializationElement,
                        {

                            create: false,

                            allowEmptyOption: true,

                            maxOptions: null,

                            placeholder:
                                'Select or search specialization...',

                        }
                    );

                }



                /*
                |--------------------------------------------------------------------------
                | Plantilla Tom Select
                |--------------------------------------------------------------------------
                */

                const plantillaElement =
                    document.getElementById('plantilla_db_id');


                let plantillaTomSelect = null;


                if (plantillaElement) {

                    plantillaTomSelect =
                        new TomSelect(
                            plantillaElement,
                            {

                                valueField:
                                    'value',

                                labelField:
                                    'text',

                                searchField: [
                                    'text'
                                ],

                                create:
                                    false,

                                preload:
                                    false,

                                maxOptions:
                                    30,

                                loadThrottle:
                                    400,

                                placeholder:
                                    'Search Plantilla Number or Position...',


                                /*
                                |--------------------------------------------------------------------------
                                | Require At Least 2 Characters
                                |--------------------------------------------------------------------------
                                */

                                shouldLoad: function (query) {

                                    return (
                                        query.trim().length >= 2
                                    );

                                },


                                /*
                                |--------------------------------------------------------------------------
                                | AJAX Search
                                |--------------------------------------------------------------------------
                                */

                                load: function (
                                    query,
                                    callback
                                ) {

                                    query =
                                        query.trim();


                                    if (
                                        query.length < 2
                                    ) {

                                        callback();

                                        return;

                                    }


                                    const url =
                                        "{{ route('add-personnel-requests.search-plantilla') }}"
                                        + "?q="
                                        + encodeURIComponent(
                                            query
                                        );


                                    fetch(
                                        url,
                                        {

                                            headers: {

                                                'Accept':
                                                    'application/json',

                                                'X-Requested-With':
                                                    'XMLHttpRequest',

                                            },

                                        }
                                    )

                                    .then(
                                        function (response) {

                                            if (
                                                !response.ok
                                            ) {

                                                throw new Error(
                                                    'Unable to search plantilla.'
                                                );

                                            }


                                            return response.json();

                                        }
                                    )

                                    .then(
                                        function (data) {

                                            callback(
                                                data
                                            );

                                        }
                                    )

                                    .catch(
                                        function (error) {

                                            console.error(
                                                'Plantilla search error:',
                                                error
                                            );


                                            callback();

                                        }
                                    );

                                },


                                /*
                                |--------------------------------------------------------------------------
                                | Messages
                                |--------------------------------------------------------------------------
                                */

                                render: {

                                    no_results:
                                        function (
                                            data,
                                            escape
                                        ) {

                                            return `
                                                <div class="px-3 py-3 text-sm text-gray-500">

                                                    No plantilla found for

                                                    <strong>
                                                        "${escape(data.input)}"
                                                    </strong>

                                                </div>
                                            `;

                                        },


                                    not_loading:
                                        function (
                                            data,
                                            escape
                                        ) {

                                            if (
                                                data.input.trim().length < 2
                                            ) {

                                                return `
                                                    <div class="px-3 py-3 text-sm text-gray-500">

                                                        Type at least 2 characters
                                                        to search plantilla items.

                                                    </div>
                                                `;

                                            }


                                            return '';

                                        },


                                    loading:
                                        function () {

                                            return `
                                                <div class="px-3 py-3 text-sm text-gray-500">

                                                    Searching plantilla...

                                                </div>
                                            `;

                                        }

                                }

                            }
                        );

                }



                /*
                |--------------------------------------------------------------------------
                | Source of Fund / Plantilla Requirement
                |--------------------------------------------------------------------------
                */

                const sourceOfFundElement =
                    document.getElementById(
                        'source_of_fund'
                    );


                const plantillaRequiredMark =
                    document.getElementById(
                        'plantilla-required-mark'
                    );


                const plantillaHelp =
                    document.getElementById(
                        'plantilla-help'
                    );


                function updatePlantillaRequirement()
                {

                    if (
                        !sourceOfFundElement
                        ||
                        !plantillaElement
                    ) {

                        return;

                    }


                    const isPlantilla =
                        sourceOfFundElement.value ===
                        'Plantilla';


                    /*
                    |--------------------------------------------------------------------------
                    | Plantilla Selected
                    |--------------------------------------------------------------------------
                    */

                    if (isPlantilla) {

                        plantillaElement.setAttribute(
                            'required',
                            'required'
                        );


                        if (
                            plantillaRequiredMark
                        ) {

                            plantillaRequiredMark
                                .classList
                                .remove('hidden');

                        }


                        if (plantillaHelp) {

                            plantillaHelp.textContent =
                                'Plantilla Item is required because the Source of Fund is Plantilla.';


                            plantillaHelp
                                .classList
                                .remove(
                                    'text-gray-500'
                                );


                            plantillaHelp
                                .classList
                                .add(
                                    'text-green-700'
                                );

                        }

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Other Source of Fund
                    |--------------------------------------------------------------------------
                    */

                    else {

                        plantillaElement.removeAttribute(
                            'required'
                        );


                        if (
                            plantillaRequiredMark
                        ) {

                            plantillaRequiredMark
                                .classList
                                .add('hidden');

                        }


                        if (plantillaHelp) {

                            plantillaHelp.textContent =
                                'Plantilla Item is optional for this Source of Fund.';


                            plantillaHelp
                                .classList
                                .remove(
                                    'text-green-700'
                                );


                            plantillaHelp
                                .classList
                                .add(
                                    'text-gray-500'
                                );

                        }

                    }

                }



                /*
                |--------------------------------------------------------------------------
                | Listen For Source of Fund Changes
                |--------------------------------------------------------------------------
                */

                if (sourceOfFundElement) {

                    sourceOfFundElement
                        .addEventListener(
                            'change',
                            updatePlantillaRequirement
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Run When Page Loads
                    |--------------------------------------------------------------------------
                    |
                    | This also handles old('source_of_fund') after a
                    | validation error.
                    |
                    */

                    updatePlantillaRequirement();

                }

            }
        );

    </script>


</x-app-layout>