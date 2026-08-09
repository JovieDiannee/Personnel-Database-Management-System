<x-app-layout>

    <div class="min-h-screen bg-gray-50 py-8">

        <div class="mx-auto max-w-7xl px-6">

            <form action="{{ route('data-management.personnel.import.confirm') }}" method="POST">
                @csrf
                
                {{-- =========================================================
                    PAGE HEADER
                ========================================================== --}}

                <div class="relative overflow-hidden rounded-2xl
                            bg-gradient-to-br from-green-950
                            via-green-900 to-green-800
                            px-6 py-8 text-white shadow-lg">

                    <div class="flex items-center justify-between gap-6">

                        <div>

                            <h1 class="text-2xl font-bold tracking-tight">
                                Personnel Information
                            </h1>

                            <p class="mt-1 text-sm text-green-100">
                                Review and verify personnel records before
                                importing them into the system.
                            </p>

                        </div>


                        <a
                            href="{{ route('data-management.personnel') }}"
                            class="shrink-0 rounded-md border border-white/30
                                bg-white/10 px-4 py-2 text-sm
                                font-semibold text-white
                                backdrop-blur-sm transition duration-200
                                hover:bg-white hover:text-green-800"
                        >
                            ← Back
                        </a>

                    </div>

                </div>


                {{-- =========================================================
                    IMPORT SUMMARY
                ========================================================== --}}

                <div class="mt-6 grid gap-4 sm:grid-cols-2">

                    {{-- Total Records --}}
                    <div class="rounded-xl border border-gray-200
                                bg-white p-5 shadow-sm">

                        <p class="text-sm font-medium text-gray-500">
                            Total Records
                        </p>

                        <p class="mt-1 text-2xl font-bold text-green-800">
                            {{ count($rows) }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Personnel records detected from the Excel file.
                        </p>

                    </div>


                    {{-- Validation Errors --}}
                    <div class="rounded-xl border border-gray-200
                                bg-white p-5 shadow-sm">

                        <p class="text-sm font-medium text-gray-500">
                            Validation Errors
                        </p>

                        <p class="mt-1 text-2xl font-bold
                                {{ count($errors) > 0
                                        ? 'text-red-600'
                                        : 'text-green-700'
                                }}">
                            {{ count($errors) }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Records requiring attention before import.
                        </p>

                    </div>

                </div>


                {{-- =========================================================
                    VALIDATION ERRORS
                ========================================================== --}}

                @if (count($errors) > 0)

                    <div class="mt-6 rounded-xl border border-red-200
                                bg-red-50 p-5">

                        <div class="flex items-start gap-3">

                            <div class="mt-0.5 text-red-600">
                                ⚠
                            </div>

                            <div>

                                <h2 class="font-bold text-red-800">
                                    Validation Errors
                                </h2>

                                <p class="mt-1 text-sm text-red-700">
                                    Please review the following issues before
                                    proceeding with the import.
                                </p>

                            </div>

                        </div>


                        <ul class="mt-4 space-y-2 text-sm text-red-700">

                            @foreach ($errors as $error)

                                <li class="rounded-md bg-white/70 px-3 py-2">
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                {{-- =========================================================
                    PREVIEW TABLE
                ========================================================== --}}

                <div class="mt-6 overflow-hidden rounded-xl
                            border border-gray-200 bg-white shadow-sm">


                    {{-- TABLE TITLE --}}
                    <div class="border-b border-gray-200 px-6 py-5">

                        <div class="flex items-center justify-between gap-4">

                            <div>

                                <h2 class="text-lg font-bold text-gray-900">
                                    Personnel Records Preview
                                </h2>

                                <p class="mt-1 text-sm text-gray-500">
                                    Review all personnel information extracted
                                    from the uploaded Excel file.
                                </p>

                            </div>

                            <div class="hidden rounded-md bg-green-50 px-3 py-2
                                        text-xs font-semibold text-green-800 sm:block">

                                {{ count($rows) }} record(s)

                            </div>

                        </div>

                    </div>


                    {{-- =====================================================
                        HORIZONTAL SCROLL AREA
                    ====================================================== --}}

                    <div class="overflow-x-auto">

                        <table class="min-w-[2400px] divide-y divide-gray-200">

                            {{-- =================================================
                                TABLE HEADER
                            ================================================== --}}

                            <thead class="bg-gray-50">

                                {{-- GROUP HEADERS --}}
                                <tr class="border-b border-gray-200">

                                    <th
                                        colspan="4"
                                        class="border-r border-gray-300
                                            bg-green-50 px-4 py-3 text-left
                                            text-xs font-bold uppercase
                                            tracking-wider text-green-900"
                                    >
                                        Account Information
                                    </th>

                                    <th
                                        colspan="10"
                                        class="border-r border-gray-300
                                            bg-green-50 px-4 py-3 text-left
                                            text-xs font-bold uppercase
                                            tracking-wider text-green-900"
                                    >
                                        Personal Information
                                    </th>

                                    <th
                                        colspan="3"
                                        class="border-r border-gray-300
                                            bg-green-50 px-4 py-3 text-left
                                            text-xs font-bold uppercase
                                            tracking-wider text-green-900"
                                    >
                                        Physical Information
                                    </th>

                                    <th
                                        colspan="2"
                                        class="border-r border-gray-300
                                            bg-green-50 px-4 py-3 text-left
                                            text-xs font-bold uppercase
                                            tracking-wider text-green-900"
                                    >
                                        Contact Information
                                    </th>

                                    <th
                                        colspan="1"
                                        class="bg-green-50 px-4 py-3 text-left
                                            text-xs font-bold uppercase
                                            tracking-wider text-green-900"
                                    >
                                        Professional Information
                                    </th>

                                </tr>


                                {{-- COLUMN HEADERS --}}
                                <tr>

                                    {{-- Account --}}
                                    <th class="sticky left-0 z-10
                                            bg-gray-50 px-4 py-3 text-left
                                            text-xs font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        #
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        Email
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        Default Password
                                    </th>

                                    <th class="border-r border-gray-300
                                            px-4 py-3 text-left text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        Account Role
                                    </th>


                                    {{-- Personal --}}
                                    <th class="px-4 py-3 text-left text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        First Name
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        Middle Name
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        Last Name
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        Extension
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        Sex
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        Birth Place
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        Birth Date
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        Civil Status
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        Religion
                                    </th>

                                    <th class="border-r border-gray-300
                                            px-4 py-3 text-left text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        Citizenship
                                    </th>


                                    {{-- Physical --}}
                                    <th class="px-4 py-3 text-left text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        Height (m)
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        Weight (kg)
                                    </th>

                                    <th class="border-r border-gray-300
                                            px-4 py-3 text-left text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        Blood Type
                                    </th>


                                    {{-- Contact --}}
                                    <th class="px-4 py-3 text-left text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        Mobile Number
                                    </th>

                                    <th class="border-r border-gray-300
                                            px-4 py-3 text-left text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        Telephone Number
                                    </th>


                                    {{-- Professional --}}
                                    <th class="px-4 py-3 text-left text-xs
                                            font-semibold uppercase
                                            tracking-wider text-gray-600">
                                        Specialization
                                    </th>

                                </tr>

                            </thead>


                            {{-- =================================================
                                TABLE BODY
                            ================================================== --}}

                            <tbody class="divide-y divide-gray-100 bg-white">

                                @forelse ($rows as $row)

                                    <tr class="transition hover:bg-green-50/40">


                                        {{-- =================================================
                                            ACCOUNT INFORMATION
                                        ================================================== --}}

                                        <td class="sticky left-0 z-[1]
                                                whitespace-nowrap bg-white
                                                px-4 py-3 text-sm font-semibold
                                                text-gray-600">

                                            {{ $row['excel_row'] }}

                                        </td>


                                        <td class="whitespace-nowrap px-4 py-3
                                                text-sm text-gray-700">

                                            {{ $row['email'] }}

                                        </td>


                                        <td class="whitespace-nowrap px-4 py-3
                                                text-sm font-semibold text-gray-700">

                                            {{ $row['birth_date']
                                                ? \Carbon\Carbon::parse(
                                                    $row['birth_date']
                                                )->format('mdY')
                                                : ''
                                            }}

                                        </td>


                                        <td class="border-r border-gray-200
                                                whitespace-nowrap px-4 py-3
                                                text-sm font-semibold
                                                text-green-700">

                                            user

                                        </td>


                                        {{-- =================================================
                                            PERSONAL INFORMATION
                                        ================================================== --}}

                                        <td class="whitespace-nowrap px-4 py-3
                                                text-sm text-gray-700">

                                            {{ $row['first_name'] }}

                                        </td>


                                        <td class="whitespace-nowrap px-4 py-3
                                                text-sm text-gray-700">

                                            {{ $row['middle_name'] }}

                                        </td>


                                        <td class="whitespace-nowrap px-4 py-3
                                                text-sm font-semibold
                                                text-gray-900">

                                            {{ $row['last_name'] }}

                                        </td>


                                        <td class="whitespace-nowrap px-4 py-3
                                                text-sm text-gray-700">

                                            {{ $row['extension_name'] }}

                                        </td>


                                        <td class="whitespace-nowrap px-4 py-3
                                                text-sm text-gray-700">

                                            {{ $row['sex'] }}

                                        </td>


                                        <td class="whitespace-nowrap px-4 py-3
                                                text-sm text-gray-700">

                                            {{ $row['birth_place'] }}

                                        </td>


                                        <td class="whitespace-nowrap px-4 py-3
                                                text-sm text-gray-700">

                                            {{ $row['birth_date'] }}

                                        </td>


                                        <td class="whitespace-nowrap px-4 py-3
                                                text-sm text-gray-700">

                                            {{ $row['civil_status'] }}

                                        </td>


                                        <td class="whitespace-nowrap px-4 py-3
                                                text-sm text-gray-700">

                                            {{ $row['religion'] }}

                                        </td>


                                        <td class="border-r border-gray-200
                                                whitespace-nowrap px-4 py-3
                                                text-sm text-gray-700">

                                            {{ $row['citizenship'] }}

                                        </td>


                                        {{-- =================================================
                                            PHYSICAL INFORMATION
                                        ================================================== --}}

                                        <td class="whitespace-nowrap px-4 py-3
                                                text-sm text-gray-700">

                                            {{ $row['height_m'] }}

                                        </td>


                                        <td class="whitespace-nowrap px-4 py-3
                                                text-sm text-gray-700">

                                            {{ $row['weight_kg'] }}

                                        </td>


                                        <td class="border-r border-gray-200
                                                whitespace-nowrap px-4 py-3
                                                text-sm text-gray-700">

                                            {{ $row['blood_type'] }}

                                        </td>


                                        {{-- =================================================
                                            CONTACT INFORMATION
                                        ================================================== --}}

                                        <td class="whitespace-nowrap px-4 py-3
                                                text-sm text-gray-700">

                                            {{ $row['mobile_number'] }}

                                        </td>


                                        <td class="border-r border-gray-200
                                                whitespace-nowrap px-4 py-3
                                                text-sm text-gray-700">

                                            {{ $row['telephone_number'] }}

                                        </td>


                                        {{-- =================================================
                                            PROFESSIONAL INFORMATION
                                        ================================================== --}}

                                        <td class="whitespace-nowrap px-4 py-3
                                                text-sm text-gray-700">

                                            {{ $row['specialization'] }}

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="20"
                                            class="px-6 py-12 text-center
                                                text-sm text-gray-500"
                                        >
                                            No personnel records were found
                                            in the uploaded Excel file.
                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- =========================================================
                        TABLE FOOTER
                    ========================================================== --}}

                    <div class="border-t border-gray-200 bg-gray-50
                                px-6 py-4">

                        <div class="flex flex-col gap-2 text-sm text-gray-600
                                    sm:flex-row sm:items-center
                                    sm:justify-between">

                            <span>
                                Showing
                                <strong class="text-gray-900">
                                    {{ count($rows) }}
                                </strong>
                                personnel record(s).
                            </span>

                            <span class="text-xs text-gray-500">
                                Scroll horizontally to view all information.
                            </span>

                        </div>

                    </div>

                </div>


                {{-- =========================================================
                    ACTIONS
                ========================================================== --}}

                <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row
                            sm:justify-end">

                    {{-- Cancel --}}
                    <a
                        href="{{ route('data-management.personnel') }}"
                        class="rounded-lg border border-gray-300
                            bg-white px-5 py-2.5 text-center
                            text-sm font-semibold text-gray-700
                            transition duration-200
                            hover:bg-gray-50"
                    >
                        Cancel
                    </a>


                    {{-- Confirm Import --}}
                    @if (count($errors) === 0 && count($rows) > 0)

                        <button
                            type="submit"
                            class="rounded-lg bg-green-700 px-6 py-2.5
                                text-sm font-semibold text-white
                                shadow-sm transition duration-200
                                hover:bg-green-800
                                focus:outline-none
                                focus:ring-2 focus:ring-green-500
                                focus:ring-offset-2"
                        >
                            Confirm Import
                        </button>

                    @endif

                </div>


                {{-- =========================================================
                    IMPORT NOTICE
                ========================================================== --}}

                @if (count($errors) === 0 && count($rows) > 0)

                    <div class="mt-4 rounded-lg border border-green-200
                                bg-green-50 px-4 py-3">

                        <p class="text-sm text-green-800">

                            <span class="font-semibold">
                                Import Ready:
                            </span>

                            Please review the records carefully before clicking
                            <strong>Confirm Import</strong>.
                            Once confirmed, the records will be saved to the
                            personnel database.

                        </p>

                    </div>

                @endif
            </form>
        </div>

    </div>

</x-app-layout>