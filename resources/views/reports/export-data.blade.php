@push('styles')
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css"
>

<style>
    /*
    |--------------------------------------------------------------------------
    | MAIN CONTAINER
    |--------------------------------------------------------------------------
    */

    .export-data {
        width: 100%;
        max-width: 1500px;
        min-width: 0;
        margin: 0 auto;
        padding: 12px 16px;
        color: #1f2937;
    }

    .export-data .page-title {
        font-size: 23px;
        font-weight: 700;
        line-height: 1.3;
        color: #14532d;
    }

    .export-data .muted {
        color: #64748b;
        font-size: 12px;
        line-height: 1.4;
    }

    /*
    |--------------------------------------------------------------------------
    | CARDS
    |--------------------------------------------------------------------------
    */

    .export-data .card {
        margin-top: 12px;
        padding: 16px;
        background: #ffffff;
        border: 1px solid #dce8df;
        border-radius: 10px;
    }

    .export-data .section-title {
        font-size: 16px;
        font-weight: 700;
        line-height: 1.4;
        color: #14532d;
    }

    /*
    |--------------------------------------------------------------------------
    | FILTER GRID
    |--------------------------------------------------------------------------
    */

    .export-data .filters {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        column-gap: 12px;
        row-gap: 10px;
    }

    .export-data .filters > div {
        min-width: 0;
    }

    .export-data .field-label {
        display: block;
        margin-bottom: 4px;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.3;
        color: #374151;
    }

    /*
    |--------------------------------------------------------------------------
    | INPUTS AND DROPDOWNS
    |--------------------------------------------------------------------------
    */

    .export-data .field-input {
        width: 100%;
        min-width: 0;
        min-height: 36px;
        height: 36px;
        padding: 6px 10px;
        border: 1px solid #cbd5d1;
        border-radius: 7px;
        background: #ffffff;
        color: #1f2937;
        font-size: 12px;
        line-height: 1.4;
    }

    .export-data .field-input:focus {
        border-color: #16a34a;
        outline: none;
        box-shadow: 0 0 0 1px #16a34a;
    }

    /*
    |--------------------------------------------------------------------------
    | TOM SELECT
    |--------------------------------------------------------------------------
    */

    .export-data .ts-wrapper {
        width: 100%;
        min-width: 0;
    }

    .export-data .ts-control {
        min-height: 36px !important;
        height: 36px;
        padding: 6px 10px !important;
        border: 1px solid #cbd5d1 !important;
        border-radius: 7px !important;
        background: #ffffff;
        font-size: 12px;
        line-height: 1.4;
        box-shadow: none;
        align-items: center;
    }

    .export-data .ts-control > .item {
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .export-data .ts-control > input {
        min-width: 0 !important;
        font-size: 12px;
    }

    .export-data .ts-wrapper.focus .ts-control {
        border-color: #16a34a !important;
        box-shadow: 0 0 0 1px #16a34a !important;
    }

    .export-data .ts-dropdown {
        border-radius: 7px;
        overflow: hidden;
        font-size: 12px;
    }

    .export-data .ts-dropdown-content {
        max-height: 220px;
    }

    .export-data .ts-dropdown .option {
        padding: 8px 10px;
    }

    .export-data .ts-dropdown .active {
        background: #dcfce7;
        color: #166534;
    }

    .export-data .ts-dropdown .selected {
        background: #15803d;
        color: #ffffff;
    }

    /*
    |--------------------------------------------------------------------------
    | BUTTONS
    |--------------------------------------------------------------------------
    */

    .export-data .btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-height: 36px;
        padding: 7px 14px;
        border: none;
        border-radius: 7px;
        background: #15803d;
        color: #ffffff;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }

    .export-data .btn-primary:hover {
        background: #166534;
    }

    .export-data .btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 36px;
        padding: 7px 12px;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        background: #ffffff;
        color: #374151;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .export-data .btn-secondary:hover {
        background: #f9fafb;
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT COLUMN SELECTION
    |--------------------------------------------------------------------------
    */

    .export-data .column-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 8px;
    }

    .export-data .column-option {
        display: flex;
        align-items: center;
        gap: 8px;
        min-height: 36px;
        padding: 7px 9px;
        border: 1px solid #e2e8f0;
        border-radius: 7px;
        cursor: pointer;
        font-size: 12px;
        overflow-wrap: anywhere;
    }

    .export-data .column-option:hover {
        background: #f0fdf4;
        border-color: #86efac;
    }

    .export-data .column-option input {
        width: 15px;
        height: 15px;
        flex-shrink: 0;
        accent-color: #15803d;
    }

    /*
    |--------------------------------------------------------------------------
    | GROUP HEADER
    |--------------------------------------------------------------------------
    */

    .export-data .group-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 10px;
        padding-bottom: 8px;
        border-bottom: 1px solid #e2e8f0;
    }

    /*
    |--------------------------------------------------------------------------
    | PREVIEW TABLE
    |--------------------------------------------------------------------------
    */

    .export-data .table-wrap {
        width: 100%;
        overflow-x: auto;
        margin-top: 12px;
    }

    .export-data table {
        width: 100%;
        min-width: 1100px;
        border-collapse: collapse;
        font-size: 12px;
    }

    .export-data th,
    .export-data td {
        padding: 9px 10px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .export-data th {
        background: #f0fdf4;
        color: #166534;
        font-weight: 700;
    }

    .export-data tbody tr:hover {
        background: #f8fafc;
    }

    /*
    |--------------------------------------------------------------------------
    | ERROR BOX
    |--------------------------------------------------------------------------
    */

    .export-data .error-box {
        margin-top: 12px;
        padding: 10px 14px;
        border-radius: 7px;
        background: #fee2e2;
        color: #991b1b;
        font-size: 12px;
    }

    /*
    |--------------------------------------------------------------------------
    | RESPONSIVE DESIGN
    |--------------------------------------------------------------------------
    */

    @media (max-width: 1100px) {
        .export-data .column-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 760px) {
        .export-data {
            padding: 10px;
        }

        .export-data .card {
            padding: 12px;
        }

        .export-data .filters {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .export-data .column-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>
@endpush


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Tom Select
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.searchable-select')
        .forEach(function (element) {

            if (typeof TomSelect === 'undefined' || element.tomselect) {
                return;
            }

            new TomSelect(element, {
                create: false,
                allowEmptyOption: true,
                maxOptions: null,
                searchField: ['text'],
                plugins: ['dropdown_input']
            });
        });


    /*
    |--------------------------------------------------------------------------
    | Column Selection
    |--------------------------------------------------------------------------
    */

    const checkboxes = document.querySelectorAll('.export-column');

    const countElement = document.getElementById('selected-count');

    const downloadButton = document.getElementById('download-excel');

    function updateCount() {

        const selected = document.querySelectorAll(
            '.export-column:checked'
        ).length;

        countElement.textContent = selected;

        downloadButton.disabled = selected === 0;

        downloadButton.classList.toggle(
            'opacity-50',
            selected === 0
        );
    }

    document.getElementById('select-all-columns')
        .addEventListener('click', function () {

            checkboxes.forEach(function (checkbox) {
                checkbox.checked = true;
            });

            updateCount();
        });

    document.getElementById('clear-all-columns')
        .addEventListener('click', function () {

            checkboxes.forEach(function (checkbox) {
                checkbox.checked = false;
            });

            updateCount();
        });

    document.querySelectorAll('.group-select')
        .forEach(function (button) {

            button.addEventListener('click', function () {

                const group = button.dataset.group;

                document.querySelectorAll(
                    '.export-column[data-group="' + group + '"]'
                ).forEach(function (checkbox) {
                    checkbox.checked = true;
                });

                updateCount();
            });
        });

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', updateCount);
    });

    document.getElementById('export-form')
        .addEventListener('submit', function (event) {

            if (!document.querySelector('.export-column:checked')) {
                event.preventDefault();

                alert('Please select at least one column.');
            }
        });

    updateCount();
});
</script>
@endpush


