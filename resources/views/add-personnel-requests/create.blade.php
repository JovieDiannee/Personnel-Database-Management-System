<x-app-layout>

    <div class="py-6">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- ============================================================
                PAGE HEADER
            ============================================================ --}}

            <div class="mb-6">

                <div class="flex items-center gap-2 text-sm text-gray-500">

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
                CARD
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
                    ERRORS
                ======================================================== --}}

                @if ($errors->any())

                    <div
                        class="m-6 rounded-xl border border-red-200
                               bg-red-50 p-4"
                    >

                        <p class="font-semibold text-red-800">
                            Please correct the following:
                        </p>

                        <ul
                            class="mt-2 list-disc space-y-1
                                   pl-5 text-sm text-red-700"
                        >

                            @foreach ($errors->all() as $error)

                                <li>{{ $error }}</li>

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
                        SCHOOL
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
                            {{ $school
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
                                    class="mb-1 block text-sm font-medium
                                           text-gray-700"
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

                            </div>


                            {{-- MIDDLE NAME --}}

                            <div>

                                <label
                                    for="middle_name"
                                    class="mb-1 block text-sm font-medium
                                           text-gray-700"
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
                                    class="mb-1 block text-sm font-medium
                                           text-gray-700"
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

                            </div>


                            {{-- EXTENSION --}}

                            <div>

                                <label
                                    for="extension_name"
                                    class="mb-1 block text-sm font-medium
                                           text-gray-700"
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
                                    class="mb-1 block text-sm font-medium
                                           text-gray-700"
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
                                    placeholder="name@deped.gov.ph"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-green-600
                                           focus:ring-green-600"
                                >

                            </div>


                            {{-- SEX --}}

                            <div>

                                <label
                                    for="sex"
                                    class="mb-1 block text-sm font-medium
                                           text-gray-700"
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
                                        Select
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
                                    class="mb-1 block text-sm font-medium
                                           text-gray-700"
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

                            </div>


                            {{-- BIRTH PLACE --}}

                            <div class="md:col-span-2">

                                <label
                                    for="birth_place"
                                    class="mb-1 block text-sm font-medium
                                           text-gray-700"
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


                            {{-- MOBILE --}}

                            <div>

                                <label
                                    for="mobile_number"
                                    class="mb-1 block text-sm font-medium
                                           text-gray-700"
                                >
                                    Mobile Number
                                </label>

                                <input
                                    type="text"
                                    id="mobile_number"
                                    name="mobile_number"
                                    value="{{ old('mobile_number') }}"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-green-600
                                           focus:ring-green-600"
                                >

                            </div>


                            {{-- SPECIALIZATION --}}

                            <div>

                                <label
                                    for="specialization"
                                    class="mb-1 block text-sm font-medium
                                           text-gray-700"
                                >
                                    Specialization
                                </label>

                                <input
                                    type="text"
                                    id="specialization"
                                    name="specialization"
                                    value="{{ old('specialization') }}"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-green-600
                                           focus:ring-green-600"
                                >

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

                            {{-- ====================================================
                                PLANTILLA ITEM - TOM SELECT
                            ==================================================== --}}

                            <div class="lg:col-span-3">

                                <label
                                    for="plantilla_db_id"
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                >
                                    Plantilla Item
                                </label>

                                <select
                                    id="plantilla_db_id"
                                    name="plantilla_db_id"
                                    autocomplete="off"
                                    class="w-full"
                                >
                                    <option value="">Select Plantilla Item</option>

                                    @foreach ($plantillas as $plantilla)

                                        <option
                                            value="{{ $plantilla->id }}"
                                            @selected(old('plantilla_db_id') == $plantilla->id)
                                        >
                                            {{ $plantilla->item_number }} — {{ $plantilla->position_title }}
                                            
                                        </option>

                                    @endforeach

                                </select>


                                {{-- VALIDATION ERROR --}}

                                @error('plantilla_db_id')

                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- ORIGINAL APPOINTMENT --}}

                            <div>

                                <label
                                    for="date_of_original_appointment"
                                    class="mb-1 block text-sm font-medium
                                           text-gray-700"
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
                                    class="mb-1 block text-sm font-medium
                                           text-gray-700"
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
                                    class="mb-1 block text-sm font-medium
                                           text-gray-700"
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
                                        Select
                                    </option>

                                    @foreach (
                                        [
                                            'Permanent',
                                            'Temporary',
                                            'Provisional',
                                            'Contractual',
                                            'Job Order'
                                        ] as $status
                                    )

                                        <option
                                            value="{{ $status }}"
                                            @selected(
                                                old('employment_status') ===
                                                $status
                                            )
                                        >
                                            {{ $status }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- NATURE OF WORK --}}

                            <div>

                                <label
                                    for="nature_of_work"
                                    class="mb-1 block text-sm font-medium
                                           text-gray-700"
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
                                        Select
                                    </option>

                                    @foreach (
                                        [
                                            'Teaching',
                                            'Teaching Related',
                                            'School Administration',
                                            'Non-Teaching'
                                        ] as $nature
                                    )

                                        <option
                                            value="{{ $nature }}"
                                            @selected(
                                                old('nature_of_work') ===
                                                $nature
                                            )
                                        >
                                            {{ $nature }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- WARM BODY --}}

                            <div>

                                <label
                                    for="warm_body_status"
                                    class="mb-1 block text-sm font-medium
                                           text-gray-700"
                                >
                                    Warm Body Status
                                </label>

                                <input
                                    type="text"
                                    id="warm_body_status"
                                    name="warm_body_status"
                                    value="{{ old('warm_body_status') }}"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-green-600
                                           focus:ring-green-600"
                                >

                            </div>


                            {{-- SOURCE OF FUND --}}

                            <div>

                                <label
                                    for="source_of_fund"
                                    class="mb-1 block text-sm font-medium
                                           text-gray-700"
                                >
                                    Source of Fund
                                </label>

                                <input
                                    type="text"
                                    id="source_of_fund"
                                    name="source_of_fund"
                                    value="{{ old('source_of_fund') }}"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-green-600
                                           focus:ring-green-600"
                                >

                            </div>


                            {{-- SALARY --}}

                            <div>

                                <label
                                    for="monthly_salary"
                                    class="mb-1 block text-sm font-medium
                                           text-gray-700"
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
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-green-600
                                           focus:ring-green-600"
                                >

                            </div>


                            {{-- CONTRACT DURATION --}}

                            <div>

                                <label
                                    for="contract_duration"
                                    class="mb-1 block text-sm font-medium
                                           text-gray-700"
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
                            class="mb-1 block text-sm font-medium
                                   text-gray-700"
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
                                   bg-white px-5 py-2.5 text-sm font-semibold
                                   text-gray-700 hover:bg-gray-50"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="rounded-lg bg-green-700 px-6 py-2.5
                                   text-sm font-semibold text-white
                                   shadow-sm transition hover:bg-green-800"
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
    src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js">
</script>


<style>

    /* =========================================================
       TOM SELECT - PDMS DESIGN
    ========================================================= */

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

        box-shadow:
            0 0 0 1px #16a34a !important;
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

</style>


<script>

    document.addEventListener('DOMContentLoaded', function () {

        const plantillaSelect =
            document.getElementById('plantilla_db_id');


        if (plantillaSelect) {

            new TomSelect(plantillaSelect, {

                create: false,

                allowEmptyOption: true,

                placeholder:
                    'Search Plantilla Number or Position...',

                maxOptions: null,

                closeAfterSelect: true,

                selectOnTab: true,

                searchField: [
                    'text'
                ],

                render: {

                    no_results: function(data, escape) {

                        return `
                            <div class="py-3 px-3 text-sm text-gray-500">
                                No plantilla item found for
                                "<strong>${escape(data.input)}</strong>"
                            </div>
                        `;
                    }

                }

            });

        }

    });

</script>

</x-app-layout>