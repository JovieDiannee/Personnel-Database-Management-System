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
                        School Database
                    </span>

                </nav>

            </div>


            @if(session('import_result'))

                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-5">

                    <h3 class="text-lg font-bold text-green-900">
                        School Import Completed
                    </h3>

                    <div class="mt-4 grid gap-4 sm:grid-cols-4">

                        <div>
                            <p class="text-sm text-gray-500">
                                New Records
                            </p>

                            <p class="text-2xl font-bold text-green-700">
                                {{ session('import_result.imported', 0) }}
                            </p>
                        </div>


                        <div>
                            <p class="text-sm text-gray-500">
                                Updated Records
                            </p>

                            <p class="text-2xl font-bold text-blue-700">
                                {{ session('import_result.updated', 0) }}
                            </p>
                        </div>


                        <div>
                            <p class="text-sm text-gray-500">
                                Skipped Records
                            </p>

                            <p class="text-2xl font-bold text-yellow-600">
                                {{ session('import_result.skipped', 0) }}
                            </p>
                        </div>


                        <div>
                            <p class="text-sm text-gray-500">
                                Errors
                            </p>

                            <p class="text-2xl font-bold text-red-600">
                                {{ count(session('import_result.errors', [])) }}
                            </p>
                        </div>

                    </div>

                </div>

            @endif


            {{-- IMPORT SECTION --}}
            <div class="mb-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                <div class="mb-6">

                    <h2 class="text-lg font-bold text-gray-900">
                        Import School Database
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Upload an Excel file containing official school information.
                    </p>

                </div>


                @if ($errors->any())

                    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4">

                        <div class="font-semibold text-red-800">
                            Unable to process the file.
                        </div>

                        <ul class="mt-2 list-disc pl-5 text-sm text-red-700">

                            @foreach ($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                <form
                    action="{{ route('data-management.schools.import') }}"
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
                                href="{{ route('data-management.school-database.download-template') }}"
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


            {{-- RECORDS SECTION --}}
            <div class="rounded-lg border border-gray-200 bg-white shadow-sm">

                {{-- HEADER --}}
                <div class="flex items-center justify-between border-b border-gray-200 p-6">

                    <div>

                        <h2 class="text-lg font-semibold text-gray-800">
                            School Database Records
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            List of official school information maintained in the system.
                        </p>

                    </div>

                    <div class="text-sm text-gray-500">

                        Total:
                        <span class="font-semibold text-green-700">
                            {{ $schools->total() }}
                        </span>

                    </div>

                </div>


                {{-- TABLE --}}
                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    School ID
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    School Name
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    District
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    Municipality
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    School Area
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    Legislative District
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    Sector
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    Curricular Offering
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-200 bg-white">

                            @forelse ($schools as $school)

                                <tr class="hover:bg-gray-50">

                                    {{-- SCHOOL ID --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-gray-900">
                                        {{ $school->school_id }}
                                    </td>


                                    {{-- SCHOOL NAME --}}
                                    <td class="px-6 py-4 text-sm text-gray-900">

                                        <div class="font-semibold">
                                            {{ $school->school_name }}
                                        </div>

                                    </td>


                                    {{-- DISTRICT --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                        {{ $school->school_district ?? '—' }}
                                    </td>


                                    {{-- MUNICIPALITY --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                        {{ $school->school_municipality ?? '—' }}
                                    </td>

                                     {{-- SCHOOL AREA --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                        {{ $school->school_area ?? '—' }}
                                    </td>

                                     {{-- LEGISLATIVE DISTRICT --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                        {{ $school->legislative_district ?? '—' }}
                                    </td>

                                    {{-- SECTOR --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                        {{ $school->school_sector ?? '—' }}
                                    </td>

                                    {{-- SCHOOL CURRICULAR OFFERING --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                        {{ $school->school_curricular_offering ?? '—' }}
                                    </td>

                                    {{-- ACTION --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-right">

                                        <a
                                            href="#"
                                            class="inline-flex items-center rounded-md
                                                border border-green-700
                                                px-3 py-2 text-sm font-semibold
                                                text-green-700
                                                hover:bg-green-50"
                                        >
                                            View
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="8"
                                        class="px-6 py-10 text-center text-sm text-gray-500"
                                    >
                                        No school records found.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- PAGINATION --}}
                @if ($schools->hasPages())

                    <div class="border-t border-gray-200 px-6 py-4">

                        {{ $schools->links() }}

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>