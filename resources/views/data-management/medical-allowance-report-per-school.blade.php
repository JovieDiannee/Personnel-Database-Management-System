
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

                {{-- SUMMARY CARDS --}}
                <div class="mb-6 grid gap-4 md:grid-cols-2">

                    {{-- TOTAL SCHOOLS --}}
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                        <p class="text-sm text-gray-500">
                            Schools
                        </p>

                        <p class="mt-1 text-3xl font-bold text-green-700">
                            {{ $reports->total() }}
                        </p>

                    </div>


                    {{-- TOTAL RECORDS ON CURRENT PAGE --}}
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                        <p class="text-sm text-gray-500">
                            Records on Current Page
                        </p>

                        <p class="mt-1 text-3xl font-bold text-blue-700">
                            {{ $reports->count() }}
                        </p>

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- REPORT HEADER + FILTER --}}
                {{-- ===================================================== --}}

                <div class="mb-5 overflow-hidden rounded-2xl shadow-md">

                    <div
                        style="background: linear-gradient(135deg, #166534 0%, #15803d 55%, #059669 100%);"
                        class="px-5 py-3"
                    >

                        <div
                            class="flex items-center justify-between gap-4"
                            style="display: flex; flex-direction: row;"
                        >

                            {{-- ================================================= --}}
                            {{-- LEFT SIDE : TITLE --}}
                            {{-- ================================================= --}}

                            <div class="min-w-0 flex-1">

                                <h2 class="text-lg font-bold leading-tight text-white">
                                    Medical Allowance Summary
                                </h2>

                                <p class="mt-0.5 text-xs text-green-100">
                                    Medical allowance availment summarized by school.
                                </p>

                            </div>


                            {{-- ================================================= --}}
                            {{-- RIGHT SIDE : SEARCH + FILTER --}}
                            {{-- ================================================= --}}

                            <form
                                method="GET"
                                action="{{ route('data-management.medical-allowance.report') }}"
                                class="shrink-0"
                            >

                                <div
                                    class="flex items-end gap-1.5"
                                    style="display: flex; flex-direction: row;"
                                    >

                                    {{-- SEARCH --}}
                                    <div style="width: 185px;">

                                        <label
                                            for="search"
                                            class="mb-0.5 block text-[11px] font-medium text-white"
                                        >
                                            Search
                                        </label>

                                        <input
                                            type="text"
                                            id="search"
                                            name="search"
                                            value="{{ $search }}"
                                            placeholder="School ID or name..."
                                            class="h-8 w-full rounded-md
                                                border-0
                                                bg-white
                                                px-2.5
                                                text-xs
                                                text-gray-700
                                                shadow-sm
                                                focus:outline-none
                                                focus:ring-1
                                                focus:ring-white"
                                        >

                                    </div>


                                    {{-- DISTRICT --}}
                                    <div style="width: 145px;">

                                        <label
                                            for="district"
                                            class="mb-0.5 block text-[11px] font-medium text-white"
                                        >
                                            District
                                        </label>

                                        <select
                                            id="district"
                                            name="district"
                                            class="h-8 w-full rounded-md
                                                border-0
                                                bg-white
                                                px-2.5
                                                text-xs
                                                text-gray-700
                                                shadow-sm
                                                focus:outline-none
                                                focus:ring-1
                                                focus:ring-white"
                                        >

                                            <option value="">
                                                All Districts
                                            </option>

                                            @foreach($districts as $districtName)

                                                <option
                                                    value="{{ $districtName }}"
                                                    @selected($district === $districtName)
                                                >
                                                    {{ $districtName }}
                                                </option>

                                            @endforeach

                                        </select>

                                    </div>


                                    {{-- BUTTONS --}}
