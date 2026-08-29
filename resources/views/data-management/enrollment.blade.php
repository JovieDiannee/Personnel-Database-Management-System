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
                        Enrollment Records
                    </span>

                </nav>

            </div>


            @if(session('enrollment_import_result'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-5">

                    <h3 class="text-lg font-bold text-green-900">
                        Enrollment Import Completed
                    </h3>

                    <div class="mt-4 grid gap-4 md:grid-cols-4">

                        <div>
                            <p class="text-sm text-gray-500">
                                New Records
                            </p>

                            <p class="text-2xl font-bold text-green-700">
                                {{ session('enrollment_import_result.imported') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Updated Records
                            </p>

                            <p class="text-2xl font-bold text-blue-700">
                                {{ session('enrollment_import_result.updated') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Skipped
                            </p>

                            <p class="text-2xl font-bold text-yellow-600">
                                {{ session('enrollment_import_result.skipped') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Errors
                            </p>

                            <p class="text-2xl font-bold text-red-600">
                                {{ count(session('enrollment_import_result.errors', [])) }}
                            </p>
                        </div>

                    </div>

                    @if(count(session('enrollment_import_result.errors', [])) > 0)

                        <div class="mt-5 rounded-lg border border-red-200 bg-red-50 p-4">

                            <h4 class="font-semibold text-red-800">
                                Import Errors
                            </h4>

                            <ul class="mt-2 list-disc pl-5 text-sm text-red-700">

                                @foreach(session('enrollment_import_result.errors', []) as $error)

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

            {{-- IMPORT SECTION --}}
            <div class="mb-6 rounded-xl border border-gray-200
                        bg-white p-6 shadow-sm">

                <div class="mb-5">

                    <h2 class="text-lg font-semibold text-gray-800">
                        Import Enrollment Records
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Upload an Excel file containing school enrollment records.
                    </p>

                </div>

                <form
                    action="{{ route('data-management.enrollment.import') }}"
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
                                href="{{ route('data-management.enrollment.download-template') }}"
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


            {{-- ENROLLMENT RECORDS --}}
            <div class="overflow-hidden rounded-xl
                        border border-gray-200 bg-white shadow-sm">


                {{-- TABLE HEADER --}}
                <div class="flex items-center justify-between
                            border-b border-gray-200 p-6">

                    <div>

                        <h2 class="text-lg font-semibold text-gray-800">
                            Enrollment Records
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Official enrollment records by school,
                            school year, and grade level.
                        </p>

                    </div>

                </div>


                {{-- SEARCH AND FILTER --}}
                <div class="border-b border-gray-200 p-6">

                    <div class="grid gap-4 md:grid-cols-4">


                        {{-- SCHOOL YEAR --}}
                        <div>

                            <label
                                for="school_year"
                                class="mb-2 block text-sm font-medium
                                       text-gray-700"
                            >
                                School Year
                            </label>

                            <select
                                id="school_year"
                                name="school_year"
                                class="w-full rounded-md border-gray-300
                                       text-sm shadow-sm
                                       focus:border-green-600
                                       focus:ring-green-600"
                            >

                                <option value="">
                                    All School Years
                                </option>

                            </select>

                        </div>


                        {{-- SCHOOL --}}
                        <div>

                            <label
                                for="school"
                                class="mb-2 block text-sm font-medium
                                       text-gray-700"
                            >
                                School
                            </label>

                            <select
                                id="school"
                                name="school"
                                class="w-full rounded-md border-gray-300
                                       text-sm shadow-sm
                                       focus:border-green-600
                                       focus:ring-green-600"
                            >

                                <option value="">
                                    All Schools
                                </option>

                            </select>

                        </div>


                        {{-- LEVEL --}}
                        <div>

                            <label
                                for="level"
                                class="mb-2 block text-sm font-medium
                                       text-gray-700"
                            >
                                Level
                            </label>

                            <select
                                id="level"
                                name="level"
                                class="w-full rounded-md border-gray-300
                                       text-sm shadow-sm
                                       focus:border-green-600
                                       focus:ring-green-600"
                            >

                                <option value="">
                                    All Levels
                                </option>

                                <option value="elementary">
                                    Elementary
                                </option>

                                <option value="jhs">
                                    Junior High School
                                </option>

                                <option value="shs">
                                    Senior High School
                                </option>

                            </select>

                        </div>


                        {{-- SEARCH --}}
                        <div>

                            <label
                                for="search"
                                class="mb-2 block text-sm font-medium
                                       text-gray-700"
                            >
                                Search
                            </label>

                            <input
                                type="text"
                                id="search"
                                name="search"
                                placeholder="Search school..."
                                class="w-full rounded-md border-gray-300
                                       text-sm shadow-sm
                                       focus:border-green-600
                                       focus:ring-green-600"
                            >

                        </div>

                    </div>

                </div>


                {{-- TABLE --}}
                <div class="overflow-x-auto">

                    <table class="min-w-max w-full divide-y divide-gray-200">

                        {{-- TABLE HEADER --}}
                        <thead class="bg-green-50">

                            {{-- GROUPED HEADER --}}
                            <tr class="border-b border-gray-200">

                                <th
                                    rowspan="2"
                                    class="sticky left-0 z-20 min-w-[110px]
                                        border-r border-gray-200
                                        bg-green-50 px-4 py-3
                                        text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600"
                                >
                                    School ID
                                </th>


                                <th
                                    rowspan="2"
                                    class="sticky left-[110px] z-20 min-w-[300px]
                                        border-r border-gray-200
                                        bg-green-50 px-4 py-3
                                        text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600"
                                >
                                    School Name
                                </th>


                                <th
                                    rowspan="2"
                                    class="min-w-[120px]
                                        border-r border-gray-200
                                        px-4 py-3
                                        text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600"
                                >
                                    School Year
                                </th>


                                {{-- ACTUAL ENROLLMENT GROUP --}}

                                <th
                                    colspan="13"
                                    class="border-b border-gray-200
                                        px-4 py-3 text-center
                                        text-xs font-bold uppercase
                                        tracking-wider text-gray-700"
                                >
                                    Actual Enrollment
                                </th>


                                <th
                                    rowspan="2"
                                    class="min-w-[100px]
                                        px-4 py-3 text-center
                                        text-xs font-semibold
                                        uppercase tracking-wider
                                        text-gray-600"
                                >
                                    Action
                                </th>

                            </tr>


                            {{-- GRADE HEADER --}}

                            <tr>

                                {{-- KINDERGARTEN --}}

                                <th
                                    class="min-w-[80px] border-r border-gray-200
                                        px-4 py-3 text-center
                                        text-xs font-semibold
                                        text-gray-600"
                                >
                                    K
                                </th>


                                {{-- GRADE 1 --}}

                                <th
                                    class="min-w-[80px] border-r border-gray-200
                                        px-4 py-3 text-center
                                        text-xs font-semibold
                                        text-gray-600"
                                >
                                    Gr 1
                                </th>


                                {{-- GRADE 2 --}}

                                <th
                                    class="min-w-[80px] border-r border-gray-200
                                        px-4 py-3 text-center
                                        text-xs font-semibold
                                        text-gray-600"
                                >
                                    Gr 2
                                </th>


                                {{-- GRADE 3 --}}

                                <th
                                    class="min-w-[80px] border-r border-gray-200
                                        px-4 py-3 text-center
                                        text-xs font-semibold
                                        text-gray-600"
                                >
                                    Gr 3
                                </th>


                                {{-- GRADE 4 --}}

                                <th
                                    class="min-w-[80px] border-r border-gray-200
                                        px-4 py-3 text-center
                                        text-xs font-semibold
                                        text-gray-600"
                                >
                                    Gr 4
                                </th>


                                {{-- GRADE 5 --}}

                                <th
                                    class="min-w-[80px] border-r border-gray-200
                                        px-4 py-3 text-center
                                        text-xs font-semibold
                                        text-gray-600"
                                >
                                    Gr 5
                                </th>


                                {{-- GRADE 6 --}}

                                <th
                                    class="min-w-[80px] border-r border-gray-200
                                        px-4 py-3 text-center
                                        text-xs font-semibold
                                        text-gray-600"
                                >
                                    Gr 6
                                </th>


                                {{-- GRADE 7 --}}

                                <th
                                    class="min-w-[80px] border-r border-gray-200
                                        px-4 py-3 text-center
                                        text-xs font-semibold
                                        text-gray-600"
                                >
                                    Gr 7
                                </th>


                                {{-- GRADE 8 --}}

                                <th
                                    class="min-w-[80px] border-r border-gray-200
                                        px-4 py-3 text-center
                                        text-xs font-semibold
                                        text-gray-600"
                                >
                                    Gr 8
                                </th>


                                {{-- GRADE 9 --}}

                                <th
                                    class="min-w-[80px] border-r border-gray-200
                                        px-4 py-3 text-center
                                        text-xs font-semibold
                                        text-gray-600"
                                >
                                    Gr 9
                                </th>


                                {{-- GRADE 10 --}}

                                <th
                                    class="min-w-[80px] border-r border-gray-200
                                        px-4 py-3 text-center
                                        text-xs font-semibold
                                        text-gray-600"
                                >
                                    Gr 10
                                </th>


                                {{-- GRADE 11 --}}

                                <th
                                    class="min-w-[80px] border-r border-gray-200
                                        px-4 py-3 text-center
                                        text-xs font-semibold
                                        text-gray-600"
                                >
                                    Gr 11
                                </th>


                                {{-- GRADE 12 --}}

                                <th
                                    class="min-w-[80px]
                                        px-4 py-3 text-center
                                        text-xs font-semibold
                                        text-gray-600"
                                >
                                    Gr 12
                                </th>

                            </tr>

                        </thead>


                        {{-- TABLE BODY --}}

                        <tbody class="divide-y divide-gray-200 bg-white">

                            @forelse($schools as $school)

                                <tr class="transition hover:bg-green-50">

                                    {{-- SCHOOL ID --}}

                                    <td
                                        class="sticky left-0 z-10
                                            border-r border-gray-200
                                            bg-white px-4 py-4
                                            text-sm font-semibold text-gray-800"
                                    >
                                        {{ $school['school_id'] ?? '—' }}
                                    </td>


                                    {{-- SCHOOL NAME --}}

                                    <td
                                        class="sticky left-[110px] z-10
                                            min-w-[300px]
                                            border-r border-gray-200
                                            bg-white px-4 py-4
                                            text-sm font-medium text-gray-800"
                                    >
                                        {{ $school['school_name'] ?? '—' }}
                                    </td>


                                    {{-- SCHOOL YEAR --}}

                                    <td
                                        class="border-r border-gray-200
                                            px-4 py-4 text-sm
                                            text-gray-700"
                                    >
                                        {{ $school['school_year'] ?? '—' }}
                                    </td>


                                    {{-- KINDERGARTEN --}}

                                    <td class="border-r border-gray-200
                                            px-4 py-4 text-center text-sm">

                                        {{ collect($school['grades'] ?? [])
                                            ->firstWhere('name', 'Kindergarten')['count']
                                            ?? '—' }}

                                    </td>


                                    {{-- GRADE 1 --}}

                                    <td class="border-r border-gray-200
                                            px-4 py-4 text-center text-sm">

                                        {{ collect($school['grades'] ?? [])
                                            ->firstWhere('name', 'Grade 1')['count']
                                            ?? '—' }}

                                    </td>


                                    {{-- GRADE 2 --}}

                                    <td class="border-r border-gray-200
                                            px-4 py-4 text-center text-sm">

                                        {{ collect($school['grades'] ?? [])
                                            ->firstWhere('name', 'Grade 2')['count']
                                            ?? '—' }}

                                    </td>


                                    {{-- GRADE 3 --}}

                                    <td class="border-r border-gray-200
                                            px-4 py-4 text-center text-sm">

                                        {{ collect($school['grades'] ?? [])
                                            ->firstWhere('name', 'Grade 3')['count']
                                            ?? '—' }}

                                    </td>


                                    {{-- GRADE 4 --}}

                                    <td class="border-r border-gray-200
                                            px-4 py-4 text-center text-sm">

                                        {{ collect($school['grades'] ?? [])
                                            ->firstWhere('name', 'Grade 4')['count']
                                            ?? '—' }}

                                    </td>


                                    {{-- GRADE 5 --}}

                                    <td class="border-r border-gray-200
                                            px-4 py-4 text-center text-sm">

                                        {{ collect($school['grades'] ?? [])
                                            ->firstWhere('name', 'Grade 5')['count']
                                            ?? '—' }}

                                    </td>


                                    {{-- GRADE 6 --}}

                                    <td class="border-r border-gray-200
                                            px-4 py-4 text-center text-sm">

                                        {{ collect($school['grades'] ?? [])
                                            ->firstWhere('name', 'Grade 6')['count']
                                            ?? '—' }}

                                    </td>


                                    {{-- GRADE 7 --}}

                                    <td class="border-r border-gray-200
                                            px-4 py-4 text-center text-sm">

                                        {{ collect($school['grades'] ?? [])
                                            ->firstWhere('name', 'Grade 7')['count']
                                            ?? '—' }}

                                    </td>


                                    {{-- GRADE 8 --}}

                                    <td class="border-r border-gray-200
                                            px-4 py-4 text-center text-sm">

                                        {{ collect($school['grades'] ?? [])
                                            ->firstWhere('name', 'Grade 8')['count']
                                            ?? '—' }}

                                    </td>


                                    {{-- GRADE 9 --}}

                                    <td class="border-r border-gray-200
                                            px-4 py-4 text-center text-sm">

                                        {{ collect($school['grades'] ?? [])
                                            ->firstWhere('name', 'Grade 9')['count']
                                            ?? '—' }}

                                    </td>


                                    {{-- GRADE 10 --}}

                                    <td class="border-r border-gray-200
                                            px-4 py-4 text-center text-sm">

                                        {{ collect($school['grades'] ?? [])
                                            ->firstWhere('name', 'Grade 10')['count']
                                            ?? '—' }}

                                    </td>


                                    {{-- GRADE 11 --}}

                                    <td class="border-r border-gray-200
                                            px-4 py-4 text-center text-sm">

                                        {{ collect($school['grades'] ?? [])
                                            ->firstWhere('name', 'Grade 11')['count']
                                            ?? '—' }}

                                    </td>


                                    {{-- GRADE 12 --}}

                                    <td class="px-4 py-4 text-center text-sm">

                                        {{ collect($school['grades'] ?? [])
                                            ->firstWhere('name', 'Grade 12')['count']
                                            ?? '—' }}

                                    </td>


                                    {{-- ACTION --}}

                                    <td class="whitespace-nowrap px-4 py-4 text-center">

                                        <a
                                            href="#"
                                            class="inline-flex items-center
                                                rounded-md border border-green-700
                                                px-4 py-2 text-sm font-semibold
                                                text-green-700
                                                transition duration-200
                                                hover:bg-green-700
                                                hover:text-white"
                                        >
                                            View
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="18"
                                        class="px-6 py-10 text-center
                                            text-sm text-gray-500"
                                    >
                                        No enrollment records found.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- PAGINATION --}}
                <div class="border-t border-gray-200 px-6 py-4">

                    <div class="text-sm text-gray-500">
                        Showing 0 records
                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>