<x-app-layout>

<div class="export-data">

    {{-- =====================================================
        BREADCRUMB
    ====================================================== --}}

    <nav
        class="mb-5 flex flex-wrap items-center gap-2 text-sm"
        aria-label="Breadcrumb"
    >

        <a
            href="{{ route('dashboard') }}"
            class="font-medium text-gray-500 hover:text-green-700"
        >
            Dashboard
        </a>

        <span class="text-gray-400">/</span>

        <a
            href="{{ route('data-management.reports') }}"
            class="font-medium text-gray-500 hover:text-green-700"
        >
            Report Management
        </a>

        <span class="text-gray-400">/</span>

        <span class="font-semibold text-green-800">
            Export Data
        </span>

    </nav>


    {{-- =====================================================
        PAGE HEADER
    ====================================================== --}}

    <div class="flex flex-wrap items-center justify-between gap-4">

        <div>
            <h1 class="page-title">
                Export Personnel Data
            </h1>

            <p class="muted mt-1">
                Filter personnel records, select export columns,
                and download the results in Excel format.
            </p>
        </div>

        <div class="rounded-lg border border-green-200 bg-green-50 px-5 py-3">

            <p class="text-xs font-semibold text-green-700">
                MATCHING PERSONNEL
            </p>

            <p class="mt-1 text-2xl font-bold text-green-800">
                {{ number_format($records->total()) }}
            </p>

        </div>

    </div>


    {{-- =====================================================
        ERRORS
    ====================================================== --}}

    @if($errors->any())

        <div class="error-box" role="alert">

            <p class="font-semibold">
                Please check the following:
            </p>

            <ul class="mt-2 list-disc pl-5">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- =====================================================
        FILTERS
    ====================================================== --}}

    <section class="card">

        <div class="mb-5">

            <h2 class="section-title">
                Filter Personnel Records
            </h2>

            <p class="muted mt-1">
                Select the conditions for the personnel records
                you want to export.
            </p>

        </div>

        <form
            method="GET"
            action="{{ route('data-management.reports.export-data') }}"
        >

            <div class="filters">

                {{-- SEARCH --}}

                <div>
                    <label for="search" class="field-label">
                        Search Personnel
                    </label>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Employee ID, name, or email"
                        class="field-input"
                    >
                </div>


                {{-- EMPLOYMENT CLASSIFICATION --}}

                <div>
                    <label for="employment_filter" class="field-label">
                        Employment Classification
                    </label>

                    <select
                        id="employment_filter"
                        name="employment_filter"
                        class="field-input"
                    >

                        <option value="all">
                            All Employees
                        </option>

                        <option
                            value="active"
                            @selected(request('employment_filter') === 'active')
                        >
                            Active
                        </option>

                        <option
                            value="inactive"
                            @selected(request('employment_filter') === 'inactive')
                        >
                            Inactive
                        </option>

                    </select>
                </div>


                {{-- DYNAMIC FILTERS --}}

                @php
                    $filterFields = [

                        // Basic Information
                        'sex' => 'Sex',
                        'civil_status' => 'Civil Status',
                        'specialization' => 'Specialization',

                        // Employment Information
                        'employment_status' => 'Employment Status',
                        'warm_body_status' => 'Warm Body Status',
                        'nature_of_work' => 'Nature of Work',
                        'source_of_fund' => 'Source of Fund',

                        // School Information
                        'school_id' => 'School ID',
                        'school_name' => 'School Name',
                        'school_district' => 'School District',
                        'school_municipality' => 'Municipality',
                        'school_area' => 'School Area',
                        'legislative_district' => 'Legislative District',
                        'school_sector' => 'School Sector',

                        // Plantilla Information
                        'item_number' => 'Plantilla Item Number',
                        'position_title' => 'Position Title',
                        'salary_grade' => 'Salary Grade',
                        'plantilla_level' => 'Plantilla Level',
                        'area_type' => 'Area Type',

                        // Medical Allowance
                        'year' => 'Medical Allowance Year',
                        'mode_of_availment' => 'Mode of Availment',
                        'disbursement_status' => 'Disbursement Status',
                        'validation_status' => 'Validation Status',
                    ];
                @endphp

                @foreach($filterFields as $field => $label)

                    @php
                        $inputName = $field === 'year'
                            ? 'medical_year'
                            : $field;
                    @endphp

                    <div class="min-w-0">

                        <label
                            for="filter-{{ $inputName }}"
                            class="field-label"
                        >
                            {{ $label }}
                        </label>

                        <select
                            id="filter-{{ $inputName }}"
                            name="{{ $inputName }}"
                            class="searchable-select"
                        >

                            <option value="">
                                All {{ $label }}
                            </option>

                            @foreach($options[$field] ?? [] as $value)

                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        (string) request($inputName)
                                        === (string) $value
                                    )
                                >
                                    {{ $value }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                @endforeach

            </div>


            {{-- FILTER ACTIONS --}}

            <div class="mt-6 flex flex-wrap gap-3">

                <button
                    type="submit"
                    class="btn-primary"
                >
                    Apply Filters
                </button>

                <a
                    href="{{ route('data-management.reports.export-data') }}"
                    class="btn-secondary"
                >
                    Reset Filters
                </a>

            </div>

        </form>

    </section>


    {{-- =====================================================
        SELECT EXPORT COLUMNS
    ====================================================== --}}

    <section class="card">

        <div class="flex flex-wrap items-center justify-between gap-4">

            <div>

                <h2 class="section-title">
                    Select Columns to Export
                </h2>

                <p class="muted mt-1">
                    Select the information to include in the Excel file.
                </p>

            </div>

            <div class="flex flex-wrap gap-2">

                <button
                    type="button"
                    id="select-all-columns"
                    class="btn-secondary"
                >
                    Select All
                </button>

                <button
                    type="button"
                    id="clear-all-columns"
                    class="btn-secondary"
                >
                    Clear All
                </button>

            </div>

        </div>


        <form
            id="export-form"
            method="POST"
            action="{{ route('data-management.reports.export-data.download') }}"
        >

            @csrf

            {{-- PRESERVE FILTERS --}}

            @foreach([
                'search',
                'employment_filter',

                'sex',
                'civil_status',
                'specialization',

                'employment_status',
                'warm_body_status',
                'nature_of_work',
                'source_of_fund',

                'school_id',
                'school_name',
                'school_district',
                'school_municipality',
                'school_area',
                'legislative_district',
                'school_sector',

                'item_number',
                'position_title',
                'salary_grade',
                'plantilla_level',
                'area_type',

                'medical_year',
                'mode_of_availment',
                'disbursement_status',
                'validation_status'
            ] as $filter)

                @if(request()->filled($filter))

                    <input
                        type="hidden"
                        name="{{ $filter }}"
                        value="{{ request($filter) }}"
                    >

                @endif

            @endforeach


            {{-- GROUPED COLUMNS --}}

            @foreach($columnGroups as $groupName => $groupColumns)

                <div class="mt-6">

                    <div class="group-header">

                        <div>

                            <h3 class="font-semibold text-gray-800">
                                {{ $groupName }}
                            </h3>

                            <p class="muted">
                                {{ count($groupColumns) }} available columns
                            </p>

                        </div>

                        <button
                            type="button"
                            class="group-select text-sm font-semibold text-green-700 hover:text-green-900"
                            data-group="{{ $loop->index }}"
                        >
                            Select Group
                        </button>

                    </div>


                    <div class="column-grid">

                        @foreach($groupColumns as $key => $definition)

                            <label class="column-option">

                                <input
                                    type="checkbox"
                                    name="columns[]"
                                    value="{{ $key }}"
                                    class="export-column"
                                    data-group="{{ $loop->parent->index }}"
                                    @checked(
                                        in_array(
                                            $key,
                                            old('columns', $defaultColumns)
                                        )
                                    )
                                >

                                <span>
                                    {{ $definition['label'] }}
                                </span>

                            </label>

                        @endforeach

                    </div>

                </div>

            @endforeach


            {{-- DOWNLOAD BUTTON --}}

            <div
                class="mt-7 flex flex-wrap items-center justify-between
                       gap-4 border-t border-gray-200 pt-5"
            >

                <p class="muted">

                    <span
                        id="selected-count"
                        class="font-bold text-green-800"
                    >
                        0
                    </span>

                    columns selected

                </p>

                <button
                    type="submit"
                    id="download-excel"
                    class="btn-primary"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 3v12m0 0l-4-4m4 4l4-4M5 17v3h14v-3"
                        />
                    </svg>

                    Download Excel

                </button>

            </div>

        </form>

    </section>


    {{-- =====================================================
        PERSONNEL PREVIEW
    ====================================================== --}}

    <section class="card">

        <div class="flex flex-wrap items-center justify-between gap-4">

            <div>

                <h2 class="section-title">
                    Personnel Records Preview
                </h2>

                <p class="muted mt-1">
                    Showing 15 records per page.
                </p>

            </div>

            <span
                class="rounded-lg bg-green-50 px-4 py-2
                       text-sm font-semibold text-green-800"
            >
                {{ number_format($records->total()) }}
                Matching Records
            </span>

        </div>


        <div
            class="table-wrap"
            role="region"
            aria-label="Personnel records preview"
            tabindex="0"
        >

            <table>

                <thead>

                    <tr>
                        <th>Employee ID</th>
                        <th>Full Name</th>
                        <th>Sex</th>
                        <th>School ID</th>
                        <th>School Name</th>
                        <th>School District</th>
                        <th>Plantilla Item Number</th>
                        <th>Position Title</th>
                        <th>Salary Grade</th>
                        <th>Source of Fund</th>
                        <th>Warm Body Status</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($records as $user)

                        @php
                            $basic = $user->basicInformation;
                            $employment = $user->employmentStatus;

                            $school = $employment?->school;
                            $plantilla = $employment?->plantilla;

                            $fullName = collect([
                                $basic?->first_name,
                                $basic?->middle_name,
                                $basic?->last_name,
                                $basic?->extension_name,
                            ])->filter()->implode(' ');

                            $inactive = in_array(
                                $employment?->warm_body_status,
                                [
                                    'Vacant (Retired)',
                                    'Vacant (Resigned)',
                                    'Vacant (Others)',
                                ],
                                true
                            );
                        @endphp

                        <tr>
                            <td>
                                {{ $basic?->issuedId?->employee_id ?? '—' }}
                            </td>

                            <td class="font-semibold text-gray-800">
                                {{ $fullName ?: $user->name }}
                            </td>

                            <td>
                                {{ $basic?->sex ?? '—' }}
                            </td>

                            <td>
                                {{ $school?->school_id ?? '—' }}
                            </td>

                            <td>
                                {{ $school?->school_name ?? '—' }}
                            </td>

                            <td>
                                {{ $school?->school_district ?? '—' }}
                            </td>

                            <td>
                                {{ $plantilla?->item_number ?? '—' }}
                            </td>

                            <td>
                                {{ $plantilla?->position_title ?? '—' }}
                            </td>

                            <td>
                                {{ $plantilla?->salary_grade ?? '—' }}
                            </td>

                            <td>
                                {{ $employment?->source_of_fund ?? '—' }}
                            </td>

                            <td>
                                {{ $employment?->warm_body_status ?? '—' }}
                            </td>
                        </tr>

                    @empty

                        <tr>

                            <td colspan="11" class="py-10 text-center text-gray-500">
                                No personnel records match the selected filters.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}

        @if($records->hasPages())

            <div class="mt-5">
                {{ $records->links() }}
            </div>

        @endif

    </section>

</div>

</x-app-layout>