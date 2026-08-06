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
        <br>

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
                                {{ $plantillaEmployees ?? '21,183' }}
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
                            href="#"
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
                                {{ $otherFundsEmployees ?? 430 }}
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
                            href="#"
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
                                {{ $numberOfSchools ?? '1,378' }}
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
                            href="#"
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
                                {{ $hrTransactions ?? '4,582' }}
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

        <br>
        {{-- =====================================================
            REQUEST STATUS SUMMARY
        ====================================================== --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-950 via-green-900 to-green-800 px-6 py-4 text-white shadow-lg">
            <h3 class="mb-4 text-lg font-bold text-gray-800 text-center text-white">
                PERSONNEL TRANSACTION REQUEST
            </h3>
        </div>
        <br>
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            {{-- PENDING --}}
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Pending
                        </p>

                        <p class="mt-2 text-3xl font-bold text-amber-600">
                            12
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
                            48
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
                            27
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
                            8
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

    </div>

</div>
```

</x-app-layout>
