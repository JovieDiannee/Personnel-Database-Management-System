<x-app-layout>

    <div class="min-h-screen min-w-0 bg-gray-50 py-4 sm:py-8">

        <div class="mx-auto w-full min-w-0 max-w-7xl px-4 sm:px-6">


            {{-- ========================================================= --}}
            {{-- BREADCRUMB TRAIL --}}
            {{-- ========================================================= --}}

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


                    {{-- Medical Allowance --}}
                    <a
                        href="{{ route('data-management.medical-allowance') }}"
                        class="font-medium text-gray-500 transition hover:text-green-700"
                    >
                        Medical Allowance
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
                        School Level Report
                    </span>

                </nav>

            </div>



            {{-- ========================================================= --}}
            {{-- TAB NAVIGATION --}}
            {{-- ========================================================= --}}

            <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                <div class="grid w-full grid-cols-1 sm:grid-cols-2">


                    {{-- TAB 1: RECORDS --}}
                    <a
                        href="{{ route('data-management.medical-allowance') }}"
                        class="flex flex-1 items-center justify-center gap-2
                               border-b-2 border-transparent bg-white
                               px-5 py-4 text-center text-sm font-semibold
                               text-gray-700 transition duration-200
                               hover:bg-green-50 hover:text-green-800"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 shrink-0"
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
                               border-b-2 border-green-700 bg-green-50
                               px-5 py-4 text-center text-sm font-semibold
                               text-green-800 transition duration-200
                               hover:bg-green-100"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 shrink-0"
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



            {{-- ========================================================= --}}
            {{-- MEDICAL ALLOWANCE REPORT --}}
            {{-- ========================================================= --}}

            <div>


                {{-- ===================================================== --}}
                {{-- REPORT YEAR HEADING --}}
                {{-- ===================================================== --}}

                <div class="mb-3 flex flex-wrap items-center justify-between gap-2">

                    <div>

                        <h2 class="text-base font-bold text-gray-900">
                            {{ $selectedYear }} Medical Allowance Report
                        </h2>

                        <p class="mt-0.5 text-xs text-gray-500">
                            Plantilla-funded employees summarized by school.
                        </p>

                    </div>


                    <div
                        class="inline-flex items-center rounded-lg
                               border border-green-200 bg-green-50
                               px-3 py-1.5 text-xs font-semibold
                               text-green-800"
                    >
                        Report Year: {{ $selectedYear }}
                    </div>

                </div>



                {{-- ===================================================== --}}
                {{-- SUMMARY CARDS - ALWAYS ONE HORIZONTAL ROW --}}
                {{-- ===================================================== --}}

                @php
                    $summaryCards = [
                        [
                            'label' => 'Total Schools',
                            'value' => $totalSchools,
                            'color' => 'text-gray-900',
                        ],
                        [
                            'label' => 'Plantilla Employees',
                            'value' => $totalPlantillaEmployee,
                            'color' => 'text-indigo-700',
                        ],
                        [
                            'label' => 'Group Availment',
                            'value' => $totalGroupAvailment,
                            'color' => 'text-blue-700',
                        ],
                        [
                            'label' => 'Individual Availment',
                            'value' => $totalIndividualAvailment,
                            'color' => 'text-purple-700',
                        ],
                        [
                            'label' => 'Not Eligible',
                            'value' => $totalNotEligible,
                            'color' => 'text-red-700',
                        ],
                        [
                            'label' => 'No Record',
                            'value' => $totalNoRecord,
                            'color' => 'text-orange-600',
                        ],
                        [
                            'label' => 'Total Eligible',
                            'value' => $totalEligible,
                            'color' => 'text-green-700',
                        ],
                    ];
                @endphp


                <div class="mb-5 w-full overflow-x-auto">

                    <div
                        style="
                            display: grid;
                            grid-template-columns: repeat(7, minmax(0, 1fr));
                            gap: 8px;
                            min-width: 1050px;
                            width: 100%;
                        "
                    >

                        @foreach ($summaryCards as $card)

                            <div
                                @class([
                                    'min-w-0 rounded-xl border px-4 py-3 shadow-sm',
                                    'border-green-200 bg-green-50' => $loop->last,
                                    'border-gray-200 bg-white' => ! $loop->last,
                                ])
                            >

                                {{-- LABEL --}}
                                <div
                                    class="truncate text-xs font-medium
                                        text-gray-500"
                                >
                                    {{ $card['label'] }}
                                </div>


                                {{-- VALUE --}}
                                <div
                                    class="mt-1 text-xl font-bold
                                        tabular-nums {{ $card['color'] }}"
                                >
                                    {{ number_format($card['value']) }}
                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>



                {{-- ===================================================== --}}
                {{-- REPORT FILTER - STRICTLY ONE ROW --}}
                {{-- ===================================================== --}}

                <div class="mb-5 w-full overflow-x-auto rounded-xl shadow-md">

                    <div
                        class="px-4 py-4"
                        style="
                            background: linear-gradient(
                                135deg,
                                #166534 0%,
                                #15803d 55%,
                                #059669 100%
                            );
                        "
                    >

                        <form
                            method="GET"
                            action="{{ route('data-management.medical-allowance.report') }}"
                            class="w-full"
                        >

                            {{-- FORCE EVERYTHING INTO ONE ROW --}}
                            <div
                                style="
                                    display: flex;
                                    flex-direction: row;
                                    flex-wrap: nowrap;
                                    align-items: flex-end;
                                    gap: 8px;
                                    width: 100%;
                                    min-width: 1050px;
                                "
                            >

                                {{-- ================================================= --}}
                                {{-- REPORT YEAR --}}
                                {{-- ================================================= --}}

                                <div
                                    style="
                                        width: 130px;
                                        min-width: 130px;
                                        flex: 0 0 130px;
                                    "
                                >

                                    <label
                                        for="year"
                                        class="mb-1 block text-[11px]
                                            font-semibold text-white"
                                    >
                                        Report Year
                                    </label>

                                    <select
                                        id="year"
                                        name="year"
                                        class="h-11 w-full rounded-lg border-0
                                            bg-white px-3 text-sm
                                            font-medium text-gray-700
                                            shadow-sm
                                            focus:outline-none
                                            focus:ring-2 focus:ring-green-300"
                                    >

                                        @foreach($availableYears as $year)

                                            <option
                                                value="{{ $year }}"
                                                @selected(
                                                    (int) $selectedYear ===
                                                    (int) $year
                                                )
                                            >
                                                {{ $year }}
                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                {{-- ================================================= --}}
                                {{-- DISTRICT --}}
                                {{-- ================================================= --}}

                                <div
                                    style="
                                        width: 220px;
                                        min-width: 220px;
                                        flex: 0 0 220px;
                                    "
                                >

                                    <label
                                        for="district"
                                        class="mb-1 block text-[11px]
                                            font-semibold text-white"
                                    >
                                        District
                                    </label>

                                    <select
                                        id="district"
                                        name="district"
                                        class="h-11 w-full rounded-lg border-0
                                            bg-white px-3 text-sm
                                            text-gray-700 shadow-sm
                                            focus:outline-none
                                            focus:ring-2 focus:ring-green-300"
                                    >

                                        @if(auth()->user()?->role === 'super_admin')

                                            <option value="">
                                                All Districts
                                            </option>

                                        @endif


                                        @foreach($districts as $districtName)

                                            <option
                                                value="{{ $districtName }}"
                                                @selected(
                                                    $district === $districtName
                                                )
                                            >
                                                {{ $districtName }}
                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                {{-- ================================================= --}}
                                {{-- SEARCH SCHOOL --}}
                                {{-- ================================================= --}}

                                <div
                                    style="
                                        min-width: 280px;
                                        flex: 1 1 auto;
                                    "
                                >

                                    <label
                                        for="search"
                                        class="mb-1 block text-[11px]
                                            font-semibold text-white"
                                    >
                                        Search School
                                    </label>

                                    <input
                                        type="text"
                                        id="search"
                                        name="search"
                                        value="{{ $search }}"
                                        placeholder="School ID or school name..."
                                        class="h-11 w-full rounded-lg border-0
                                            bg-white px-3 text-sm
                                            text-gray-700 shadow-sm
                                            placeholder:text-gray-400
                                            focus:outline-none
                                            focus:ring-2 focus:ring-green-300"
                                    >

                                </div>


                                {{-- ================================================= --}}
                                {{-- SEARCH BUTTON --}}
                                {{-- ================================================= --}}

                                <button
                                    type="submit"
                                    class="inline-flex h-11 shrink-0
                                        items-center justify-center gap-2
                                        whitespace-nowrap rounded-lg
                                        bg-white px-5 text-sm
                                        font-semibold text-green-800
                                        shadow-sm transition
                                        hover:bg-green-50"
                                >

                                    <svg
                                        class="h-4 w-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="m21 21-4.35-4.35
                                            M11 18a7 7 0 1 1 0-14
                                            7 7 0 0 1 0 14z"
                                        />
                                    </svg>

                                    Search

                                </button>


                                {{-- ================================================= --}}
                                {{-- RESET BUTTON --}}
                                {{-- ================================================= --}}

                                <a
                                    href="{{ route('data-management.medical-allowance.report') }}"
                                    class="inline-flex h-11 shrink-0
                                        items-center justify-center
                                        whitespace-nowrap rounded-lg
                                        border border-white/50
                                        bg-white/10 px-5
                                        text-sm font-semibold text-white
                                        transition
                                        hover:bg-white
                                        hover:text-green-800"
                                >
                                    Reset
                                </a>


                                {{-- ================================================= --}}
                                {{-- DOWNLOAD EXCEL --}}
                                {{-- ================================================= --}}

                                @if(auth()->user()?->role === 'super_admin')

                                    <button
                                        type="submit"
                                        formaction="{{ route('data-management.medical-allowance.report.export') }}"
                                        class="inline-flex h-11 shrink-0
                                            items-center justify-center gap-2
                                            whitespace-nowrap rounded-lg
                                            border border-green-300
                                            bg-green-800 px-5
                                            text-sm font-semibold
                                            text-white shadow-sm
                                            transition
                                            hover:bg-green-900"
                                    >

                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 4v12m0 0
                                                4-4m-4 4-4-4
                                                M5 20h14"
                                            />
                                        </svg>

                                        Download Excel

                                    </button>

                                @endif

                            </div>

                        </form>

                    </div>

                </div>



                {{-- ===================================================== --}}
                {{-- REPORT TABLE --}}
                {{-- ===================================================== --}}

                <div
                    class="min-w-0 overflow-hidden rounded-xl
                           border border-gray-200 bg-white shadow-sm"
                >

                    <div
                        class="flex flex-col gap-1 border-b border-gray-200
                               bg-gray-50 px-5 py-3 sm:flex-row
                               sm:items-center sm:justify-between"
                    >

                        <div>

                            <h3 class="text-sm font-bold text-gray-800">
                                {{ $selectedYear }} School-Level Report
                            </h3>

                            <p class="mt-0.5 text-xs text-gray-500">
                                Employees with Source of Fund = Plantilla
                            </p>

                        </div>

                        <span
                            class="inline-flex w-fit items-center rounded-md
                                   border border-green-200 bg-green-50
                                   px-2.5 py-1 text-xs font-semibold
                                   text-green-700"
                        >
                            {{ $selectedYear }}
                        </span>

                    </div>


                    <p
                        class="border-b border-gray-100 px-4 py-2
                               text-xs text-gray-500 lg:hidden"
                    >
                        Swipe left or right to view all report columns.
                    </p>


                    <div
                        class="w-full min-w-0 max-w-full overflow-x-auto
                               overscroll-x-contain"
                        tabindex="0"
                        role="region"
                        aria-label="School medical allowance report"
                    >

                        <table
                            class="w-full min-w-[1300px]
                                   divide-y divide-gray-200"
                        >

                            <thead>

                                <tr class="bg-gray-50">


                                    {{-- SCHOOL ID --}}
                                    <th
                                        class="px-5 py-3 text-left text-xs
                                               font-semibold uppercase
                                               tracking-wider text-gray-600"
                                    >
                                        School ID
                                    </th>


                                    {{-- SCHOOL NAME --}}
                                    <th
                                        class="px-5 py-3 text-left text-xs
                                               font-semibold uppercase
                                               tracking-wider text-gray-600"
                                    >
                                        School Name
                                    </th>


                                    {{-- DISTRICT --}}
                                    <th
                                        class="px-5 py-3 text-left text-xs
                                               font-semibold uppercase
                                               tracking-wider text-gray-600"
                                    >
                                        District Name
                                    </th>


                                    {{-- AREA --}}
                                    <th
                                        class="px-5 py-3 text-left text-xs
                                               font-semibold uppercase
                                               tracking-wider text-gray-600"
                                    >
                                        Area
                                    </th>


                                    {{-- PLANTILLA --}}
                                    <th
                                        class="bg-indigo-50 px-5 py-3
                                               text-center text-xs font-semibold
                                               uppercase tracking-wider
                                               text-indigo-700"
                                    >
                                        Plantilla
                                        <span
                                            class="mt-1 block text-[10px]
                                                   font-normal text-indigo-600"
                                        >
                                            Employees
                                        </span>
                                    </th>


                                    {{-- GROUP --}}
                                    <th
                                        class="px-5 py-3 text-center text-xs
                                               font-semibold uppercase
                                               tracking-wider text-gray-600"
                                    >
                                        Group Availment

                                        <span class="mt-1 block text-gray-500">
                                            (HMO)
                                        </span>
                                    </th>


                                    {{-- INDIVIDUAL --}}
                                    <th
                                        class="px-5 py-3 text-center text-xs
                                               font-semibold uppercase
                                               tracking-wider text-gray-600"
                                    >
                                        Individual Availment

                                        <span class="mt-1 block text-gray-500">
                                            (HMO)
                                        </span>
                                    </th>


                                    {{-- TOTAL ELIGIBLE --}}
                                    <th
                                        class="border-x border-green-200
                                               bg-green-100 px-4 py-3
                                               text-center"
                                    >

                                        <span
                                            class="block text-xs font-bold
                                                   uppercase tracking-wide
                                                   text-green-800"
                                        >
                                            Total Eligible
                                        </span>

                                        <span
                                            class="mt-1 block whitespace-nowrap
                                                   text-xs font-normal
                                                   text-green-700"
                                        >
                                            Group + Individual
                                        </span>

                                    </th>


                                    {{-- NOT ELIGIBLE --}}
                                    <th
                                        class="px-5 py-3 text-center text-xs
                                               font-semibold uppercase
                                               tracking-wider text-gray-600"
                                    >
                                        Not Eligible
                                    </th>


                                    {{-- NO RECORD --}}
                                    <th
                                        class="bg-orange-50 px-5 py-3
                                               text-center text-xs font-semibold
                                               uppercase tracking-wider
                                               text-orange-700"
                                    >
                                        No Record

                                        <span
                                            class="mt-1 block text-[10px]
                                                   font-normal text-orange-600"
                                        >
                                            {{ $selectedYear }}
                                        </span>
                                    </th>


                                </tr>

                            </thead>



                            <tbody class="divide-y divide-gray-200">

                                @forelse($reports as $report)

                                    @php

                                        $schoolTotalEligible =
                                            (int) $report->group_hmo
                                            +
                                            (int) $report->individual_hmo;

                                    @endphp


                                    <tr
                                        class="group transition-colors
                                               hover:bg-gray-50"
                                    >


                                        {{-- SCHOOL ID --}}
                                        <td
                                            class="whitespace-nowrap px-5 py-4
                                                   text-sm font-semibold
                                                   text-gray-800"
                                        >
                                            {{ $report->school_id }}
                                        </td>


                                        {{-- SCHOOL NAME --}}
                                        <td
                                            class="min-w-[280px] px-5 py-4
                                                   text-sm font-semibold
                                                   text-gray-900"
                                        >
                                            {{ $report->school_name }}
                                        </td>


                                        {{-- DISTRICT --}}
                                        <td
                                            class="whitespace-nowrap px-5 py-4
                                                   text-sm text-gray-600"
                                        >
                                            {{ $report->school_district ?: '—' }}
                                        </td>


                                        {{-- AREA --}}
                                        <td
                                            class="whitespace-nowrap px-5 py-4
                                                   text-sm text-gray-600"
                                        >
                                            {{ $report->school_area ?: '—' }}
                                        </td>


                                        {{-- TOTAL PLANTILLA EMPLOYEE --}}
                                        <td
                                            class="bg-indigo-50/50 px-5 py-4
                                                   text-center text-sm
                                                   font-bold tabular-nums
                                                   text-indigo-700"
                                        >
                                            {{ number_format(
                                                $report->total_plantilla_employee
                                            ) }}
                                        </td>


                                        {{-- GROUP HMO --}}
                                        <td
                                            class="px-5 py-4 text-center
                                                   text-sm font-semibold
                                                   tabular-nums text-blue-700"
                                        >
                                            {{ number_format(
                                                $report->group_hmo
                                            ) }}
                                        </td>


                                        {{-- INDIVIDUAL HMO --}}
                                        <td
                                            class="px-5 py-4 text-center
                                                   text-sm font-semibold
                                                   tabular-nums text-purple-700"
                                        >
                                            {{ number_format(
                                                $report->individual_hmo
                                            ) }}
                                        </td>


                                        {{-- TOTAL ELIGIBLE --}}
                                        <td
                                            class="border-x border-green-200
                                                   bg-green-50 px-4 py-3
                                                   text-center transition-colors
                                                   group-hover:bg-green-100"
                                        >

                                            <span
                                                class="inline-flex items-center
                                                       justify-center rounded-md
                                                       bg-green-100 px-3 py-1
                                                       text-sm font-bold
                                                       tabular-nums
                                                       text-green-800"
                                            >
                                                {{ number_format(
                                                    $schoolTotalEligible
                                                ) }}
                                            </span>

                                        </td>


                                        {{-- NOT ELIGIBLE --}}
                                        <td
                                            class="px-5 py-4 text-center
                                                   text-sm font-medium
                                                   tabular-nums text-red-600"
                                        >
                                            {{ number_format(
                                                $report->not_eligible
                                            ) }}
                                        </td>


                                        {{-- NO RECORD --}}
                                        <td
                                            class="bg-orange-50/50 px-5 py-4
                                                   text-center text-sm
                                                   font-semibold tabular-nums
                                                   text-orange-600"
                                        >
                                            {{ number_format(
                                                $report->no_record
                                            ) }}
                                        </td>


                                    </tr>


                                @empty

                                    <tr>

                                        <td
                                            colspan="10"
                                            class="px-4 py-12 text-center
                                                   text-sm text-gray-500
                                                   sm:px-6"
                                        >

                                            No {{ $selectedYear }} medical allowance
                                            report records found.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>



                    {{-- ================================================= --}}
                    {{-- PAGINATION --}}
                    {{-- ================================================= --}}

                    <div
                        class="border-t border-gray-200 bg-gray-50
                               px-4 py-4 sm:px-6"
                    >

                        <div
                            class="flex flex-col gap-3
                                   md:flex-row md:items-center
                                   md:justify-between"
                        >

                            <p class="text-sm text-gray-500">

                                Showing

                                <span class="font-semibold text-gray-700">
                                    {{ $reports->firstItem() ?? 0 }}
                                </span>

                                to

                                <span class="font-semibold text-gray-700">
                                    {{ $reports->lastItem() ?? 0 }}
                                </span>

                                of

                                <span class="font-semibold text-gray-700">
                                    {{ $reports->total() }}
                                </span>

                                schools for {{ $selectedYear }}.

                            </p>


                            <div>
                                {{ $reports->links() }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- ========================================================= --}}
            {{-- GENERAL ERROR --}}
            {{-- ========================================================= --}}

            @if(session('error'))

                <div
                    class="mt-6 rounded-xl border border-red-200
                           bg-red-50 p-4 sm:p-5"
                >

                    <p class="font-semibold text-red-800">
                        {{ session('error') }}
                    </p>

                </div>

            @endif



            {{-- ========================================================= --}}
            {{-- SUCCESS MESSAGE --}}
            {{-- ========================================================= --}}

            @if(session('success'))

                <div
                    class="mt-6 rounded-xl border border-green-200
                           bg-green-50 p-4 sm:p-5"
                >

                    <p class="font-semibold text-green-800">
                        {{ session('success') }}
                    </p>

                </div>

            @endif



            {{-- ========================================================= --}}
            {{-- MEDICAL ALLOWANCE IMPORT RESULT --}}
            {{-- ========================================================= --}}

            @if(session('medical_allowance_import_result'))

                <div
                    class="mt-6 rounded-xl border border-green-200
                           bg-green-50 p-4 sm:p-5"
                >

                    <h3 class="text-lg font-bold text-green-900">
                        Medical Allowance Import Completed
                    </h3>


                    <div class="mt-4 grid grid-cols-2 gap-4 lg:grid-cols-4">


                        {{-- NEW RECORDS --}}
                        <div>

                            <p class="text-sm text-gray-500">
                                New Records
                            </p>

                            <p class="text-2xl font-bold text-green-700">
                                {{
                                    session(
                                        'medical_allowance_import_result.imported'
                                    )
                                }}
                            </p>

                        </div>


                        {{-- UPDATED --}}
                        <div>

                            <p class="text-sm text-gray-500">
                                Updated Records
                            </p>

                            <p class="text-2xl font-bold text-blue-700">
                                {{
                                    session(
                                        'medical_allowance_import_result.updated'
                                    )
                                }}
                            </p>

                        </div>


                        {{-- SKIPPED --}}
                        <div>

                            <p class="text-sm text-gray-500">
                                Skipped
                            </p>

                            <p class="text-2xl font-bold text-yellow-600">
                                {{
                                    session(
                                        'medical_allowance_import_result.skipped'
                                    )
                                }}
                            </p>

                        </div>


                        {{-- ERRORS --}}
                        <div>

                            <p class="text-sm text-gray-500">
                                Errors
                            </p>

                            <p class="text-2xl font-bold text-red-600">
                                {{
                                    count(
                                        session(
                                            'medical_allowance_import_result.errors',
                                            []
                                        )
                                    )
                                }}
                            </p>

                        </div>

                    </div>



                    {{-- ERROR DETAILS --}}
                    @if(
                        count(
                            session(
                                'medical_allowance_import_result.errors',
                                []
                            )
                        ) > 0
                    )

                        <div
                            class="mt-5 rounded-lg border border-red-200
                                   bg-red-50 p-4"
                        >

                            <h4 class="font-semibold text-red-800">
                                Import Errors
                            </h4>


                            <ul
                                class="mt-2 list-disc pl-5
                                       text-sm text-red-700"
                            >

                                @foreach(
                                    session(
                                        'medical_allowance_import_result.errors',
                                        []
                                    ) as $error
                                )

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


        </div>

    </div>

</x-app-layout>