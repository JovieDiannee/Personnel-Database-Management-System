<x-app-layout>

<div class="min-h-screen bg-gray-50 py-6">

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6">


        {{-- =========================================================
            BREADCRUMB
        ========================================================== --}}

        <div class="mb-5">

            <nav
                class="flex flex-wrap items-center gap-2 text-sm"
                aria-label="Breadcrumb"
            >

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


                <svg
                    class="h-4 w-4 text-gray-400"
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


                <a
                    href="{{ route('data-management') }}"
                    class="font-medium text-gray-500 transition hover:text-green-700"
                >
                    Data Management
                </a>


                <svg
                    class="h-4 w-4 text-gray-400"
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


                <span class="font-semibold text-green-700">
                    Medical Allowance
                </span>

            </nav>

        </div>


        {{-- =========================================================
            TAB NAVIGATION
        ========================================================== --}}

        <div
            class="mb-5 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm"
        >

            <div class="grid grid-cols-2">

                <a
                    href="{{ route('data-management.medical-allowance') }}"
                    class="flex items-center justify-center gap-3
                           border-b-2 border-green-700 bg-green-50
                           px-4 py-3.5 text-green-800"
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
                            d="M17 20h5v-2a4 4 0 00-4-4h-1
                               M9 20H4v-2a4 4 0 014-4h1
                               M12 12a4 4 0 100-8 4 4 0 000 8z"
                        />
                    </svg>

                    <div>
                        <p class="text-sm font-semibold">
                            Personnel Records
                        </p>

                        <p class="text-xs font-normal text-gray-500">
                            Review and validate individual records
                        </p>
                    </div>

                </a>


                <a
                    href="{{ route('data-management.medical-allowance.report') }}"
                    class="flex items-center justify-center gap-3
                           border-b-2 border-transparent px-4 py-3.5
                           text-gray-700 transition
                           hover:bg-gray-50 hover:text-green-700"
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
                            d="M9 17v-2a4 4 0 014-4h4a4 4 0 014 4v2
                               M9 17H5a2 2 0 01-2-2V7a2 2 0 012-2h10
                               a2 2 0 012 2v2 M7 9h6 M7 13h2"
                        />
                    </svg>

                    <div>
                        <p class="text-sm font-semibold">
                            Medical Allowance Report
                        </p>

                        <p class="text-xs font-normal text-gray-500">
                            School-level summary
                        </p>
                    </div>

                </a>

            </div>

        </div>


        {{-- =========================================================
            SUCCESS / ERROR
        ========================================================== --}}

        @if(session('success'))

            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 5000)"
                x-transition
                class="mb-5 flex items-center justify-between
                       rounded-lg border border-green-200
                       bg-green-50 px-4 py-3"
            >

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-8 w-8 items-center justify-center
                               rounded-full bg-green-100 text-green-700"
                    >
                        ✓
                    </div>

                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>

                </div>


                <button
                    type="button"
                    @click="show = false"
                    class="text-green-700"
                >
                    ✕
                </button>

            </div>

        @endif


        @if(session('error'))

            <div
                class="mb-5 rounded-lg border border-red-200
                       bg-red-50 px-4 py-3 text-sm
                       font-medium text-red-800"
            >
                {{ session('error') }}
            </div>

        @endif

        {{-- =========================================================
            VALIDATION / DEADLINE BAR
        ========================================================== --}}

        @if(
            auth()->user()?->role === 'admin'
            && $medicalSubmission?->status === 'Verified'
        )

            {{-- =====================================================
                VERIFIED / LOCKED
            ====================================================== --}}

            <div
                class="mb-5 flex w-full items-center justify-between
                    gap-4 rounded-xl border border-green-200
                    bg-green-50 px-5 py-4"
            >

                {{-- LEFT SIDE --}}
                <div class="flex min-w-0 flex-1 items-center gap-3">

                    {{-- ICON --}}
                    <div
                        class="flex h-10 w-10 shrink-0 items-center
                            justify-center rounded-full
                            bg-green-100 text-green-700"
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
                                d="M5 12l4 4L19 6"
                            />
                        </svg>
                    </div>


                    {{-- INFORMATION --}}
                    <div class="min-w-0">

                        <p class="text-sm font-semibold text-green-900">
                            Medical Allowance Report Validated
                        </p>

                        <p class="mt-0.5 text-xs text-green-700">

                            Validated by

                            <span class="font-semibold">
                                {{ $medicalSubmission->validatedBy?->name ?? 'Unavailable' }}
                            </span>

                            @if($medicalSubmission->validated_at)

                                on

                                {{ $medicalSubmission->validated_at
                                    ->copy()
                                    ->timezone('Asia/Manila')
                                    ->format('F j, Y • g:i A') }}

                            @endif

                        </p>

                    </div>

                </div>


                {{-- RIGHT SIDE --}}
                <div class="ml-auto shrink-0">

                    <span
                        class="inline-flex items-center gap-1.5
                            whitespace-nowrap rounded-full
                            bg-green-100 px-3 py-1.5
                            text-xs font-semibold text-green-700"
                    >

                        <svg
                            class="h-3.5 w-3.5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6
                                a2 2 0 00-2-2h-1V7a5 5 0 00-10 0v4H6
                                a2 2 0 00-2 2v6a2 2 0 002 2zm3-10V7
                                a3 3 0 016 0v4H9z"
                            />
                        </svg>

                        Editing Locked

                    </span>

                </div>

            </div>


        @elseif($medicalReport)

            {{-- =====================================================
                ONGOING VALIDATION
            ====================================================== --}}

            <div
                class="mb-5 w-full rounded-xl border
                    border-orange-200 bg-orange-50
                    px-5 py-4"
            >

                {{-- =================================================
                    SINGLE ROW
                ================================================== --}}

                <div class="flex w-full items-center gap-5">

                    {{-- =================================================
                        LEFT SIDE
                    ================================================== --}}

                    <div class="flex min-w-0 flex-1 items-start gap-3">

                        {{-- CLOCK ICON --}}
                        <div
                            class="flex h-10 w-10 shrink-0 items-center
                                justify-center rounded-full
                                bg-orange-100 text-orange-700"
                        >
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    cx="12"
                                    cy="12"
                                    r="9"
                                    stroke-width="2"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 7v5l3 2"
                                />
                            </svg>
                        </div>


                        {{-- DEADLINE INFORMATION --}}
                        <div class="min-w-0 flex-1">

                            <p class="text-sm font-semibold text-red-700">
                                Medical Allowance Validation Deadline:

                                <strong>
                                    {{ $medicalReport->deadline
                                        ?->format('F j, Y • g:i A')
                                        ?? 'Not configured' }}
                                </strong>
                            </p>



                            <p class="mt-1 text-sm text-gray-600">

                                Review and validate both

                                <strong class="font-semibold text-gray-700">
                                    {{ $previousYear }}
                                </strong>

                                and

                                <strong class="font-semibold text-gray-700">
                                    {{ $currentYear }}
                                </strong>

                                medical allowance records before submission.

                            </p>

                        </div>

                    </div>


                    {{-- =================================================
                        RIGHT SIDE
                    ================================================== --}}

                    @if(auth()->user()?->role === 'admin')

                        <div class="ml-auto shrink-0">

                            {{-- REPORT CLOSED --}}
                            @if($medicalReport->status !== 'Ongoing')

                                <span
                                    class="inline-flex min-h-11 items-center
                                        justify-center whitespace-nowrap
                                        rounded-lg bg-gray-200
                                        px-5 text-sm font-semibold
                                        text-gray-600"
                                >
                                    Report Closed
                                </span>


                            {{-- NO SCHOOL --}}
                            @elseif(!$schoolCode)

                                <span
                                    class="inline-flex min-h-11 items-center
                                        whitespace-nowrap rounded-lg
                                        bg-red-50 px-4 text-sm
                                        font-semibold text-red-700"
                                >
                                    No assigned school.
                                </span>


                            {{-- VALIDATE BUTTON --}}
                            @else

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'data-management.medical-allowance.validate',
                                        ['report' => $medicalReport->id]
                                    ) }}"
                                    onsubmit="return confirm(
                                        'Confirm that the {{ $previousYear }} and {{ $currentYear }} medical allowance records have been reviewed and are correct. Submit this report?'
                                    );"
                                    class="m-0"
                                >

                                    @csrf
                                    @method('PATCH')


                                    <button
                                        type="submit"
                                        class="inline-flex min-h-11 items-center
                                            justify-center gap-2 whitespace-nowrap
                                            rounded-lg bg-green-700 px-5
                                            text-sm font-semibold text-white
                                            shadow-sm transition
                                            hover:bg-green-800
                                            focus:outline-none
                                            focus:ring-2
                                            focus:ring-green-600
                                            focus:ring-offset-2"
                                    >

                                        <svg
                                            class="h-4 w-4 shrink-0"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M5 13l4 4L19 7"
                                            />
                                        </svg>

                                        Validate & Submit

                                    </button>

                                </form>

                            @endif

                        </div>

                    @endif

                </div>

            </div>

        @endif


        {{-- =====================================================
            SUPER ADMIN - IMPORT PERSONNEL
        ====================================================== --}}

        @if(auth()->user()->role === 'super_admin')

            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">

                <h2 class="text-lg font-bold text-gray-800">
                    Import Medical Allowance Status
                </h2>


                <p class="mt-1 text-sm text-gray-500">
                    Upload the official Medical Allowance Status Excel file.
                </p>


                <form
                    action="{{ route('data-management.medical-allowance.import') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="mt-6"
                >

                    @csrf


                    <div class="flex flex-col gap-3 xl:flex-row xl:items-center">


                        {{-- EXCEL FILE LABEL --}}

                        <label
                            for="file"
                            class="shrink-0 text-sm font-semibold text-gray-700"
                        >
                            EXCEL FILE
                        </label>


                        {{-- CUSTOM FILE INPUT --}}

                        <div class="relative flex h-11 min-w-0 w-full xl:flex-1">

                            <input
                                type="file"
                                id="file"
                                name="file"
                                accept=".xlsx,.xls"
                                required
                                class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0"
                                onchange="
                                    document.getElementById('file-name').textContent =
                                    this.files.length
                                        ? this.files[0].name
                                        : 'No file selected'
                                "
                            >


                            <div
                                class="flex h-full w-full items-center overflow-hidden
                                        rounded-lg border border-gray-300 bg-white shadow-sm"
                            >

                                <span
                                    class="flex h-full shrink-0 items-center
                                            border-r border-green-200
                                            bg-green-50 px-4
                                            text-sm font-semibold text-green-700"
                                >
                                    Browse...
                                </span>


                                <span
                                    id="file-name"
                                    class="truncate px-4 text-sm text-gray-500"
                                >
                                    No file selected
                                </span>

                            </div>

                        </div>


                        {{-- ACTION BUTTONS --}}

                        <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row">

                            <button
                                type="submit"
                                class="inline-flex min-h-11 items-center justify-center gap-2
                                        rounded-lg bg-green-700 px-5
                                        text-sm font-semibold text-white
                                        shadow-sm transition hover:bg-green-800"
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 3v10m0-10L8 7m4-4l4 4"
                                    />
                                </svg>

                                Upload & Preview

                            </button>


                            <a
                                href="{{ route('data-management.medical-allowance.template') }}"
                                class="inline-flex min-h-11 items-center justify-center gap-2
                                        rounded-lg border border-green-700 bg-white px-4
                                        text-sm font-semibold text-green-700
                                        shadow-sm transition hover:bg-green-50"
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 3v12m0 0l-4-4m4 4l4-4"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M5 21h14"
                                    />
                                </svg>

                                Download Template

                            </a>

                        </div>

                    </div>


                    <p class="mt-1.5 text-xs text-gray-500">
                        Accepted formats:
                        <span class="font-medium">.xlsx</span>
                        and
                        <span class="font-medium">.xls</span>.
                        Maximum file size:
                        <span class="font-medium">10 MB</span>.
                    </p>

                </form>

            </div>
            <br>

        @endif


        {{-- =========================================================
            VALIDATION SUMMARY
        ========================================================== --}}

        <div class="mb-4">

            <div class="mb-2 flex items-center gap-2">

                <h2 class="text-base font-bold text-gray-900">
                    Validation Summary
                </h2>

                <span class="text-gray-300">|</span>

                <p class="text-xs text-gray-500">
                    {{ $previousYear }} vs. {{ $currentYear }} Medical Allowance
                </p>

            </div>


            {{-- =====================================================
                9 CARDS IN ONE ROW
            ====================================================== --}}

            <div class="w-full overflow-x-auto">

                <div
                    class="grid gap-2"
                    style="
                        grid-template-columns: repeat(9, minmax(125px, 1fr));
                        min-width: 1150px;
                    "
                >

                    {{-- ================================================= --}}
                    {{-- TOTAL --}}
                    {{-- ================================================= --}}

                    <a
                        href="{{ request()->fullUrlWithQuery([
                            'filter' => 'all',
                            'page' => 1
                        ]) }}#medical-allowance-table"
                        class="min-w-0 rounded-lg border px-3 py-2.5 transition
                            {{ $filter === 'all'
                                ? 'border-slate-400 bg-slate-50 shadow-sm'
                                : 'border-gray-200 bg-white hover:border-slate-300' }}"
                    >
                        <p class="truncate text-[10px] font-semibold uppercase text-gray-500">
                            Total
                        </p>

                        <p class="mt-1 text-lg font-bold text-gray-900">
                            {{ number_format($summary['total'] ?? 0) }}
                        </p>
                    </a>


                    {{-- ================================================= --}}
                    {{-- ACTIVE --}}
                    {{-- ================================================= --}}

                    <a
                        href="{{ request()->fullUrlWithQuery([
                            'filter' => 'active',
                            'page' => 1
                        ]) }}#medical-allowance-table"
                        class="min-w-0 rounded-lg border px-3 py-2.5 transition
                            {{ $filter === 'active'
                                ? 'border-green-300 bg-green-50 shadow-sm'
                                : 'border-gray-200 bg-white hover:border-green-300' }}"
                    >
                        <p class="truncate text-[10px] font-semibold uppercase text-green-600">
                            Active
                        </p>

                        <p class="mt-1 text-lg font-bold text-green-700">
                            {{ number_format($summary['active'] ?? 0) }}
                        </p>
                    </a>


                    {{-- ================================================= --}}
                    {{-- INACTIVE --}}
                    {{-- ================================================= --}}

                    <a
                        href="{{ request()->fullUrlWithQuery([
                            'filter' => 'inactive',
                            'page' => 1
                        ]) }}#medical-allowance-table"
                        class="min-w-0 rounded-lg border px-3 py-2.5 transition
                            {{ $filter === 'inactive'
                                ? 'border-red-300 bg-red-50 shadow-sm'
                                : 'border-gray-200 bg-white hover:border-red-300' }}"
                    >
                        <p class="truncate text-[10px] font-semibold uppercase text-red-600">
                            Inactive
                        </p>

                        <p class="mt-1 text-lg font-bold text-red-700">
                            {{ number_format($summary['inactive'] ?? 0) }}
                        </p>
                    </a>


                    {{-- ================================================= --}}
                    {{-- NO SCHOOL ASSIGNMENT --}}
                    {{-- ================================================= --}}

                    <a
                        href="{{ request()->fullUrlWithQuery([
                            'filter' => 'no_school',
                            'page' => 1
                        ]) }}#medical-allowance-table"
                        class="min-w-0 rounded-lg border px-3 py-2.5 transition
                            {{ $filter === 'no_school'
                                ? 'border-orange-300 bg-orange-50 shadow-sm'
                                : 'border-gray-200 bg-white hover:border-orange-300' }}"
                    >
                        <p
                            class="truncate text-[10px] font-semibold uppercase text-orange-600"
                            title="No School Assignment"
                        >
                            No School
                        </p>

                        <p class="mt-1 text-lg font-bold text-orange-700">
                            {{ number_format($summary['no_school'] ?? 0) }}
                        </p>
                    </a>


                    {{-- ================================================= --}}
                    {{-- CHANGED --}}
                    {{-- ================================================= --}}

                    <a
                        href="{{ request()->fullUrlWithQuery([
                            'filter' => 'changed',
                            'page' => 1
                        ]) }}#medical-allowance-table"
                        class="min-w-0 rounded-lg border px-3 py-2.5 transition
                            {{ $filter === 'changed'
                                ? 'border-blue-300 bg-blue-50 shadow-sm'
                                : 'border-gray-200 bg-white hover:border-blue-300' }}"
                    >
                        <p class="truncate text-[10px] font-semibold uppercase text-blue-600">
                            Changed
                        </p>

                        <p class="mt-1 text-lg font-bold text-blue-700">
                            {{ number_format($summary['changed'] ?? 0) }}
                        </p>
                    </a>


                    {{-- ================================================= --}}
                    {{-- NO CHANGES --}}
                    {{-- ================================================= --}}

                    <a
                        href="{{ request()->fullUrlWithQuery([
                            'filter' => 'no_change',
                            'page' => 1
                        ]) }}#medical-allowance-table"
                        class="min-w-0 rounded-lg border px-3 py-2.5 transition
                            {{ $filter === 'no_change'
                                ? 'border-gray-400 bg-gray-100 shadow-sm'
                                : 'border-gray-200 bg-white hover:border-gray-300' }}"
                    >
                        <p class="truncate text-[10px] font-semibold uppercase text-gray-500">
                            No Changes
                        </p>

                        <p class="mt-1 text-lg font-bold text-gray-700">
                            {{ number_format($summary['no_change'] ?? 0) }}
                        </p>
                    </a>


                    {{-- ================================================= --}}
                    {{-- NO PREVIOUS RECORD --}}
                    {{-- ================================================= --}}

                    <a
                        href="{{ request()->fullUrlWithQuery([
                            'filter' => 'new',
                            'page' => 1
                        ]) }}#medical-allowance-table"
                        class="min-w-0 rounded-lg border px-3 py-2.5 transition
                            {{ $filter === 'new'
                                ? 'border-purple-300 bg-purple-50 shadow-sm'
                                : 'border-gray-200 bg-white hover:border-purple-300' }}"
                    >
                        <p
                            class="truncate text-[10px] font-semibold uppercase text-purple-600"
                            title="No {{ $previousYear }} Record"
                        >
                            No {{ $previousYear }} Record
                        </p>

                        <p class="mt-1 text-lg font-bold text-purple-700">
                            {{ number_format($summary['new'] ?? 0) }}
                        </p>
                    </a>


                    {{-- ================================================= --}}
                    {{-- PENDING --}}
                    {{-- ================================================= --}}

                    <a
                        href="{{ request()->fullUrlWithQuery([
                            'filter' => 'pending',
                            'page' => 1
                        ]) }}#medical-allowance-table"
                        class="min-w-0 rounded-lg border px-3 py-2.5 transition
                            {{ $filter === 'pending'
                                ? 'border-amber-300 bg-amber-50 shadow-sm'
                                : 'border-gray-200 bg-white hover:border-amber-300' }}"
                    >
                        <p class="truncate text-[10px] font-semibold uppercase text-amber-600">
                            Pending
                        </p>

                        <p class="mt-1 text-lg font-bold text-amber-700">
                            {{ number_format($summary['pending'] ?? 0) }}
                        </p>
                    </a>


                    {{-- ================================================= --}}
                    {{-- VALIDATED --}}
                    {{-- ================================================= --}}

                    <a
                        href="{{ request()->fullUrlWithQuery([
                            'filter' => 'validated',
                            'page' => 1
                        ]) }}#medical-allowance-table"
                        class="min-w-0 rounded-lg border px-3 py-2.5 transition
                            {{ $filter === 'validated'
                                ? 'border-green-300 bg-green-50 shadow-sm'
                                : 'border-gray-200 bg-white hover:border-green-300' }}"
                    >
                        <p class="truncate text-[10px] font-semibold uppercase text-green-600">
                            Validated
                        </p>

                        <p class="mt-1 text-lg font-bold text-green-700">
                            {{ number_format($summary['validated'] ?? 0) }}
                        </p>
                    </a>

                </div>

            </div>

        </div>


        {{-- =========================================================
            RECORDS CARD
        ========================================================== --}}

        <div
            id="medical-allowance-table"
            class="overflow-hidden rounded-xl
                   border border-gray-200 bg-white shadow-sm"
            >


            {{-- HEADER --}}

            <div
                class="flex flex-col gap-3 bg-green-800
                       px-5 py-4 sm:flex-row
                       sm:items-center sm:justify-between"
            >

                <div>

                    <h2 class="text-base font-semibold text-white">

                        Medical Allowance Records

                        @if(auth()->user()->role === 'admin')

                            <span class="font-normal text-green-100">
                                —
                                @php
                                    $school = auth()->user()->employmentStatus?->school;
                                @endphp

                                {{ $school
                                    ? $school->school_name . ' - ' . $school->school_district
                                    : 'No assigned school'
                                }}
                            </span>

                        @else

                            <span class="font-normal text-green-100">
                                — All Schools
                            </span>

                        @endif

                    </h2>


                    <p class="mt-1 text-xs text-green-100">
                        Review and correct both year records before validation.
                    </p>

                </div>


                <div
                    class="inline-flex w-fit rounded-lg
                           bg-green-900/40 px-3 py-2
                           text-xs font-medium text-green-100"
                >
                    {{ $previousYear }} → {{ $currentYear }}
                </div>

            </div>


            {{-- =====================================================
                SEARCH
            ====================================================== --}}

            <div class="border-b border-gray-200 p-4">

                <form
                    action="{{ route(
                        'data-management.medical-allowance'
                    ) }}"
                    method="GET"
                >

                    <input
                        type="hidden"
                        name="filter"
                        value="{{ $filter }}"
                    >


                    <div class="flex flex-col gap-2 sm:flex-row">

                        <div class="relative flex-1">

                            <div
                                class="pointer-events-none absolute
                                       inset-y-0 left-0 flex items-center pl-3"
                            >
                                <svg
                                    class="h-4 w-4 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <circle cx="11" cy="11" r="7"/>

                                    <path
                                        stroke-linecap="round"
                                        stroke-width="2"
                                        d="m20 20-3.5-3.5"
                                    />
                                </svg>
                            </div>


                            <input
                                type="text"
                                name="search"
                                value="{{ $search }}"
                                placeholder="  Search employee name, email, school, position or status..."
                                class="w-full rounded-lg border-gray-300
                                       py-2.5 pl-10 pr-4 text-sm
                                       shadow-sm focus:border-green-600
                                       focus:ring-green-600"
                            >

                        </div>


                        <button
                            type="submit"
                            class="min-h-10 rounded-lg bg-green-700
                                   px-5 text-sm font-semibold text-white
                                   transition hover:bg-green-800"
                        >
                            Search
                        </button>


                        @if($search !== '' || $filter !== 'all')

                            <a
                                href="{{ route(
                                    'data-management.medical-allowance'
                                ) }}#medical-allowance-table"
                                class="flex min-h-10 items-center
                                       justify-center rounded-lg border
                                       border-gray-300 bg-white px-4
                                       text-sm font-semibold text-gray-600
                                       hover:bg-gray-50"
                            >
                                Clear
                            </a>

                        @endif

                    </div>

                </form>

            </div>


            {{-- =====================================================
                SORT URL
            ====================================================== --}}

            @php

                $sortUrl = function ($column) use (
                    $sort,
                    $direction
                ) {

                    $newDirection =
                        ($sort === $column && $direction === 'asc')
                            ? 'desc'
                            : 'asc';

                    return request()->fullUrlWithQuery([
                        'sort' => $column,
                        'direction' => $newDirection,
                        'page' => 1,
                    ]) . '#medical-allowance-table';

                };

            @endphp


            {{-- =====================================================
                TABLE
            ====================================================== --}}

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead>

                        {{-- YEAR GROUP --}}

                        <tr class="border-b border-gray-200 bg-gray-50">

                        {{-- Employee + School + Position + Warm Body --}}
                        <th
                            colspan="{{ auth()->user()->role === 'super_admin' ? 4 : 3 }}"
                            class="px-4 py-2"
                        ></th>


                        {{-- PREVIOUS YEAR --}}
                        <th
                            class="border-l border-gray-200
                                bg-slate-100 px-4 py-2 text-center"
                        >
                            <span
                                class="text-xs font-bold uppercase
                                    tracking-wide text-slate-600"
                            >
                                {{ $previousYear }}
                            </span>
                        </th>


                        {{-- CURRENT YEAR --}}
                        <th
                            class="border-l border-gray-200
                                bg-green-100 px-4 py-2 text-center"
                        >
                            <span
                                class="text-xs font-bold uppercase
                                    tracking-wide text-green-700"
                            >
                                {{ $currentYear }}
                            </span>
                        </th>


                        {{-- Comparison + Validation + Action --}}
                        <th colspan="3"></th>

                    </tr>


                        <tr class="bg-white">

                        {{-- ================================================= --}}
                        {{-- EMPLOYEE --}}
                        {{-- ================================================= --}}

                        <th
                            class="min-w-[240px] px-4 py-3
                                text-left text-xs font-semibold
                                uppercase tracking-wide text-gray-500"
                        >
                            <a
                                href="{{ $sortUrl('name') }}"
                                class="hover:text-green-700"
                            >
                                Employee
                            </a>
                        </th>


                        {{-- ================================================= --}}
                        {{-- SCHOOL ASSIGNMENT --}}
                        {{-- SUPER ADMIN ONLY --}}
                        {{-- ================================================= --}}

                        @if(auth()->user()->role === 'super_admin')

                            <th
                                class="min-w-[210px] px-4 py-3
                                    text-left text-xs font-semibold
                                    uppercase text-gray-500"
                            >
                                <a
                                    href="{{ $sortUrl('school') }}"
                                    class="hover:text-green-700"
                                >
                                    School Assignment
                                </a>
                            </th>

                        @endif


                        {{-- ================================================= --}}
                        {{-- POSITION --}}
                        {{-- ================================================= --}}

                        <th
                            class="min-w-[190px] px-4 py-3
                                text-center text-xs font-semibold
                                uppercase text-gray-500"
                        >
                            <a
                                href="{{ $sortUrl('position') }}"
                                class="hover:text-green-700"
                            >
                                Position Status
                            </a>
                        </th>


                        {{-- ================================================= --}}
                        {{-- WARM BODY STATUS --}}
                        {{-- ================================================= --}}

                        <th
                            class="min-w-[170px] px-4 py-3
                                text-center text-xs font-semibold
                                uppercase tracking-wide text-gray-500"
                        >
                            Warm Body Status
                        </th>


                        {{-- ================================================= --}}
                        {{-- PREVIOUS --}}
                        {{-- ================================================= --}}

                        <th
                            class="min-w-[190px] border-l
                                border-gray-200 bg-slate-50
                                px-4 py-3 text-left text-xs
                                font-semibold uppercase text-slate-600"
                        >
                            Previous Availment
                        </th>


                        {{-- ================================================= --}}
                        {{-- CURRENT --}}
                        {{-- ================================================= --}}

                        <th
                            class="min-w-[190px] border-l
                                border-gray-200 bg-green-100
                                px-4 py-3 text-left text-xs
                                font-semibold uppercase text-green-700"
                        >
                            Current Availment
                        </th>


                        {{-- ================================================= --}}
                        {{-- COMPARISON --}}
                        {{-- ================================================= --}}

                        <th
                            class="min-w-[120px] px-4 py-3
                                text-center text-xs font-semibold
                                uppercase text-gray-500"
                        >
                            Comparison
                        </th>


                        {{-- ================================================= --}}
                        {{-- VALIDATION --}}
                        {{-- ================================================= --}}

                        <th
                            class="min-w-[120px] px-4 py-3
                                text-center text-xs font-semibold
                                uppercase text-gray-500"
                        >
                            Validation
                        </th>


                        {{-- ================================================= --}}
                        {{-- ACTION --}}
                        {{-- ================================================= --}}

                        <th
                            class="min-w-[100px] px-4 py-3
                                text-center text-xs font-semibold
                                uppercase text-gray-500"
                        >
                            Action
                        </th>

                    </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @forelse($medicalAllowances as $record)

                            @php
                                /*
                                |--------------------------------------------------------------------------
                                | EMPLOYEE NAME
                                |--------------------------------------------------------------------------
                                |
                                | $record is a stdClass returned by DB::table().
                                | Therefore, use the selected columns directly.
                                |
                                */

                                $name = trim(
                                    ($record->first_name ?? '') . ' ' .
                                    ($record->middle_name ?? '') . ' ' .
                                    ($record->last_name ?? '') . ' ' .
                                    ($record->extension_name ?? '')
                                );

                                /*
                                |--------------------------------------------------------------------------
                                | FALLBACK NAME
                                |--------------------------------------------------------------------------
                                */

                                if ($name === '') {
                                    $name = $record->name ?? 'Unknown Employee';
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | SCHOOL ASSIGNMENT
                                |--------------------------------------------------------------------------
                                */

                                $schoolName = trim(
                                    (string) ($record->school_name ?? '')
                                );

                                $schoolDistrict = trim(
                                    (string) ($record->school_district ?? '')
                                );


                                /*
                                |--------------------------------------------------------------------------
                                | POSITION
                                |--------------------------------------------------------------------------
                                */

                                $positionTitle = trim(
                                    (string) ($record->position_title ?? '')
                                );

                                $employmentStatus = trim(
                                    (string) ($record->employment_status ?? '')
                                );

                                /*
                                |--------------------------------------------------------------------------
                                | WARM BODY STATUS
                                |--------------------------------------------------------------------------
                                */

                                $warmBodyStatus = trim(
                                    (string) ($record->warm_body_status ?? '')
                                );


                                /*
                                |--------------------------------------------------------------------------
                                | ACTIVE / INACTIVE
                                |--------------------------------------------------------------------------
                                |
                                | Inactive:
                                |
                                | - Vacant (Resigned)
                                | - Vacant (Retired)
                                | - Vacant (Others)
                                |
                                | All other values are considered Active.
                                |
                                */

                                $inactiveWarmBodyStatuses = [
                                    'Vacant (Resigned)',
                                    'Vacant (Retired)',
                                    'Vacant (Others)',
                                ];


                                $isInactive = in_array(
                                    $warmBodyStatus,
                                    $inactiveWarmBodyStatuses,
                                    true
                                );


                                $warmBodyClassification =
                                    $isInactive
                                        ? 'Inactive'
                                        : 'Active';

                                /*
                                |--------------------------------------------------------------------------
                                | PREVIOUS YEAR MEDICAL ALLOWANCE
                                |--------------------------------------------------------------------------
                                */

                                $previous = trim(
                                    (string) (
                                        $record->previous_mode_of_availment ?? ''
                                    )
                                );


                                /*
                                |--------------------------------------------------------------------------
                                | CURRENT YEAR MEDICAL ALLOWANCE
                                |--------------------------------------------------------------------------
                                */

                                $current = trim(
                                    (string) (
                                        $record->mode_of_availment ?? ''
                                    )
                                );


                                /*
                                |--------------------------------------------------------------------------
                                | COMPARISON
                                |--------------------------------------------------------------------------
                                */

                                if (!$record->previous_medical_id) {

                                    $comparison = 'missing';

                                } elseif ($previous === $current) {

                                    $comparison = 'same';

                                } else {

                                    $comparison = 'changed';
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | VALIDATION STATUS
                                |--------------------------------------------------------------------------
                                */

                                $validationStatus = strtolower(
                                    trim(
                                        $record->validation_status
                                        ?? 'Pending'
                                    )
                                );


                                /*
                                |--------------------------------------------------------------------------
                                | LOCK STATUS
                                |--------------------------------------------------------------------------
                                */

                                $isLocked =
                                    auth()->user()?->role === 'admin'
                                    &&
                                    $medicalSubmission?->status === 'Verified';


                                /*
                                |--------------------------------------------------------------------------
                                | INITIAL
                                |--------------------------------------------------------------------------
                                */

                                $initial = strtoupper(
                                    substr(
                                        $record->first_name
                                        ?? $record->name
                                        ?? 'E',
                                        0,
                                        1
                                    )
                                );
                            @endphp


                            <tr
                                x-data="{ updateModalOpen: false }"
                                class="transition hover:bg-gray-50"
                            >

                                {{-- =========================================================
                                    EMPLOYEE
                                ========================================================== --}}

                                <td class="px-4 py-4">

                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $name ?: '—' }}
                                    </p>

                                    <p class="mt-1 break-all text-xs text-gray-500">
                                        {{ $record->email ?? '—' }}
                                    </p>

                                    @if(!empty($record->employee_id))

                                        <p class="mt-1 text-[11px] text-gray-400">
                                            Employee ID:
                                            {{ $record->employee_id }}
                                        </p>

                                    @endif

                                </td>


                                {{-- =========================================================
                                    SCHOOL ASSIGNMENT
                                    SUPER ADMIN ONLY
                                ========================================================== --}}

                                @if(auth()->user()->role === 'super_admin')

                                    <td class="px-4 py-4 text-sm text-gray-700">

                                        @if($schoolName !== '')

                                            <p class="font-medium text-gray-800">
                                                {{ $schoolName }}
                                            </p>

                                            @if($schoolDistrict !== '')

                                                <p class="mt-1 text-xs text-gray-500">
                                                    {{ $schoolDistrict }}
                                                </p>

                                            @endif

                                        @else

                                            <span
                                                class="inline-flex rounded-full
                                                    bg-gray-100 px-2.5 py-1
                                                    text-xs font-semibold
                                                    text-gray-600"
                                            >
                                                No School Assignment
                                            </span>

                                        @endif

                                    </td>

                                @endif


                                {{-- =========================================================
                                    POSITION STATUS
                                ========================================================== --}}

                                <td class="px-4 py-4 text-center">

                                    <p class="text-sm font-medium text-gray-800">
                                        {{ $positionTitle ?: '—' }}
                                    </p>


                                    @if($employmentStatus !== '')

                                        <span
                                            class="mt-1 inline-flex rounded-full
                                                bg-green-50 px-2.5 py-1
                                                text-xs font-semibold
                                                text-green-700"
                                        >
                                            {{ $employmentStatus }}
                                        </span>

                                    @else

                                        <span class="text-sm text-gray-400">
                                            —
                                        </span>

                                    @endif

                                </td>

                                {{-- =========================================================
                                    WARM BODY STATUS
                                ========================================================== --}}

                                <td class="px-4 py-4 text-center">

                                    <div class="flex flex-col items-center justify-center gap-1.5">

                                        {{-- =================================================
                                            ACTIVE / INACTIVE CLASSIFICATION
                                        ================================================== --}}

                                        @if($isInactive)

                                            <span
                                                class="inline-flex items-center justify-center gap-1.5
                                                    rounded-full bg-red-50
                                                    px-2.5 py-1
                                                    text-xs font-semibold text-red-700"
                                            >

                                                <span
                                                    class="h-1.5 w-1.5 rounded-full bg-red-500"
                                                ></span>

                                                Inactive

                                            </span>

                                        @else

                                            <span
                                                class="inline-flex items-center justify-center gap-1.5
                                                    rounded-full bg-green-50
                                                    px-2.5 py-1
                                                    text-xs font-semibold text-green-700"
                                            >

                                                <span
                                                    class="h-1.5 w-1.5 rounded-full bg-green-500"
                                                ></span>

                                                Active

                                            </span>

                                        @endif


                                        {{-- =================================================
                                            ACTUAL WARM BODY STATUS
                                        ================================================== --}}

                                        @if($warmBodyStatus !== '')

                                            <span
                                                class="text-center text-xs
                                                    {{ $isInactive
                                                        ? 'font-medium text-red-600'
                                                        : 'text-gray-500' }}"
                                            >
                                                {{ $warmBodyStatus }}
                                            </span>

                                        @else

                                            <span class="text-center text-xs text-gray-400">
                                                Not Specified
                                            </span>

                                        @endif

                                    </div>

                                </td>


                                {{-- =========================================================
                                    PREVIOUS YEAR
                                ========================================================== --}}

                                <td
                                    class="border-l border-gray-100
                                        bg-slate-50/60 px-4 py-4"
                                >

                                    @if($record->previous_medical_id)

                                        <p class="text-sm font-medium text-gray-800">
                                            {{ $previous ?: '—' }}
                                        </p>

                                    @else

                                        <span
                                            class="inline-flex rounded-md
                                                bg-purple-50 px-2.5 py-1
                                                text-xs font-semibold
                                                text-purple-700"
                                        >
                                            Needs Review
                                        </span>

                                        <p class="mt-1 text-xs text-gray-400">
                                            No existing {{ $previousYear }} record
                                        </p>

                                    @endif

                                </td>


                                {{-- =========================================================
                                    CURRENT YEAR
                                ========================================================== --}}

                                <td
                                    class="border-l border-gray-100
                                        bg-green-50/30 px-4 py-4"
                                >

                                    <p class="text-sm font-semibold text-green-800">
                                        {{ $current ?: '—' }}
                                    </p>

                                </td>


                                {{-- =========================================================
                                    COMPARISON
                                ========================================================== --}}

                                <td class="px-4 py-4 text-center">

                                    @if($comparison === 'same')

                                        <span
                                            class="inline-flex rounded-full
                                                bg-gray-100 px-2.5 py-1
                                                text-xs font-semibold
                                                text-gray-600"
                                        >
                                            No Changes
                                        </span>

                                    @elseif($comparison === 'changed')

                                        <span
                                            class="inline-flex rounded-full
                                                bg-blue-50 px-2.5 py-1
                                                text-xs font-semibold
                                                text-blue-700"
                                        >
                                            Changed
                                        </span>

                                    @else

                                        <span
                                            class="inline-flex rounded-full
                                                bg-purple-50 px-2.5 py-1
                                                text-xs font-semibold
                                                text-purple-700"
                                        >
                                            Review
                                        </span>

                                    @endif

                                </td>


                                {{-- =========================================================
                                    VALIDATION
                                ========================================================== --}}

                                <td class="px-4 py-4 text-center">

                                    @if($validationStatus === 'validated')

                                        <span
                                            class="inline-flex items-center gap-1
                                                rounded-full bg-green-50
                                                px-2.5 py-1 text-xs
                                                font-semibold text-green-700"
                                        >
                                            ✓ Validated
                                        </span>

                                    @else

                                        <span
                                            class="inline-flex items-center gap-1
                                                rounded-full bg-amber-50
                                                px-2.5 py-1 text-xs
                                                font-semibold text-amber-700"
                                        >

                                            <span
                                                class="h-1.5 w-1.5
                                                    rounded-full bg-amber-500"
                                            ></span>

                                            Pending

                                        </span>

                                    @endif

                                </td>


                                {{-- =========================================================
                                    ACTION
                                ========================================================== --}}

                                <td class="px-4 py-4 text-center">

                                    <button
                                        type="button"

                                        @disabled($isLocked)

                                        @if(!$isLocked)
                                            @click="updateModalOpen = true"
                                        @endif

                                        class="
                                            inline-flex min-h-10 min-w-[90px]
                                            items-center justify-center
                                            rounded-lg px-5 py-2
                                            text-sm font-semibold
                                            transition shadow-sm

                                            {{ $isLocked
                                                ? 'cursor-not-allowed border border-gray-200 bg-gray-100 text-gray-400 shadow-none'
                                                : 'border border-green-600 bg-green-50 text-green-700 hover:bg-green-700 hover:text-white'
                                            }}
                                        "
                                    >
                                        Review
                                    </button>


                                    {{-- =====================================================
                                        UPDATE MODAL
                                    ====================================================== --}}

                                    <template x-teleport="body">

                                        <div
                                            x-cloak
                                            x-show="updateModalOpen"
                                            x-transition.opacity

                                            @keydown.escape.window="
                                                updateModalOpen = false
                                            "

                                            class="fixed inset-0 z-50
                                                flex items-center justify-center
                                                bg-gray-900/50 p-4"

                                            role="dialog"
                                            aria-modal="true"
                                        >

                                            <div
                                                x-show="updateModalOpen"
                                                x-transition.scale.origin.center

                                                @click.outside="
                                                    updateModalOpen = false
                                                "

                                                class="w-full max-w-xl
                                                    overflow-hidden rounded-xl
                                                    bg-white shadow-2xl"
                                            >

                                                {{-- =========================================
                                                    MODAL HEADER
                                                ========================================== --}}

                                                <div
                                                    class="flex items-center
                                                        justify-between
                                                        border-b border-gray-200
                                                        px-5 py-4"
                                                >

                                                    <div>

                                                        <h3
                                                            class="text-base font-bold
                                                                text-gray-900"
                                                        >
                                                            Review Medical Allowance
                                                        </h3>

                                                        <p
                                                            class="mt-0.5 text-xs
                                                                text-gray-500"
                                                        >
                                                            Verify both year records
                                                            before saving.
                                                        </p>

                                                    </div>


                                                    <button
                                                        type="button"

                                                        @click="
                                                            updateModalOpen = false
                                                        "

                                                        class="flex h-9 w-9
                                                            items-center justify-center
                                                            rounded-lg text-gray-400
                                                            transition
                                                            hover:bg-gray-100
                                                            hover:text-gray-700"
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
                                                                d="M6 18L18 6M6 6l12 12"
                                                            />
                                                        </svg>

                                                    </button>

                                                </div>


                                                {{-- =========================================
                                                    FORM
                                                ========================================== --}}

                                                <form
                                                    method="POST"

                                                    action="{{ route(
                                                        'medical-allowance.update-availment',
                                                        $record->users_id
                                                    ) }}"
                                                >

                                                    @csrf
                                                    @method('PATCH')


                                                    <div class="p-5">

                                                        {{-- =================================
                                                            EMPLOYEE INFORMATION
                                                        ================================== --}}

                                                        <div
                                                            class="mb-5 flex
                                                                items-start gap-3
                                                                rounded-lg
                                                                bg-gray-50 p-4"
                                                        >

                                                            <div
                                                                class="flex h-10 w-10
                                                                    shrink-0 items-center
                                                                    justify-center
                                                                    rounded-full
                                                                    bg-green-100
                                                                    text-sm font-bold
                                                                    text-green-700"
                                                            >
                                                                {{ $initial }}
                                                            </div>


                                            <div class="min-w-0">

                                                <p class="font-semibold text-gray-900">
                                                    {{ $name ?: 'Unknown Employee' }}
                                                </p>

                                                <p class="mt-0.5 text-xs text-gray-500">
                                                    {{ $positionTitle ?: 'No position' }}
                                                </p>


                                                @if($schoolName !== '')

                                                    <p class="mt-0.5 text-xs text-gray-500">

                                                        {{ $schoolName }}

                                                        @if($schoolDistrict !== '')
                                                            • {{ $schoolDistrict }}
                                                        @endif

                                                    </p>

                                                @else

                                                    <p class="mt-0.5 text-xs text-gray-400">
                                                        No school assignment
                                                    </p>

                                                @endif

                                            </div>

                                        </div>


                                        {{-- =================================
                                            YEARS
                                        ================================== --}}

                                        <div class="grid gap-4 sm:grid-cols-2">


                                            {{-- =============================
                                                PREVIOUS YEAR
                                            ============================== --}}

                                            <div
                                                class="rounded-xl
                                                    border border-slate-200
                                                    bg-slate-50 p-4"
                                            >

                                                <div
                                                    class="mb-3 flex
                                                        items-center
                                                        justify-between"
                                                >

                                                    <div>

                                                        <p
                                                            class="text-xs
                                                                font-semibold
                                                                uppercase
                                                                tracking-wide
                                                                text-slate-500"
                                                        >
                                                            Previous Year
                                                        </p>

                                                        <p
                                                            class="text-xl
                                                                font-bold
                                                                text-slate-800"
                                                        >
                                                            {{ $previousYear }}
                                                        </p>

                                                    </div>


                                                    @if(!$record->previous_medical_id)

                                                        <span
                                                            class="rounded-full
                                                                bg-purple-100
                                                                px-2 py-1
                                                                text-[10px]
                                                                font-semibold
                                                                text-purple-700"
                                                        >
                                                            Needs Review
                                                        </span>

                                                    @endif

                                                </div>


                                                <label
                                                    for="previous_mode_{{ $record->users_id }}"
                                                    class="mb-2 block
                                                        text-xs font-semibold
                                                        text-gray-700"
                                                >
                                                    Mode of Availment
                                                </label>


                                                <select
                                                    id="previous_mode_{{ $record->users_id }}"
                                                    name="previous_mode_of_availment"
                                                    required

                                                    class="w-full rounded-lg
                                                        border-gray-300
                                                        bg-white text-sm
                                                        shadow-sm
                                                        focus:border-green-600
                                                        focus:ring-green-600"
                                                >

                                                    <option
                                                        value=""
                                                        disabled
                                                        @selected(!$previous)
                                                    >
                                                        Select availment
                                                    </option>


                                                    @foreach($availmentOptions as $option)

                                                        <option
                                                            value="{{ $option }}"

                                                            @selected(
                                                                $previous === $option
                                                            )
                                                        >
                                                            {{ $option }}
                                                        </option>

                                                    @endforeach

                                                </select>


                                                @if(!$record->previous_medical_id)

                                                    <p
                                                        class="mt-2 text-xs
                                                            leading-5
                                                            text-slate-500"
                                                    >
                                                        No record exists yet.
                                                        Selecting an option will
                                                        create the
                                                        {{ $previousYear }}
                                                        record.
                                                    </p>

                                                @endif

                                            </div>


                                            {{-- =============================
                                                CURRENT YEAR
                                            ============================== --}}

                                            <div
                                                class="rounded-xl
                                                    border border-green-200
                                                    bg-green-50 p-4"
                                            >

                                                <div class="mb-3">

                                                    <p
                                                        class="text-xs
                                                            font-semibold
                                                            uppercase
                                                            tracking-wide
                                                            text-green-600"
                                                    >
                                                        Current Year
                                                    </p>

                                                    <p
                                                        class="text-xl
                                                            font-bold
                                                            text-green-800"
                                                    >
                                                        {{ $currentYear }}
                                                    </p>

                                                </div>


                                                <label
                                                    for="current_mode_{{ $record->users_id }}"
                                                    class="mb-2 block
                                                        text-xs font-semibold
                                                        text-gray-700"
                                                >
                                                    Mode of Availment
                                                </label>


                                                <select
                                                    id="current_mode_{{ $record->users_id }}"
                                                    name="current_mode_of_availment"
                                                    required

                                                    class="w-full rounded-lg
                                                        border-green-300
                                                        bg-white text-sm
                                                        shadow-sm
                                                        focus:border-green-600
                                                        focus:ring-green-600"
                                                >

                                                    <option
                                                        value=""
                                                        disabled
                                                        @selected(!$current)
                                                    >
                                                        Select availment
                                                    </option>


                                                    @foreach($availmentOptions as $option)

                                                        <option
                                                            value="{{ $option }}"

                                                            @selected(
                                                                $current === $option
                                                            )
                                                        >
                                                            {{ $option }}
                                                        </option>

                                                    @endforeach

                                                </select>

                                            </div>

                                        </div>


                                        {{-- =================================
                                            NOTICE
                                        ================================== --}}

                                        <div
                                            class="mt-4 flex items-start
                                                gap-2 rounded-lg
                                                border border-blue-100
                                                bg-blue-50 px-3 py-2.5"
                                        >

                                            <svg
                                                class="mt-0.5 h-4 w-4
                                                    shrink-0 text-blue-600"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <circle
                                                    cx="12"
                                                    cy="12"
                                                    r="9"
                                                />

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-width="2"
                                                    d="M12 11v5m0-8h.01"
                                                />
                                            </svg>


                                            <p
                                                class="text-xs leading-5
                                                    text-blue-700"
                                            >
                                                Review both selections carefully.
                                                These records will be included in
                                                the school's Medical Allowance
                                                validation.
                                            </p>

                                        </div>

                                    </div>


                                    {{-- =====================================
                                        FOOTER
                                    ====================================== --}}

                                    <div
                                        class="flex items-center justify-end gap-3
                                            border-t border-gray-200
                                            bg-gray-50 px-5 py-4"
                                    >

                                        {{-- CANCEL --}}
                                        <button
                                            type="button"
                                            @click="updateModalOpen = false"
                                            style="height: 48px; padding-left: 24px; padding-right: 24px;"
                                            class="rounded-lg
                                                border border-gray-300
                                                bg-white
                                                text-sm font-semibold
                                                text-gray-700
                                                transition
                                                hover:bg-gray-100"
                                        >
                                            Cancel
                                        </button>


                                        {{-- SAVE CHANGES --}}
                                        <button
                                            type="submit"
                                            style="height: 48px; min-width: 175px; padding-left: 24px; padding-right: 24px;"
                                            class="inline-flex
                                                items-center justify-center
                                                gap-2 rounded-lg
                                                bg-green-700
                                                text-sm font-semibold
                                                text-white
                                                shadow-sm transition
                                                hover:bg-green-800"
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
                                                    d="M5 13l4 4L19 7"
                                                />
                                            </svg>

                                            Save Changes

                                        </button>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </template>

                </td>

            </tr>


        @empty

            <tr>

                <td
                    colspan="{{ auth()->user()->role === 'super_admin'
                        ? 8
                        : 7 }}"

                    class="px-6 py-14 text-center"
                >

                    <div
                        class="mx-auto flex h-12 w-12
                            items-center justify-center
                            rounded-full bg-gray-100
                            text-gray-400"
                    >

                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >

                            <circle
                                cx="11"
                                cy="11"
                                r="7"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-width="2"
                                d="m20 20-3.5-3.5"
                            />

                        </svg>

                    </div>


                    <p
                        class="mt-3 text-sm font-semibold
                            text-gray-700"
                    >
                        No medical allowance records found.
                    </p>


                    @if($search !== '')

                        <p
                            class="mt-1 text-sm
                                text-gray-500"
                        >
                            No results matched
                            “{{ $search }}”.
                        </p>

                    @endif

                </td>

            </tr>

        @endforelse

    </tbody>

                </table>

            </div>


            {{-- =====================================================
                PAGINATION
            ====================================================== --}}

            <div
                class="border-t border-gray-200
                       bg-gray-50/50 px-4 py-3"
            >

                <div
                    class="flex flex-col gap-3
                           sm:flex-row sm:items-center
                           sm:justify-between"
                >

                    <p class="text-xs text-gray-500">

                        @if($medicalAllowances->total() > 0)

                            Showing

                            <span class="font-semibold text-gray-700">
                                {{ $medicalAllowances->firstItem() }}
                            </span>

                            to

                            <span class="font-semibold text-gray-700">
                                {{ $medicalAllowances->lastItem() }}
                            </span>

                            of

                            <span class="font-semibold text-gray-700">
                                {{ $medicalAllowances->total() }}
                            </span>

                            records

                        @else

                            Showing 0 records

                        @endif

                    </p>


                    <div>
                        {{ $medicalAllowances->links() }}
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


@once

<style>

    [x-cloak] {
        display: none !important;
    }

</style>

@endonce

</x-app-layout>