<div class="flex items-end gap-2">
    {{-- SEARCH --}}
    <button
        type="submit"
        class="h-10 rounded-lg
               bg-white
               px-4
               text-xs
               font-semibold
               text-green-800
               shadow-sm
               transition-all
               duration-200
               hover:bg-green-50
               hover:shadow-md
               focus:outline-none
               focus:ring-2
               focus:ring-white/50"
    >
        Search
    </button>

    {{-- RESET --}}
    <a
        href="{{ route('data-management.medical-allowance.report') }}"
        class="flex h-10 items-center justify-center
               rounded-lg
               border border-white/50
               bg-white/10
               px-4
               text-xs
               font-semibold
               text-white
               transition-all
               duration-200
               hover:bg-white
               hover:text-green-800
               hover:shadow-md"
    >
        Reset
    </a>
</div>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- REPORT TABLE --}}
                {{-- ===================================================== --}}

                <div class="overflow-hidden rounded-xl
                            border border-gray-200
                            bg-white shadow-sm">

                    <div class="overflow-x-auto">

                        <table class="min-w-[1200px] w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-5 py-3 text-left text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        School ID
                                    </th>

                                    <th class="px-5 py-3 text-left text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        School Name
                                    </th>

                                    <th class="px-5 py-3 text-left text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        District Name
                                    </th>

                                    <th class="px-5 py-3 text-left text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        Area
                                    </th>

                                    <th class="px-5 py-3 text-center text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        Group Availment
                                        <br>
                                        (HMO)
                                    </th>

                                    <th class="px-5 py-3 text-center text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        Individual Availment
                                        <br>
                                        (HMO)
                                    </th>

                                    <th class="px-5 py-3 text-center text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        Individual Availment
                                        <br>
                                        (Medical Expenses)
                                    </th>

                                    <th class="px-5 py-3 text-center text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        Total Eligible
                                        <br>
                                        Employee
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-200 bg-white">

                                @forelse($reports as $report)

                                    <tr class="transition hover:bg-green-50">

                                        <td class="whitespace-nowrap px-5 py-4
                                                text-sm font-semibold text-gray-800">
                                            {{ $report->school_id }}
                                        </td>

                                        <td class="min-w-[280px] px-5 py-4
                                                text-sm font-medium text-gray-800">
                                            {{ $report->school_name }}
                                        </td>

                                        <td class="whitespace-nowrap px-5 py-4
                                                text-sm text-gray-700">
                                            {{ $report->school_district ?: '—' }}
                                        </td>

                                        <td class="whitespace-nowrap px-5 py-4
                                                text-sm text-gray-700">
                                            {{ $report->school_area ?: '—' }}
                                        </td>

                                        <td class="px-5 py-4 text-center
                                                text-sm font-semibold text-green-700">
                                            {{ $report->group_hmo }}
                                        </td>

                                        <td class="px-5 py-4 text-center
                                                text-sm font-semibold text-blue-700">
                                            {{ $report->individual_hmo }}
                                        </td>

                                        <td class="px-5 py-4 text-center
                                                text-sm font-semibold text-purple-700">
                                            {{ $report->individual_medical }}
                                        </td>

                                        <td class="px-5 py-4 text-center
                                                text-sm font-bold text-gray-900">
                                            {{ $report->total_eligible_employee }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="8"
                                            class="px-6 py-10 text-center
                                                text-sm text-gray-500"
                                        >
                                            No medical allowance records found.
                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- PAGINATION --}}
                    <div class="border-t border-gray-200 px-6 py-4">

                        <div class="flex flex-col gap-3
                                    md:flex-row
                                    md:items-center
                                    md:justify-between">

                            <p class="text-sm text-gray-500">

                                Showing

                                <span class="font-medium text-gray-700">
                                    {{ $reports->firstItem() ?? 0 }}
                                </span>

                                to

                                <span class="font-medium text-gray-700">
                                    {{ $reports->lastItem() ?? 0 }}
                                </span>

                                of

                                <span class="font-medium text-gray-700">
                                    {{ $reports->total() }}
                                </span>

                                schools.

                            </p>

                            <div>
                                {{ $reports->links() }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>
            <br>

            
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


        </div>

    </div>

</x-app-layout>