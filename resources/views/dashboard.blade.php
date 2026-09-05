<x-app-layout>



{{-- =====================================================
    DASHBOARD CONTENT
====================================================== --}}
<div class="min-h-screen bg-gradient-to-br from-green-50 via-gray-50 to-emerald-100 py-8">

    <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8" >


        {{-- =====================================================
            WELCOME SECTION
        ====================================================== --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-950 via-green-900 to-green-800 p-6 text-white shadow-lg">

            <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">

                <div>

                    <p class="text-sm font-medium text-green-100">
                        Welcome back,
                    </p>

                    <h1 class="mt-1 text-2xl font-bold">
                        {{ auth()->user()->name }}
                    </h1>

                    <p class="mt-2 text-sm text-green-100">
                        Here is the current overview of personnel and HR operations.
                    </p>

                </div>


                <div class="rounded-xl bg-white/10 px-5 py-4 backdrop-blur-sm">

                    <p class="text-xs uppercase tracking-wider text-green-100">
                        User Role
                    </p>

                    <p class="mt-1 text-lg font-bold">
                        {{ ucwords(str_replace('_', ' ', auth()->user()->role ?? 'User')) }}
                    </p>

                </div>

            </div>

        </div>

        @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')

            {{-- =====================================================
                PERSONNEL AND SCHOOL STATISTICS
            ====================================================== --}}
            <div>
                <div class="mb-4 text-center">

                    <h3 class="text-lg font-bold text-gray-800">
                        PERSONNEL AND SCHOOL STATISTICS
                    </h3>

                    <p class="text-sm text-gray-500">
                        Summary of current personnel and school records
                    </p>

                </div>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">


                    {{-- PLANTILLA-BASED EMPLOYEES --}}
                    <div class="rounded-2xl border-l-4 border-green-600 bg-white p-6 shadow-sm ring-1 ring-green-100">

                        <div class="flex items-start justify-between">

                            <div>

                                <p class="text-sm font-medium text-gray-500">
                                    Plantilla-Based Employees
                                </p>

                                <p class="mt-3 text-4xl font-bold text-green-800">
                                    {{ number_format($plantillaEmployees) }}
                                </p>

                            </div>

                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-green-700"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 21a8 8 0 0116 0"
                                    />
                                </svg>

                            </div>

                        </div>

                        <div class="mt-4 border-t border-gray-100 pt-3">

                            <a
                                href="{{ route('data-management.employment-status') }}"
                                class="text-sm font-medium text-green-700 hover:text-green-900"
                            >
                                View personnel →
                            </a>

                        </div>

                    </div>


                    {{-- OTHER FUNDS --}}
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

                        <div class="flex items-start justify-between">

                            <div>

                                <p class="text-sm font-medium text-gray-500">
                                    Other Funds Employees
                                </p>

                                <p class="mt-3 text-4xl font-bold text-green-800">
                                    {{ number_format($otherFundsEmployees) }}
                                </p>

                            </div>

                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-blue-700"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2m0-10a9 9 0 100 18 9 9 0 000-18z"
                                    />
                                </svg>

                            </div>

                        </div>

                        <div class="mt-4 border-t border-gray-100 pt-3">

                            <a
                                href="{{ route('data-management.personnel') }}"
                                class="text-sm font-medium text-blue-700 hover:text-blue-900"
                            >
                                View personnel →
                            </a>

                        </div>

                    </div>


                    {{-- NUMBER OF SCHOOLS --}}
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

                        <div class="flex items-start justify-between">

                            <div>

                                <p class="text-sm font-medium text-gray-500">
                                    Number of Schools
                                </p>

                                <p class="mt-3 text-4xl font-bold text-green-800">
                                    {{ number_format($numberOfSchools) }}
                                </p>

                            </div>

                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-amber-700"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-5h6v5M8 10h.01M12 10h.01M16 10h.01"
                                    />
                                </svg>

                            </div>

                        </div>

                        <div class="mt-4 border-t border-gray-100 pt-3">

                            <a
                                href="{{ route('data-management.schools') }}"
                                class="text-sm font-medium text-amber-700 hover:text-amber-900"
                            >
                                View schools →
                            </a>

                        </div>

                    </div>


                    {{-- HR TRANSACTIONS --}}
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

                        <div class="flex items-start justify-between">

                            <div>

                                <p class="text-sm font-medium text-gray-500">
                                    HR Transactions
                                </p>

                                <p class="mt-3 text-4xl font-bold text-green-800">
                                    {{ number_format($hrTransactions) }}
                                </p>

                            </div>

                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-purple-700"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l4 4v12a2 2 0 01-2 2z"
                                    />
                                </svg>

                            </div>

                        </div>

                        <div class="mt-4 border-t border-gray-100 pt-3">

                            <a
                                href="#"
                                class="text-sm font-medium text-purple-700 hover:text-purple-900"
                            >
                                View transactions →
                            </a>

                        </div>

                    </div>

                </div>

            </div>

            {{-- =====================================================
                MEDICAL ALLOWANCE REPORT
            ====================================================== --}}

            <div>

                {{-- SECTION TITLE --}}
                <div class="relative overflow-hidden rounded-2xl
                            bg-gradient-to-br from-green-950 via-green-900 to-green-800
                            px-6 py-4 text-white shadow-lg">

                    <div class="text-center">

                        <h3 class="text-xl font-bold uppercase tracking-wide text-white">
                            Medical Allowance Report School Year 2026–2027
                        </h3>

                        <p class="mt-1 text-sm text-green-100">
                            Summary of personnel medical allowance availments
                        </p>

                    </div>

                </div>


                {{-- DEADLINE NOTICE --}}
                <div class="mt-3 flex items-center justify-center rounded-xl
                            border border-red-200 bg-red-50 px-5 py-3
                            shadow-sm">

                    <div class="flex items-center gap-3">

                        {{-- CLOCK ICON --}}
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center
                                    rounded-full bg-red-100 text-red-600">

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
                                    d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>

                        </div>

                        <div class="text-sm text-red-700">

                            <span class="font-medium">
                                Deadline for Updating:
                            </span>

                            <span class="ml-1 font-bold">
                                September 9, 2026 • 5:00 P.M.
                            </span>

                        </div>

                    </div>

                </div>

                <br>
                

                {{-- 3 CARDS IN ONE ROW --}}
                <div class="flex w-full flex-nowrap gap-6">

                    {{-- =================================================
                        GROUP AVAILMENT
                    ================================================== --}}
                    <a
                        href="{{ route('data-management.medical-allowance') }}"
                        class="group block min-w-0 flex-1 cursor-pointer rounded-2xl bg-white p-5 shadow-sm
                            ring-1 ring-gray-100 transition duration-300
                            hover:-translate-y-1 hover:shadow-lg hover:ring-green-200
                            focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                        aria-label="View medical allowance records"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-500">
                                    Group Availment (HMO)
                                </p>

                                <p class="mt-2 text-3xl font-bold text-green-700">
                                    {{ number_format($groupAvailment) }}
                                </p>

                                <p class="mt-1 text-xs text-gray-400">
                                    Personnel enrolled in the group medical program
                                </p>
                            </div>

                            {{-- ICON --}}
                            <div
                                class="flex h-12 w-12 shrink-0 items-center justify-center
                                    rounded-xl bg-green-100 text-green-700"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    aria-hidden="true"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 21h18M5 21V7a2 2 0 012-2h2v4h6V5h2a2 2 0 012 2v14M9 21v-4h6v4"
                                    />
                                </svg>
                            </div>
                        </div>
                    </a>

                    {{-- =================================================
                        INDIVIDUAL AVAILMENT HMO
                    ================================================== --}}
                    <a
                        href="{{ route('data-management.medical-allowance') }}"
                        class="group block min-w-0 flex-1 cursor-pointer rounded-2xl bg-white p-5 shadow-sm
                            ring-1 ring-gray-100 transition duration-300
                            hover:-translate-y-1 hover:shadow-lg hover:ring-blue-200
                            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        aria-label="View medical allowance records"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-500">
                                    Individual Availment (HMO)
                                </p>

                                <p class="mt-2 text-3xl font-bold text-blue-600">
                                    {{ number_format($individualAvailment) }}
                                </p>

                                <p class="mt-1 text-xs text-gray-400">
                                    Personnel utilizing HMO medical benefits
                                </p>
                            </div>

                            {{-- ICON --}}
                            <div
                                class="flex h-12 w-12 shrink-0 items-center justify-center
                                    rounded-xl bg-blue-100 text-blue-600"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    aria-hidden="true"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M20 8h-3V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2H4a2 2 0 00-2 2v8a2 2 0 002 2h16a2 2 0 002-2v-8a2 2 0 00-2-2zM9 6h6v2H9V6zm3 5v2m-2-1h4"
                                    />
                                </svg>
                            </div>
                        </div>
                    </a>

                    {{-- =================================================
                        MEDICAL ALLOWANCE RECEIVED - CLICKABLE CARD
                    ================================================== --}}
                    <a
                        href="{{ route('data-management.medical-allowance') }}"
                        class="group block min-w-0 flex-1 cursor-pointer rounded-2xl
                            bg-white p-5 shadow-sm ring-1 ring-gray-100
                            transition duration-300
                            hover:-translate-y-1 hover:shadow-lg hover:ring-red-200
                            focus:outline-none focus:ring-2 focus:ring-red-500
                            focus:ring-offset-2"
                        aria-label="View medical allowance records"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <p
                                    class="text-sm font-medium text-gray-500
                                        transition group-hover:text-red-600"
                                >
                                    Medical Allowance Received
                                </p>

                                <p class="mt-2 text-3xl font-bold text-red-600">
                                    {{ number_format($numberOfDisbursement) }}
                                </p>

                                <p class="mt-1 text-xs text-gray-400">
                                    Personnel who have already received their medical allowance
                                </p>
                            </div>

                            {{-- ICON --}}
                            <div
                                class="flex h-12 w-12 shrink-0 items-center justify-center
                                    rounded-xl bg-red-100 text-red-600 transition
                                    group-hover:bg-red-600 group-hover:text-white"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    aria-hidden="true"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M20 8h-3V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2H4a2 2 0 00-2 2v8a2 2 0 002 2h16a2 2 0 002-2v-8a2 2 0 00-2-2zM9 6h6v2H9V6zm3 5v2m-2-1h4"
                                    />
                                </svg>
                            </div>
                        </div>
                    </a>

                </div>


            </div>

            {{-- =====================================================
                TEACHER REQUIREMENT ANALYSIS SCHOOL YEAR 2026-2027 
            ====================================================== --}}

            <div class="mt-10">
                {{-- SECTION TITLE --}}
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-950 via-green-900 to-green-800 px-6 py-4 text-white shadow-lg">

                    <div class="text-center">

                        {{-- LINE 1 --}}
                        <h3 class="text-xl font-bold uppercase tracking-wide text-white">
                            Teacher Requirement Analysis School Year 2026–2027
                        </h3>

                        {{-- LINE 2 --}}
                        <p class="mt-1 text-sm text-green-100">
                            ELEMENTARY LEVEL &nbsp;•&nbsp; Teacher Inventory, Excess, and Shortage
                        </p>

                    </div>

                </div>
                <br>
                {{-- =================================================
                    THREE CARDS - HORIZONTAL
                ================================================== --}}
                <div class="flex w-full flex-nowrap gap-6">


                    {{-- =================================================
                        TEACHER INVENTORY
                    ================================================== --}}
                    <div class="min-w-0 flex-1 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition duration-300 hover:-translate-y-1 hover:shadow-lg">

                        <div class="flex items-start justify-between gap-4">

                            <div class="min-w-0 flex-1">

                                <p class="text-sm font-medium text-gray-500">
                                    Teacher Inventory
                                </p>

                                <p class="mt-2 text-3xl font-bold text-green-700">
                                    ---
                                </p>

                                <p class="mt-1 text-xs text-gray-400">
                                    Total number of teachers in the school
                                </p>

                            </div>


                            {{-- ICON --}}
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-green-100 text-green-700">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >

                                    {{-- Users --}}
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"
                                    />

                                    <circle
                                        cx="9"
                                        cy="7"
                                        r="4"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"
                                    />

                                </svg>

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                        EXCESS TEACHERS
                    ================================================== --}}
                    <div class="min-w-0 flex-1 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition duration-300 hover:-translate-y-1 hover:shadow-lg">

                        <div class="flex items-start justify-between gap-4">

                            <div class="min-w-0 flex-1">

                                <p class="text-sm font-medium text-gray-500">
                                    No. of Excess Teachers
                                </p>

                                <p class="mt-2 text-3xl font-bold text-amber-600">
                                    ---
                                </p>

                                <p class="mt-1 text-xs text-gray-400">
                                    Teachers exceeding the required number
                                </p>

                            </div>


                            {{-- ICON --}}
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >

                                    {{-- Trending Up --}}
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 17l6-6 4 4 8-8"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15 7h6v6"
                                    />

                                </svg>

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                        TEACHER SHORTAGE
                    ================================================== --}}
                    <div class="min-w-0 flex-1 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition duration-300 hover:-translate-y-1 hover:shadow-lg">

                        <div class="flex items-start justify-between gap-4">

                            <div class="min-w-0 flex-1">

                                <p class="text-sm font-medium text-gray-500">
                                    No. of Teacher Shortage
                                </p>

                                <p class="mt-2 text-3xl font-bold text-red-600">
                                    ---
                                </p>

                                <p class="mt-1 text-xs text-gray-400">
                                    Additional teachers needed
                                </p>

                            </div>


                            {{-- ICON --}}
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >

                                    {{-- Warning --}}
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 9v4m0 4h.01"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"
                                    />

                                </svg>

                            </div>

                        </div>

                    </div>


                </div>

            </div>

            {{-- =====================================================
                TEACHER REQUIREMENT ANALYSIS SCHOOL YEAR 2026-2027
            ====================================================== --}}

            <div class="mt-10">


                {{-- THREE LEVEL CARDS --}}
                <div class="flex gap-6">


                    {{-- =================================================
                        ENROLLMENT COUNT
                    ================================================== --}}
                    <div class="flex min-w-0 flex-1 flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">

                        {{-- Card Header --}}
                        <div class="flex items-center justify-between bg-green-700 px-5 py-4 text-white">

                            <div>

                                <h4 class="text-lg font-bold">
                                    ENROLLMENT COUNT S.Y. 2025-2026
                                </h4>

                                <p class="text-xs text-green-100">
                                    ELEMENTARY LEVEL &nbsp;•&nbsp;Kinder to Grade 12
                                </p>

                            </div>


                            {{-- School Icon --}}
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/15">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-5h6v5M8 10h.01M12 10h.01M16 10h.01"
                                    />
                                </svg>

                            </div>

                        </div>


                        {{-- Grade Levels --}}
                        <div class="p-5">

                            <div class="space-y-3">

                                @foreach([
                                    'Kinder',
                                    'Grade 1',
                                    'Grade 2',
                                    'Grade 3',
                                    'Grade 4',
                                    'Grade 5',
                                    'Grade 6',
                                    'Grade 7',
                                    'Grade 8',
                                    'Grade 9',
                                    'Grade 10',
                                    'Grade 11',
                                    'Grade 12'
                                ] as $grade)

                                    <div class="flex items-center justify-between border-b border-gray-100 pb-2">

                                        <span class="text-sm font-medium text-gray-600">
                                            {{ $grade }}
                                        </span>

                                        <span class="font-bold text-green-700">
                                            ---
                                        </span>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                        TEACHER COUNT PER POSITION
                    ================================================== --}}
                    <div class="flex min-w-0 flex-1 flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">

                        {{-- Card Header --}}
                        <div class="flex items-center justify-between bg-green-800 px-5 py-4 text-white">

                            <div>

                                <h4 class="text-lg font-bold">
                                    TEACHER COUNT PER POSITION
                                </h4>

                                <p class="text-xs text-blue-100">
                                    ELEMENTARY LEVEL &nbsp;•&nbsp;Teachers Position
                                </p>

                            </div>


                            {{-- School Icon --}}
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/15">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"
                                    />

                                    <circle
                                        cx="9"
                                        cy="7"
                                        r="4"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"
                                    />

                                </svg>

                            </div>

                        </div>


                        {{-- Grade Levels --}}
                        <div class="p-5">

                            <div class="space-y-3">

                                @foreach([
                                    'Teacher I',
                                    'Teacher II',
                                    'Teacher III',
                                    'Teacher IV',
                                    'Teacher V',
                                    'Teacher VI',
                                    'Master Teacher I',
                                    'Master Teacher II',
                                    'Master Teacher III',
                                    'Master Teacher IV',
                                ] as $position)

                                    <div class="flex items-center justify-between border-b border-gray-100 pb-2">

                                        <span class="text-sm font-medium text-gray-600">
                                            {{ $position }}
                                        </span>

                                        <span class="font-bold text-green-800">
                                            ---
                                        </span>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                        3RD CARD
                        TEACHER COUNT PER SPECIALIZATION
                    ================================================== --}}

                    <div class="min-w-0 flex-1 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">

                        {{-- CARD HEADER --}}
                        <div class="flex items-center justify-between bg-green-700 px-5 py-4 text-white">

                            <div>

                                <h4 class="text-lg font-bold">
                                    TEACHER SPECIALIZATION
                                </h4>

                                <p class="text-xs text-green-100">
                                    ELEMENTARY LEVEL &nbsp;•&nbsp; List of Specialization
                                </p>

                            </div>


                            {{-- ICON --}}
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/15">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"
                                    />

                                    <circle
                                        cx="9"
                                        cy="7"
                                        r="4"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"
                                    />

                                </svg>

                            </div>

                        </div>


                        {{-- CARD CONTENT --}}
                        <div class="p-5">



                            {{-- SPECIALIZATION COUNTS --}}
                            <div class="space-y-2">


                                {{-- EARLY CHILDHOOD --}}
                                <div class="flex items-center justify-between border-b border-gray-100 pb-2">

                                    <span class="text-xs font-medium text-gray-600">
                                        Early Childhood Education
                                    </span>

                                    <span class="text-sm font-bold text-green-700">
                                        ---
                                    </span>

                                </div>


                                {{-- ENGLISH --}}
                                <div class="flex items-center justify-between border-b border-gray-100 pb-2">

                                    <span class="text-xs font-medium text-gray-600">
                                        English
                                    </span>

                                    <span class="text-sm font-bold text-green-700">
                                        ---
                                    </span>

                                </div>


                                {{-- FILIPINO --}}
                                <div class="flex items-center justify-between border-b border-gray-100 pb-2">

                                    <span class="text-xs font-medium text-gray-600">
                                        Filipino
                                    </span>

                                    <span class="text-sm font-bold text-green-700">
                                        ---
                                    </span>

                                </div>


                                {{-- GENERAL EDUCATION --}}
                                <div class="flex items-center justify-between border-b border-gray-100 pb-2">

                                    <span class="text-xs font-medium text-gray-600">
                                        General Education
                                    </span>

                                    <span class="text-sm font-bold text-green-700">
                                        ---
                                    </span>

                                </div>


                                {{-- MATHEMATICS --}}
                                <div class="flex items-center justify-between border-b border-gray-100 pb-2">

                                    <span class="text-xs font-medium text-gray-600">
                                        Mathematics
                                    </span>

                                    <span class="text-sm font-bold text-green-700">
                                        ---
                                    </span>

                                </div>


                                {{-- GENERAL SCIENCE --}}
                                <div class="flex items-center justify-between border-b border-gray-100 pb-2">

                                    <span class="text-xs font-medium text-gray-600">
                                        General Science
                                    </span>

                                    <span class="text-sm font-bold text-green-700">
                                        ---
                                    </span>

                                </div>


                                {{-- PHYSICAL SCIENCE --}}
                                <div class="flex items-center justify-between border-b border-gray-100 pb-2">

                                    <span class="text-xs font-medium text-gray-600">
                                        Physical Science
                                    </span>

                                    <span class="text-sm font-bold text-green-700">
                                        ---
                                    </span>

                                </div>


                                {{-- VALUES EDUCATION --}}
                                <div class="flex items-center justify-between border-b border-gray-100 pb-2">

                                    <span class="text-xs font-medium text-gray-600">
                                        Values Education
                                    </span>

                                    <span class="text-sm font-bold text-green-700">
                                        ---
                                    </span>

                                </div>

                            </div>

                            <br>
                            {{-- =============================================
                                GREEN DIVIDER
                            ============================================== --}}

                            <div class="my-5">

                                {{-- GREEN LINE --}}
                                <div class="h-1 w-full rounded-full bg-green-800"></div>


                                {{-- PRIORITY SPECIALIZATIONS LABEL --}}
                                <div class="mt-4 flex items-center gap-3">

                                    <div class="h-px flex-1 bg-gray-300"></div>

                                    <span class="rounded-full px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-green-800">
                                        Priority Specializations
                                    </span>

                                    <div class="h-px flex-1 bg-gray-300"></div>

                                </div>

                            </div>


                            {{-- =============================================
                                PRIORITY SPECIALIZATIONS
                                NO COUNTS
                            ============================================== --}}

                            <div class="space-y-2">


                                {{-- 1 --}}
                                <div class="flex items-center gap-3 rounded-lg bg-green-50 px-3 py-2">

                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-green-700 text-xs font-bold text-white">
                                        1
                                    </span>

                                    <span class="text-xs font-semibold text-gray-700">
                                        Specialization #1
                                    </span>

                                </div>


                                {{-- 2 --}}
                                <div class="flex items-center gap-3 rounded-lg bg-green-50 px-3 py-2">

                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-green-700 text-xs font-bold text-white">
                                        2
                                    </span>

                                    <span class="text-xs font-semibold text-gray-700">
                                        Specialization #2
                                    </span>

                                </div>


                                {{-- 3 --}}
                                <div class="flex items-center gap-3 rounded-lg bg-green-50 px-3 py-2">

                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-green-700 text-xs font-bold text-white">
                                        3
                                    </span>

                                    <span class="text-xs font-semibold text-gray-700">
                                        Specialization #3
                                    </span>

                                </div>


                                {{-- 4 --}}
                                <div class="flex items-center gap-3 rounded-lg bg-green-50 px-3 py-2">

                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-green-700 text-xs font-bold text-white">
                                        4
                                    </span>

                                    <span class="text-xs font-semibold text-gray-700">
                                        Specialization #4
                                    </span>

                                </div>


                                {{-- 5 --}}
                                <div class="flex items-center gap-3 rounded-lg bg-green-50 px-3 py-2">

                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-green-700 text-xs font-bold text-white">
                                        5
                                    </span>

                                    <span class="text-xs font-semibold text-gray-700">
                                        Specialization #5
                                    </span>

                                </div>


                            </div>

                        </div>

                    </div>

                </div>

            </div>

        @endif

        @if(auth()->user()->role === 'super_admin')
            {{-- =====================================================
                REQUEST STATUS SUMMARY
            ====================================================== --}}
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-950 via-green-900 to-green-800 px-6 py-4 text-white shadow-lg">
                <h3 class="mb-4 text-lg font-bold text-gray-800 text-center text-white">
                    PERSONNEL TRANSACTION REQUEST
                </h3>
            </div>
            <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                {{-- PENDING --}}
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Pending
                            </p>

                            <p class="mt-2 text-3xl font-bold text-amber-600">
                                ---
                            </p>

                            <p class="mt-1 text-xs text-gray-400">
                                Awaiting approval
                            </p>

                        </div>


                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-amber-600">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"
                                />
                            </svg>

                        </div>

                    </div>

                </div>


                {{-- APPROVED --}}
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Approved
                            </p>

                            <p class="mt-2 text-3xl font-bold text-green-700">
                                ---
                            </p>

                            <p class="mt-1 text-xs text-gray-400">
                                Successfully approved
                            </p>

                        </div>


                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-green-700">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>

                        </div>

                    </div>

                </div>


                {{-- SUBMITTED --}}
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Submitted
                            </p>

                            <p class="mt-2 text-3xl font-bold text-blue-600">
                                ---
                            </p>

                            <p class="mt-1 text-xs text-gray-400">
                                Recently submitted
                            </p>

                        </div>


                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-600">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M12 19V5m0 0l-6 6m6-6l6 6"
                                />
                            </svg>

                        </div>

                    </div>

                </div>


                {{-- DISAPPROVED --}}
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Disapproved
                            </p>

                            <p class="mt-2 text-3xl font-bold text-red-600">
                                ---
                            </p>

                            <p class="mt-1 text-xs text-gray-400">
                                Not approved
                            </p>

                        </div>


                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-100 text-red-600">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>

                        </div>

                    </div>

                </div>

            </div>
            <br>
    
            {{-- =====================================================
                QUICK ACTIONS
            ====================================================== --}}
            <div>
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-950 via-green-900 to-green-800 px-6 py-4 text-white shadow-lg">
                    <h3 class="mb-4 text-lg font-bold text-gray-800 text-center text-white">
                        QUICK ACTIONS
                    </h3>
                </div>
                <br>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

                    <a
                        href="#"
                        class="rounded-xl border border-gray-200 bg-white p-5 transition hover:border-green-500 hover:shadow-md"
                    >

                        <p class="font-semibold text-gray-800">
                            Add Employee
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Create a new personnel profile
                        </p>

                    </a>


                    <a
                        href="#"
                        class="rounded-xl border border-gray-200 bg-white p-5 transition hover:border-green-500 hover:shadow-md"
                    >

                        <p class="font-semibold text-gray-800">
                            View Employees
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Browse personnel records
                        </p>

                    </a>


                    <a
                        href="#"
                        class="rounded-xl border border-gray-200 bg-white p-5 transition hover:border-green-500 hover:shadow-md"
                    >

                        <p class="font-semibold text-gray-800">
                            HR Transactions
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Manage personnel requests
                        </p>

                    </a>


                    <a
                        href="#"
                        class="rounded-xl border border-gray-200 bg-white p-5 transition hover:border-green-500 hover:shadow-md"
                    >

                        <p class="font-semibold text-gray-800">
                            Generate Reports
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            View and generate reports
                        </p>

                    </a>

                </div>

            </div>
        @endif

        @if(auth()->user()->role === 'user')
            {{-- =====================================================
                403 CONTENT
            ====================================================== --}}
            <div
                class="overflow-hidden rounded-2xl bg-white
                        shadow-sm ring-1 ring-gray-100"
                >

                {{-- Section Header --}}
                <div
                    class="border-b border-green-100
                            bg-green-50 px-6 py-4"
                >

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 items-center justify-center
                                    rounded-lg bg-green-700 text-white"
                        >

                            {{-- Lock Icon --}}
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M16.5 10.5V6.75a4.5 4.5 0 00-9 0v3.75"
                                />

                                <rect
                                    width="15"
                                    height="10"
                                    x="4.5"
                                    y="10.5"
                                    rx="2.25"
                                />
                            </svg>

                        </div>

                        <div>

                            <h2 class="font-bold text-gray-800">
                                Permission Required
                            </h2>

                            <p class="text-sm text-gray-500">
                                This resource is restricted based on your account role.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Main Content --}}
                <div class="px-6 py-10 sm:px-10 sm:py-14">

                    <div class="mx-auto max-w-3xl">

                        <div class="grid gap-8 md:grid-cols-2 md:items-center">

                            {{-- =================================================
                                LEFT - 403 DISPLAY
                            ================================================== --}}
                            <div class="text-center md:text-left">

                                <div
                                    class="text-7xl font-black tracking-tight
                                            text-green-900 sm:text-8xl"
                                >
                                    403
                                </div>

                                <div
                                    class="mt-3 h-1 w-20 rounded-full
                                            bg-green-600
                                            mx-auto md:mx-0"
                                ></div>

                                <h3
                                    class="mt-5 text-xl font-bold text-gray-800"
                                >
                                    Unauthorized Action
                                </h3>

                                <p
                                    class="mt-3 text-sm leading-6 text-gray-500"
                                >
                                    You are currently signed in, but your account
                                    does not have permission to perform this action
                                    or access this section of the Personnel Database
                                    Management System.
                                </p>

                            </div>


                            {{-- =================================================
                                RIGHT - ACCESS INFORMATION
                            ================================================== --}}
                            <div>

                                {{-- Current Account --}}
                                <div
                                    class="rounded-xl border border-green-100
                                            bg-green-50 p-5"
                                >

                                    <div class="flex items-start gap-4">

                                        <div
                                            class="flex h-11 w-11 shrink-0
                                                    items-center justify-center
                                                    rounded-lg bg-green-700
                                                    text-white"
                                        >

                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-6 w-6"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"
                                                />

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M4.5 20.25a8.25 8.25 0 0115 0"
                                                />
                                            </svg>

                                        </div>


                                        <div class="min-w-0">

                                            <p
                                                class="text-xs font-semibold
                                                        uppercase tracking-wider
                                                        text-green-700"
                                            >
                                                Current Account
                                            </p>

                                            <p
                                                class="mt-1 truncate
                                                        font-bold text-gray-800"
                                            >
                                                {{ Auth::user()->name }}
                                            </p>

                                            <p class="text-sm text-gray-500">
                                                {{ Auth::user()->email }}
                                            </p>

                                        </div>

                                    </div>

                                </div>


                                {{-- Role --}}
                                <div
                                    class="mt-4 flex items-center justify-between
                                            rounded-xl border border-gray-100
                                            bg-white px-5 py-4
                                            shadow-sm"
                                >

                                    <div>

                                        <p
                                            class="text-xs font-semibold
                                                    uppercase tracking-wider
                                                    text-gray-400"
                                        >
                                            Your Role
                                        </p>

                                        <p class="mt-1 font-bold text-gray-800">

                                            {{ ucwords(str_replace('_', ' ', Auth::user()->role)) }}

                                        </p>

                                    </div>


                                    <div
                                        class="flex h-10 w-10 items-center
                                                justify-center rounded-lg
                                                bg-green-100 text-green-700"
                                    >

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M9 12.75L11.25 15 15 9.75"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M12 3l7.5 3v5.25c0 4.75-3.2 8.65-7.5 9.75-4.3-1.1-7.5-5-7.5-9.75V6L12 3z"
                                            />
                                        </svg>

                                    </div>

                                </div>


                                {{-- Information --}}
                                <div
                                    class="mt-4 flex gap-3 rounded-xl
                                            border border-green-100
                                            bg-green-50/70 p-4"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="mt-0.5 h-5 w-5 shrink-0 text-green-700"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="9"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            d="M12 11v5"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            d="M12 8.25h.01"
                                        />
                                    </svg>


                                    <p
                                        class="text-sm leading-5 text-gray-600"
                                    >
                                        If you believe you should have access to
                                        this resource, please contact the system
                                        administrator or the Personnel Unit.
                                    </p>

                                </div>


                                {{-- Back Button --}}
                                <div class="mt-6">

                                    <a
                                        href="{{ route('profile.edit') }}"
                                        class="inline-flex w-full items-center
                                                justify-center gap-2 rounded-lg
                                                bg-green-700 px-5 py-3
                                                text-sm font-semibold text-white
                                                shadow-sm transition
                                                hover:bg-green-800
                                                focus:outline-none
                                                focus:ring-2
                                                focus:ring-green-500
                                                focus:ring-offset-2"
                                    >

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"
                                            />
                                        </svg>

                                        Go to Profile

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        @endif

    </div>

</div>

</x-app-layout>
