<x-app-layout>

    <div class="min-h-screen bg-gray-50 py-8">

        <div class="mx-auto max-w-7xl px-6">


            {{-- BREADCRUMB TRAIL --}}
            <div class="mb-4">

                <nav
                    class="flex items-center text-sm"
                    aria-label="Breadcrumb"
                >

                    {{-- Home --}}
                    <a
                        href="{{ route('dashboard') }}"
                        class="flex items-center font-medium text-gray-500
                            transition hover:text-green-700"
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
                        class="font-medium text-gray-500
                            transition hover:text-green-700"
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

                    {{-- Current Page --}}
                    <span class="font-semibold text-green-800">
                        Medical Allowance
                    </span>

                </nav>

            </div>
           
            {{-- TAB NAVIGATION --}}
            <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                <div class="flex w-full">

                    {{-- TAB 1: RECORDS --}}
                    <a
                        href="{{ route('data-management.medical-allowance') }}"
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


            {{-- =====================================================
                SUPER ADMIN - IMPORT PERSONNEL
            ====================================================== --}}
            @if(auth()->user()->role === 'super_admin')

                <div id="import" class="mb-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                    <div class="mb-5">

                        <h2 class="text-lg font-semibold text-gray-800">
                            Import Medical Allowance Records
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Upload an Excel file containing personnel medical
                            allowance records.
                        </p>

                    </div>


                    <form
                        action="{{ route('data-management.medical-allowance.import') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        >

                        @csrf

                        <div class="flex flex-col gap-3 md:flex-row md:items-center">

                            {{-- EXCEL FILE LABEL --}}
                            <label
                                for="file"
                                class="shrink-0 text-sm font-semibold text-gray-700"
                            >
                                EXCEL FILE
                            </label>


                            {{-- CUSTOM FILE INPUT --}}
                            <div class="relative flex h-10 flex-1">

                                {{-- REAL FILE INPUT --}}
                                <input
                                    type="file"
                                    id="file"
                                    name="file"
                                    accept=".xlsx,.xls"
                                    required
                                    class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0"
                                    onchange="document.getElementById('file-name').textContent =
                                        this.files.length ? this.files[0].name : 'No file selected'"
                                >


                                {{-- CUSTOM FILE DISPLAY --}}
                                <div
                                    class="flex h-full w-full items-center overflow-hidden
                                        rounded-lg border border-gray-300
                                        bg-white shadow-sm"
                                >

                                    {{-- BROWSE BUTTON --}}
                                    <span
                                        class="flex h-full shrink-0 items-center
                                            border-r border-green-200
                                            bg-green-50
                                            px-4
                                            text-sm font-semibold
                                            text-green-700"
                                    >
                                        Browse...
                                    </span>


                                    {{-- FILE NAME --}}
                                    <span
                                        id="file-name"
                                        class="truncate px-4 text-sm text-gray-500"
                                    >
                                        No file selected
                                    </span>

                                </div>

                            </div>


                            {{-- ACTION BUTTONS --}}
                            <div class="flex shrink-0 items-center gap-2">

                                {{-- UPLOAD BUTTON --}}
                                <button
                                    type="submit"
                                    class="flex h-10 items-center justify-center gap-2
                                        rounded-lg
                                        bg-green-700
                                        px-5
                                        text-sm
                                        font-semibold
                                        text-white
                                        shadow-sm
                                        transition
                                        duration-200
                                        hover:bg-green-800
                                        focus:outline-none
                                        focus:ring-2
                                        focus:ring-green-500
                                        focus:ring-offset-2"
                                    >

                                    {{-- UPLOAD ICON --}}
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

                                {{-- DOWNLOAD TEMPLATE --}}
                                <a
                                    href="{{ route('data-management.medical-allowance.template') }}"
                                    class="flex h-10 items-center justify-center gap-2
                                        rounded-lg
                                        border border-green-700
                                        bg-white
                                        px-4
                                        text-sm
                                        font-semibold
                                        text-green-700
                                        shadow-sm
                                        transition
                                        duration-200
                                        hover:bg-green-50
                                        focus:outline-none
                                        focus:ring-2
                                        focus:ring-green-500
                                        focus:ring-offset-2"
                                    >

                                    {{-- DOWNLOAD ICON --}}
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

                        {{-- HELP TEXT --}}
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

            {{-- =====================================================
                ADMIN - MANUAL PERSONNEL ENTRY
            ====================================================== --}}
            @elseif(auth()->user()->role === 'admin')
            
                <div
                    class="rounded-2xl border border-green-200
                        bg-white p-6 shadow-sm"
                    >

                    <div
                        class="flex flex-col gap-5
                            md:flex-row md:items-center
                            md:justify-between"
                    >

                        <div class="flex items-start gap-4">

                            <div>

                                <h2 class="text-lg font-bold text-gray-900">
                                    Add Personnel Information
                                </h2>

                                <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">
                                    Excel import is available only to the Super Admin.
                                    To add a personnel record, complete the personnel information form.
                                </p>

                            </div>

                        </div>


                        <a
                            href="https://forms.gle/zrz8AGM3bdvAWoJ67"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex h-10 shrink-0
                                items-center justify-center gap-2
                                rounded-lg bg-green-700
                                px-5 text-sm font-semibold
                                text-white shadow-sm
                                transition hover:bg-green-800
                                focus:outline-none
                                focus:ring-2 focus:ring-green-500
                                focus:ring-offset-2"
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
                                    d="M12 4.5v15m7.5-7.5h-15"
                                />
                            </svg>

                            Add Personnel

                        </a>

                    </div>

                </div>
                <br>

            @endif

            {{-- ========================================================= --}}
            {{-- MEDICAL ALLOWANCE RECORDS --}}
            {{-- ========================================================= --}}

            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

                {{-- UPDATE NOTIFICATION --}}
                @if (session('success'))
                    <div
                        id="update-notification"
                        tabindex="-1"
                        x-data="{ show: true }"
                        x-init="
                            $nextTick(() => {
                                setTimeout(() => {
                                    $el.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'center'
                                    });

                                    $el.focus({ preventScroll: true });
                                }, 200);
                            });

                            setTimeout(() => show = false, 6000);
                        "
                        x-show="show"
                        x-transition
                        class="mb-4 flex items-start justify-between rounded-lg
                            border border-green-200 bg-green-50 text-green-800
                            focus:outline-none focus:ring-2 focus:ring-green-500
                            focus:ring-offset-2"
                        style="padding: 14px 18px;"
                        role="alert"
                    >
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-8 w-8 shrink-0 items-center justify-center
                                    rounded-full bg-green-100"
                            >
                                <svg
                                    class="h-5 w-5 text-green-700"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            </div>

                            <div>
                                <p class="text-sm font-semibold">
                                    Update successful
                                </p>

                                <p class="mt-0.5 text-sm text-green-700">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>

                        <button
                            type="button"
                            @click="show = false"
                            class="ml-4 rounded-md p-1 text-green-600
                                hover:bg-green-100 hover:text-green-800"
                            aria-label="Close notification"
                        >
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
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
                @endif

                {{-- HEADER --}}
                <div class="flex flex-col gap-4 border-b border-gray-200 p-6 md:flex-row md:items-center md:justify-between bg-green-800">

                    <div>

                        <h2 class="text-lg font-semibold text-white">
                            Medical Allowance Records

                            @if (auth()->user()->role === 'admin')
                                (
                                    {{ auth()->user()->employmentStatus?->school?->school_name ?? 'No assigned school' }}
                                    -
                                    {{ auth()->user()->employmentStatus?->school?->school_district ?? 'No district' }}
                                )
                            @elseif (auth()->user()->role === 'super_admin')
                                (All Schools)
                            @endif
                        </h2>

                        <p class="mt-1 text-sm text-white">
                            List of personnel medical allowance records and
                            related employment information.
                        </p>

                    </div>

                </div>


                {{-- SEARCH --}}
                <div 
                    id="medical-allowance-table"
                    class="border-b border-gray-200 p-6">

                    <form
                        action="{{ route('data-management.medical-allowance') }}"
                        method="GET"
                    >

                        <div class="flex flex-col gap-3 md:flex-row">

                            <div class="flex-1">

                                <label
                                    for="search"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Search Personnel
                                </label>

                                <input
                                    type="text"
                                    id="search"
                                    name="search"
                                    value="{{ $search }}"
                                    placeholder="Search by name, email, school, or status..."
                                    class="w-full rounded-md border-gray-300
                                        text-sm shadow-sm
                                        focus:border-green-600
                                        focus:ring-green-600"
                                >

                            </div>


                            <div class="flex items-end gap-2">

                                <button
                                    type="submit"
                                    class="rounded-md bg-green-700
                                        px-5 py-2.5
                                        text-sm font-semibold text-white
                                        hover:bg-green-800"
                                >
                                    Search
                                </button>


                                @if($search !== '')

                                    <a
                                        href="{{ route('data-management.medical-allowance') }}"
                                        class="rounded-md border border-gray-300
                                            bg-white px-5 py-2.5
                                            text-sm font-semibold text-gray-700
                                            hover:bg-gray-50"
                                    >
                                        Clear
                                    </a>

                                @endif

                            </div>

                        </div>

                    </form>

                </div>


                {{-- TABLE --}}
                <div
                    class="overflow-x-auto scroll-mt-24"
                >

                     @php
                        $sortUrl = function ($column) use ($sort, $direction) {

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

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-green-50">
                        <tr>

                            {{-- NAME AND EMAIL --}}
                            <th class="min-w-[220px] px-4 py-3 text-left text-xs font-semibold
                                    uppercase tracking-wider text-gray-600">

                                <a
                                    href="{{ $sortUrl('name') }}"
                                    class="flex items-center justify-between gap-3 hover:text-green-700"
                                >
                                    <span>Name and Email</span>

                                    {{-- SORT ICON --}}
                                    <span class="flex shrink-0 flex-col items-center leading-[9px]">

                                        {{-- ASCENDING --}}
                                        <span class="text-[11px] font-black
                                            {{ $sort === 'name' && $direction === 'asc'
                                                ? 'text-green-700'
                                                : 'text-gray-300'
                                            }}">
                                            ▲
                                        </span>

                                        {{-- DESCENDING --}}
                                        <span class="text-[11px] font-black
                                            {{ $sort === 'name' && $direction === 'desc'
                                                ? 'text-green-700'
                                                : 'text-gray-300'
                                            }}">
                                            ▼
                                        </span>

                                    </span>
                                </a>

                            </th>


                            @if (auth()->user()->role === 'super_admin')

                                {{-- SCHOOL NAME --}}
                                <th class="min-w-[220px] px-4 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">

                                    <a
                                        href="{{ $sortUrl('school') }}"
                                        class="flex items-center justify-between gap-3 hover:text-green-700"
                                    >
                                        <span>School Name</span>

                                        <span class="flex shrink-0 flex-col items-center leading-[9px]">

                                            <span class="text-[11px] font-black
                                                {{ $sort === 'school' && $direction === 'asc'
                                                    ? 'text-green-700'
                                                    : 'text-gray-300'
                                                }}">
                                                ▲
                                            </span>

                                            <span class="text-[11px] font-black
                                                {{ $sort === 'school' && $direction === 'desc'
                                                    ? 'text-green-700'
                                                    : 'text-gray-300'
                                                }}">
                                                ▼
                                            </span>

                                        </span>
                                    </a>

                                </th>


                                {{-- DISTRICT --}}
                                <th class="min-w-[140px] px-4 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">

                                    <a
                                        href="{{ $sortUrl('district') }}"
                                        class="flex items-center justify-between gap-3 hover:text-green-700"
                                    >
                                        <span>District</span>

                                        <span class="flex shrink-0 flex-col items-center leading-[9px]">

                                            <span class="text-[11px] font-black
                                                {{ $sort === 'district' && $direction === 'asc'
                                                    ? 'text-green-700'
                                                    : 'text-gray-300'
                                                }}">
                                                ▲
                                            </span>

                                            <span class="text-[11px] font-black
                                                {{ $sort === 'district' && $direction === 'desc'
                                                    ? 'text-green-700'
                                                    : 'text-gray-300'
                                                }}">
                                                ▼
                                            </span>

                                        </span>
                                    </a>

                                </th>


                                {{-- ITEM FROM SCHOOL LEVEL --}}
                                <th class="min-w-[180px] px-4 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">

                                    <a
                                        href="{{ $sortUrl('school_level') }}"
                                        class="flex items-center justify-between gap-3 hover:text-green-700"
                                    >
                                        <span>
                                            Item From<br>
                                            School Level
                                        </span>

                                        <span class="flex shrink-0 flex-col items-center leading-[9px]">

                                            <span class="text-[11px] font-black
                                                {{ $sort === 'school_level' && $direction === 'asc'
                                                    ? 'text-green-700'
                                                    : 'text-gray-300'
                                                }}">
                                                ▲
                                            </span>

                                            <span class="text-[11px] font-black
                                                {{ $sort === 'school_level' && $direction === 'desc'
                                                    ? 'text-green-700'
                                                    : 'text-gray-300'
                                                }}">
                                                ▼
                                            </span>

                                        </span>
                                    </a>

                                </th>

                            @endif


                            {{-- POSITION TITLE --}}
                            <th class="min-w-[200px] px-4 py-3 text-left text-xs font-semibold
                                    uppercase tracking-wider text-gray-600">

                                <a
                                    href="{{ $sortUrl('position') }}"
                                    class="flex items-center justify-between gap-3 hover:text-green-700"
                                >
                                    <span>Position Title</span>

                                    <span class="flex shrink-0 flex-col items-center leading-[9px]">

                                        <span class="text-[11px] font-black
                                            {{ $sort === 'position' && $direction === 'asc'
                                                ? 'text-green-700'
                                                : 'text-gray-300'
                                            }}">
                                            ▲
                                        </span>

                                        <span class="text-[11px] font-black
                                            {{ $sort === 'position' && $direction === 'desc'
                                                ? 'text-green-700'
                                                : 'text-gray-300'
                                            }}">
                                            ▼
                                        </span>

                                    </span>
                                </a>

                            </th>


                            {{-- EMPLOYMENT STATUS --}}
                            <th class="min-w-[170px] px-4 py-3 text-left text-xs font-semibold
                                    uppercase tracking-wider text-gray-600">

                                <a
                                    href="{{ $sortUrl('employment_status') }}"
                                    class="flex items-center justify-between gap-3 hover:text-green-700"
                                >
                                    <span>
                                        Employment<br>
                                        Status
                                    </span>

                                    <span class="flex shrink-0 flex-col items-center leading-[9px]">

                                        <span class="text-[11px] font-black
                                            {{ $sort === 'employment_status' && $direction === 'asc'
                                                ? 'text-green-700'
                                                : 'text-gray-300'
                                            }}">
                                            ▲
                                        </span>

                                        <span class="text-[11px] font-black
                                            {{ $sort === 'employment_status' && $direction === 'desc'
                                                ? 'text-green-700'
                                                : 'text-gray-300'
                                            }}">
                                            ▼
                                        </span>

                                    </span>
                                </a>

                            </th>


                            {{-- MEDICAL ALLOWANCE MODE AVAILMENT --}}
                            <th class="min-w-[230px] px-4 py-3 text-left text-xs font-semibold
                                    uppercase tracking-wider text-gray-600">

                                <a
                                    href="{{ $sortUrl('mode_of_availment') }}"
                                    class="flex items-center justify-between gap-3 hover:text-green-700"
                                >
                                    <span>
                                        Medical Allowance<br>
                                        Mode Availment
                                    </span>

                                    <span class="flex shrink-0 flex-col items-center leading-[9px]">

                                        <span class="text-[11px] font-black
                                            {{ $sort === 'mode_of_availment' && $direction === 'asc'
                                                ? 'text-green-700'
                                                : 'text-gray-300'
                                            }}">
                                            ▲
                                        </span>

                                        <span class="text-[11px] font-black
                                            {{ $sort === 'mode_of_availment' && $direction === 'desc'
                                                ? 'text-green-700'
                                                : 'text-gray-300'
                                            }}">
                                            ▼
                                        </span>

                                    </span>
                                </a>

                            </th>


                            {{-- DISBURSEMENT STATUS --}}
                            <th class="min-w-[190px] px-4 py-3 text-left text-xs font-semibold
                                    uppercase tracking-wider text-gray-600">

                                <a
                                    href="{{ $sortUrl('disbursement_status') }}"
                                    class="flex items-center justify-between gap-3 hover:text-green-700"
                                >
                                    <span>
                                        Disbursement<br>
                                        Status
                                    </span>

                                    <span class="flex shrink-0 flex-col items-center leading-[9px]">

                                        <span class="text-[11px] font-black
                                            {{ $sort === 'disbursement_status' && $direction === 'asc'
                                                ? 'text-green-700'
                                                : 'text-gray-300'
                                            }}">
                                            ▲
                                        </span>

                                        <span class="text-[11px] font-black
                                            {{ $sort === 'disbursement_status' && $direction === 'desc'
                                                ? 'text-green-700'
                                                : 'text-gray-300'
                                            }}">
                                            ▼
                                        </span>

                                    </span>
                                </a>

                            </th>


                            {{-- ACTION --}}
                            <th class="min-w-[110px] px-4 py-3 text-center text-xs font-semibold
                                    uppercase tracking-wider text-gray-600">
                                Action
                            </th>

                        </tr>
                    </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($medicalAllowances as $record)
                                @php
                                    $basic = $record->user?->basicInformation;

                                    $name = trim(
                                        ($basic?->first_name ?? '') . ' ' .
                                        ($basic?->middle_name ?? '') . ' ' .
                                        ($basic?->last_name ?? '') . ' ' .
                                        ($basic?->extension_name ?? '')
                                    );

                                    $employment = $record->user?->employmentStatus;
                                    $plantilla = $employment?->plantilla;
                                    $school = $employment?->school;

                                    $disbursementStatus = strtolower(
                                        trim($record->disbursement_status ?? '')
                                    );
                                @endphp

                                <tr
                                    class="hover:bg-gray-50"
                                    x-data="{ updateModalOpen: false }"
                                >
                                    {{-- NAME + EMAIL --}}
                                    <td class="min-w-[220px] px-4 py-4">
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ $name ?: '—' }}
                                        </div>

                                        <div class="mt-1 break-words text-sm text-gray-500">
                                            {{ $record->user?->email ?? '—' }}
                                        </div>
                                    </td>

                                    @if (auth()->user()->role === 'super_admin')
                                        {{-- SCHOOL NAME --}}
                                        <td class="min-w-[220px] px-4 py-4 text-sm text-gray-700">
                                            {{ $school?->school_name ?? '—' }}
                                        </td>

                                        {{-- DISTRICT --}}
                                        <td class="min-w-[140px] px-4 py-4 text-sm text-gray-700">
                                            {{ $school?->school_district ?? '—' }}
                                        </td>

                                        {{-- ITEM FROM SCHOOL LEVEL --}}
                                        <td class="min-w-[180px] px-4 py-4 text-sm text-gray-700">
                                            {{ $plantilla?->item_from_school_level ?? '—' }}
                                        </td>
                                    @endif

                                    {{-- POSITION TITLE --}}
                                    <td class="min-w-[200px] px-4 py-4 text-sm font-medium text-gray-900">
                                        {{ $plantilla?->position_title ?? '—' }}
                                    </td>

                                    {{-- EMPLOYMENT STATUS --}}
                                    <td class="min-w-[160px] px-4 py-4">
                                        @if ($employment?->employment_status)
                                            <span class="inline-flex rounded-full bg-green-100 px-3 py-1
                                                        text-xs font-semibold text-green-700">
                                                {{ $employment->employment_status }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-400">—</span>
                                        @endif
                                    </td>

                                    {{-- GROUP AVAILMENT --}}
                                    <td class="min-w-[200px] px-4 py-4 text-sm text-gray-700">
                                        {{ $record->mode_of_availment ?? '—' }}
                                    </td>

                                    {{-- DISBURSEMENT STATUS --}}
                                    <td class="min-w-[180px] px-4 py-4">
                                        @if ($record->disbursement_status)
                                            @if ($disbursementStatus === 'paid')
                                                <span class="inline-flex rounded-full bg-green-100 px-3 py-1
                                                            text-xs font-semibold text-green-700">
                                                    {{ $record->disbursement_status }}
                                                </span>
                                            @elseif ($disbursementStatus === 'pending')
                                                <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1
                                                            text-xs font-semibold text-yellow-700">
                                                    {{ $record->disbursement_status }}
                                                </span>
                                            @else
                                                <span class="inline-flex rounded-full bg-gray-100 px-3 py-1
                                                            text-xs font-semibold text-gray-700">
                                                    {{ $record->disbursement_status }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-sm text-gray-400">—</span>
                                        @endif
                                    </td>

                                    {{-- ACTION --}}
                                    <td class="whitespace-nowrap px-4 py-4 text-center">
                                        <button
                                            type="button"
                                            @click="updateModalOpen = true"
                                            class="inline-flex items-center rounded-md bg-green-700
                                                px-4 py-2 text-sm font-semibold text-white
                                                transition hover:bg-green-800"
                                        >
                                            Update
                                        </button>

                                        {{-- UPDATE MODAL --}}
                                        <template x-teleport="body">
                                            <div
                                                x-cloak
                                                x-show="updateModalOpen"
                                                x-transition.opacity
                                                @keydown.escape.window="updateModalOpen = false"
                                                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
                                                role="dialog"
                                                aria-modal="true"
                                                aria-labelledby="update-availment-title-{{ $record->id }}"
                                            >
                                                <div
                                                    x-show="updateModalOpen"
                                                    x-transition.scale.origin.center
                                                    @click.outside="updateModalOpen = false"
                                                    class="w-full overflow-hidden rounded-xl bg-white text-left shadow-2xl"
                                                    style="max-width: 420px;"
                                                >
                                                    {{-- GREEN HEADER --}}
                                                    <div class="flex items-start justify-between bg-green-700 px-5 py-4">
                                                        <div class="min-w-0 pr-4">
                                                            <h3
                                                                id="update-availment-title-{{ $record->id }}"
                                                                class="text-base font-semibold text-white"
                                                            >
                                                                Update Medical Allowance Availment
                                                            </h3>

                                                            <p class="mt-1 truncate text-sm text-green-100">
                                                                Name: {{ $name ?: 'Unknown personnel' }}
                                                            </p>
                                                        </div>

                                                        <button
                                                            type="button"
                                                            @click="updateModalOpen = false"
                                                            class="flex h-8 w-8 shrink-0 items-center justify-center
                                                                rounded-full text-green-100 transition
                                                                hover:bg-green-800 hover:text-white"
                                                            aria-label="Close modal"
                                                        >
                                                            <svg
                                                                class="h-5 w-5"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                                stroke="currentColor"
                                                            >
                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M6 18 18 6M6 6l12 12"
                                                                />
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    {{-- FORM --}}
                                                    <form
                                                        method="POST"
                                                        action="{{ route(
                                                            'medical-allowance.update-availment',
                                                            $record
                                                        ) }}"
                                                    >
                                                        @csrf
                                                        @method('PATCH')

                                                        <div class="space-y-4 px-5 py-5">
                                                            {{-- SCHOOL AND DISTRICT DETAILS --}}
                                                            <div
                                                                class="rounded-lg border border-green-100 bg-green-50"
                                                                style="padding: 18px 20px;"
                                                                >
                                                                <div class="space-y-4">
                                                                    {{-- SCHOOL --}}
                                                                    <div style="padding-bottom: 12px;">
                                                                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                                            School
                                                                        </p>

                                                                        <p class="mt-1 text-sm font-medium text-gray-800">
                                                                            {{ $school?->school_name ?? '—' }}
                                                                        </p>
                                                                    </div>

                                                                    {{-- DISTRICT --}}
                                                                    <div
                                                                        class="border-t border-green-100"
                                                                        style="padding-top: 12px;"
                                                                    >
                                                                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                                            District
                                                                        </p>

                                                                        <p class="mt-1 text-sm font-medium text-gray-800">
                                                                            {{ $school?->school_district ?? '—' }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            {{-- =====================================================
                                                                MODE OF AVAILMENT
                                                            ====================================================== --}}
                                                            <div
                                                                x-data="{
                                                                    originalMode: @js($record->mode_of_availment),
                                                                    selectedMode: @js($record->mode_of_availment),

                                                                    /*
                                                                    |--------------------------------------------------------------------------
                                                                    | INVALID CHANGES
                                                                    |--------------------------------------------------------------------------
                                                                    |
                                                                    | 1. Group Availment → Individual Availment = NOT ALLOWED
                                                                    | 2. Not Eligible → Individual Availment = NOT ALLOWED
                                                                    |
                                                                    */
                                                                    get isInvalid() {

                                                                        return (
                                                                            this.originalMode === 'Group Availment (HMO)' &&
                                                                            this.selectedMode === 'Individual Availment (HMO)'
                                                                        ) || (
                                                                            this.originalMode === 'Not Eligible' &&
                                                                            this.selectedMode === 'Individual Availment (HMO)'
                                                                        );
                                                                    },

                                                                    /*
                                                                    |--------------------------------------------------------------------------
                                                                    | CHECK IF VALUE CHANGED
                                                                    |--------------------------------------------------------------------------
                                                                    */
                                                                    get hasChanged() {
                                                                        return this.originalMode !== this.selectedMode;
                                                                    },

                                                                    /*
                                                                    |--------------------------------------------------------------------------
                                                                    | VALIDATION MESSAGE
                                                                    |--------------------------------------------------------------------------
                                                                    */
                                                                    get validationMessage() {

                                                                        if (
                                                                            this.originalMode === 'Group Availment (HMO)' &&
                                                                            this.selectedMode === 'Individual Availment (HMO)'
                                                                        ) {
                                                                            return 'Group Availment (HMO) cannot be changed to Individual Availment (HMO).';
                                                                        }

                                                                        if (
                                                                            this.originalMode === 'Not Eligible' &&
                                                                            this.selectedMode === 'Individual Availment (HMO)'
                                                                        ) {
                                                                            return 'Not Eligible cannot be changed to Individual Availment (HMO).';
                                                                        }

                                                                        return '';
                                                                    }
                                                                }"

                                                                class="rounded-lg border border-gray-200 bg-white"
                                                                style="padding: 18px 20px;"
                                                            >


                                                                {{-- =====================================================
                                                                    LABEL
                                                                ====================================================== --}}
                                                                <label
                                                                    for="mode_of_availment_{{ $record->id }}"
                                                                    class="mb-2 block text-sm font-semibold text-gray-700"
                                                                >
                                                                    Mode of Availment
                                                                    <span class="text-red-500">*</span>
                                                                </label>


                                                                {{-- =====================================================
                                                                    SELECT
                                                                ====================================================== --}}
                                                                <select
                                                                    id="mode_of_availment_{{ $record->id }}"
                                                                    name="mode_of_availment"
                                                                    x-model="selectedMode"
                                                                    required

                                                                    :class="isInvalid
                                                                        ? 'border-red-500 focus:border-red-500 focus:ring-red-200'
                                                                        : 'border-gray-300 focus:border-green-600 focus:ring-green-200'"

                                                                    class="w-full rounded-lg border bg-white
                                                                        px-3 py-2.5 text-sm text-gray-900
                                                                        focus:outline-none focus:ring-2"
                                                                >

                                                                    <option value="Group Availment (HMO)">
                                                                        Group Availment (HMO)
                                                                    </option>

                                                                    <option value="Individual Availment (HMO)">
                                                                        Individual Availment (HMO)
                                                                    </option>

                                                                    <option value="Not Eligible">
                                                                        Not Eligible
                                                                    </option>

                                                                </select>


                                                                {{-- =====================================================
                                                                    REAL-TIME ERROR MESSAGE
                                                                ====================================================== --}}
                                                                <div
                                                                    x-cloak
                                                                    x-show="isInvalid"
                                                                    x-transition

                                                                    class="mt-3 flex items-start gap-3
                                                                        rounded-lg border border-red-200
                                                                        bg-red-50 px-4 py-3"
                                                                >

                                                                    {{-- WARNING ICON --}}
                                                                    <svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        class="mt-0.5 h-5 w-5 shrink-0 text-red-600"
                                                                        fill="none"
                                                                        viewBox="0 0 24 24"
                                                                        stroke="currentColor"
                                                                        stroke-width="2"
                                                                    >
                                                                        <path
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            d="M12 9v3.75M12 16.5h.01"
                                                                        />

                                                                        <path
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            d="M10.29 3.86L1.82 18a2 2 0
                                                                            001.71 3h16.94a2 2 0
                                                                            001.71-3L13.71 3.86a2 2
                                                                            0 00-3.42 0z"
                                                                        />
                                                                    </svg>


                                                                    <div>

                                                                        <p class="text-sm font-semibold text-red-800">
                                                                            Option Not Allowed
                                                                        </p>

                                                                        <p
                                                                            class="mt-1 text-xs leading-5 text-red-700"
                                                                            x-text="validationMessage"
                                                                        ></p>

                                                                    </div>

                                                                </div>


                                                                {{-- =====================================================
                                                                    VALID CHANGE MESSAGE
                                                                ====================================================== --}}
                                                                <div
                                                                    x-cloak
                                                                    x-show="hasChanged && !isInvalid"
                                                                    x-transition

                                                                    class="mt-3 flex items-start gap-3
                                                                        rounded-lg border border-green-200
                                                                        bg-green-50 px-4 py-3"
                                                                >

                                                                    {{-- CHECK ICON --}}
                                                                    <svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        class="mt-0.5 h-5 w-5 shrink-0 text-green-600"
                                                                        fill="none"
                                                                        viewBox="0 0 24 24"
                                                                        stroke="currentColor"
                                                                        stroke-width="2"
                                                                    >
                                                                        <path
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            d="M5 13l4 4L19 7"
                                                                        />
                                                                    </svg>


                                                                    <div>

                                                                        <p class="text-sm font-semibold text-green-800">
                                                                            Change Allowed
                                                                        </p>

                                                                        <p class="mt-1 text-xs leading-5 text-green-700">
                                                                            This availment status can be updated.
                                                                        </p>

                                                                    </div>

                                                                </div>


                                                                {{-- =====================================================
                                                                    LARAVEL VALIDATION ERROR
                                                                ====================================================== --}}
                                                                @error('mode_of_availment')

                                                                    <p class="mt-2 text-sm text-red-600">
                                                                        {{ $message }}
                                                                    </p>

                                                                @enderror


                                                                {{-- =====================================================
                                                                    SAVE BUTTON
                                                                ====================================================== --}}
                                                                <div class="mt-5 flex justify-end">

                                                                    <button
                                                                        type="submit"

                                                                        :disabled="isInvalid"

                                                                        :class="isInvalid
                                                                            ? 'cursor-not-allowed bg-gray-300 text-gray-500'
                                                                            : 'bg-green-700 text-white hover:bg-green-800'"

                                                                        class="rounded-lg px-4 py-2
                                                                            text-sm font-semibold
                                                                            transition
                                                                            focus:outline-none
                                                                            focus:ring-2
                                                                            focus:ring-green-300"
                                                                    >
                                                                        Save Changes
                                                                    </button>

                                                                </div>

                                                            </div>

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
                                        colspan="{{ auth()->user()->role === 'super_admin' ? 10 : 7 }}"
                                        class="px-6 py-12 text-center"
                                    >
                                        <div class="text-sm font-medium text-gray-700">
                                            No medical allowance records found.
                                        </div>

                                        @if ($search !== '')
                                            <p class="mt-1 text-sm text-gray-500">
                                                No records matched your search for
                                                <span class="font-semibold">
                                                    “{{ $search }}”
                                                </span>.
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Required for Alpine modal elements --}}
                @once
                    <style>
                        [x-cloak] {
                            display: none !important;
                        }
                    </style>
                @endonce


                {{-- PAGINATION --}}
                <div class="border-t border-gray-200 px-6 py-4">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                        {{-- RECORD COUNT --}}
                        <div class="text-sm text-gray-500">

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

                        </div>


                        {{-- PAGINATION LINKS --}}
                        <div>

                            {{ $medicalAllowances->links() }}

                        </div>

                    </div>

                </div>


            </div>
        </div>
    </div>

</x-app-layout>