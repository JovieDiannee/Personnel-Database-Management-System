<x-app-layout>

    <div class="min-h-screen bg-gray-50 py-8">

        <div class="mx-auto max-w-7xl px-6">

            {{-- PAGE HEADER --}}
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-950 via-green-900 to-green-800 py-6 text-white shadow-lg">

                <div class="flex items-center justify-between px-6 py-5">

                    <div>
                        <h1 class="text-2xl font-bold text-white">
                            Personnel Information
                        </h1>

                        <p class="mt-1 text-sm text-green-100">
                            Maintain and manage official personnel information and records.
                        </p>
                    </div>

                    <a
                        href="{{ route('data-management') }}"
                        class="rounded-md border border-white/30 bg-white/10 px-4 py-2
                            text-sm font-semibold text-white
                            backdrop-blur-sm
                            transition duration-200
                            hover:bg-white hover:text-green-800"
                    >
                        ← Back to Data Management
                    </a>

                </div>

            </div>
            <br>

            @if(session('import_result'))

                @php
                    $result = session('import_result');

                    $imported = $result['imported'] ?? 0;
                    $updated = $result['updated'] ?? 0;
                    $skipped = $result['skipped'] ?? 0;
                    $errors = $result['errors'] ?? [];
                @endphp


                {{-- SUCCESS / UPDATE NOTIFICATION --}}
                @if($imported > 0 || $updated > 0)

                    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-5">

                        <div class="flex items-start gap-3">

                            <div class="mt-0.5 flex h-8 w-8 items-center justify-center
                                        rounded-full bg-green-600 text-white">

                                ✓

                            </div>

                            <div class="flex-1">

                                <h3 class="text-lg font-bold text-green-900">
                                    Import Completed Successfully
                                </h3>

                                <p class="mt-1 text-sm text-green-800">
                                    The personnel information has been processed successfully.
                                </p>

                                <div class="mt-4 grid gap-4 sm:grid-cols-3">

                                    {{-- IMPORTED --}}
                                    <div class="rounded-lg bg-white p-4 ring-1 ring-green-100">

                                        <p class="text-sm text-gray-500">
                                            New Records
                                        </p>

                                        <p class="mt-1 text-2xl font-bold text-green-700">
                                            {{ $imported }}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            Newly added personnel
                                        </p>

                                    </div>


                                    {{-- UPDATED --}}
                                    <div class="rounded-lg bg-white p-4 ring-1 ring-green-100">

                                        <p class="text-sm text-gray-500">
                                            Updated Records
                                        </p>

                                        <p class="mt-1 text-2xl font-bold text-blue-700">
                                            {{ $updated }}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            Existing personnel updated
                                        </p>

                                    </div>


                                    {{-- SKIPPED --}}
                                    <div class="rounded-lg bg-white p-4 ring-1 ring-green-100">

                                        <p class="text-sm text-gray-500">
                                            Skipped Records
                                        </p>

                                        <p class="mt-1 text-2xl font-bold text-yellow-600">
                                            {{ $skipped }}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            Records not processed
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                @endif


                {{-- ERRORS --}}
                @if(count($errors) > 0)

                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-5">

                        <div class="flex items-start gap-3">

                            <div class="mt-0.5 flex h-8 w-8 items-center justify-center
                                        rounded-full bg-red-600 text-white">

                                !

                            </div>

                            <div class="flex-1">

                                <h3 class="text-lg font-bold text-red-900">
                                    Import Errors
                                </h3>

                                <p class="mt-1 text-sm text-red-700">
                                    Some records could not be processed. Please review the
                                    details below.
                                </p>


                                <div class="mt-4 overflow-x-auto rounded-lg border border-red-200 bg-white">

                                    <table class="min-w-full divide-y divide-red-200">

                                        <thead class="bg-red-50">

                                            <tr>

                                                <th class="px-4 py-3 text-left text-xs font-semibold
                                                        uppercase tracking-wider text-red-800">
                                                    Excel Row
                                                </th>

                                                <th class="px-4 py-3 text-left text-xs font-semibold
                                                        uppercase tracking-wider text-red-800">
                                                    Error
                                                </th>

                                            </tr>

                                        </thead>


                                        <tbody class="divide-y divide-gray-200">

                                            @foreach($errors as $error)

                                                <tr>

                                                    <td class="whitespace-nowrap px-4 py-3 text-sm
                                                            font-semibold text-gray-700">

                                                        {{ $error['row'] ?? '-' }}

                                                    </td>

                                                    <td class="px-4 py-3 text-sm text-red-700">

                                                        {{ $error['message'] ?? 'Unknown error.' }}

                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>

                @endif

            @endif


            {{-- UPLOAD FILE SECTION --}}

            <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                <div class="mb-6">
                    <h2 class="text-lg font-bold text-gray-900">
                        Import Personnel Information
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Upload an Excel file containing official personnel information
                        for validation and preview.
                    </p>
                </div>


                <form
                    action="{{ route('data-management.personnel.import') }}"
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
                                href="{{ route('data-management.personnel-basic-information.download-template') }}"
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

            <br>
            


            {{-- RECORDS SECTION --}}
            <div class="rounded-lg border border-gray-200 bg-white shadow-sm">

                {{-- SECTION HEADER --}}
                <div class="flex items-center justify-between border-b border-gray-200 p-6">

                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">
                            Personnel Records
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            List of personnel records maintained in the system.
                        </p>
                    </div>
                </div>


                {{-- SEARCH --}}
                <div class="border-b border-gray-200 p-6">

                    <div class="max-w-md">

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
                            placeholder="Search by name or employee number..."
                            class="w-full rounded-md border-gray-300
                                text-sm shadow-sm
                                focus:border-green-600
                                focus:ring-green-600"
                        >

                    </div>

                </div>


                {{-- TABLE --}}
                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                {{-- Employee No. --}}
                                <th class="px-6 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    Employee No.
                                </th>

                                {{-- Name --}}
                                <th class="px-6 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    Name
                                </th>

                                {{-- Sex --}}
                                <th class="px-6 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    Sex
                                </th>

                                {{-- Birth Date --}}
                                <th class="px-6 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    Birth Date
                                </th>

                                {{-- Mobile Number --}}
                                <th class="px-6 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    Mobile Number
                                </th>

                                {{-- User Role --}}
                                <th class="px-6 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    User Role
                                </th>

                                {{-- Status --}}
                                <th class="px-6 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    Status
                                </th>

                                {{-- Date Created --}}
                                <th class="px-6 py-3 text-left text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    Date Created
                                </th>

                                {{-- Action --}}
                                <th class="px-6 py-3 text-right text-xs font-semibold
                                        uppercase tracking-wider text-gray-600">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-200 bg-white">

                            @forelse ($personnel as $person)

                                <tr class="hover:bg-gray-50">

                                    {{-- Employee No. --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $person->issuedId?->employee_id ?? '—' }}
                                    </td>


                                    {{-- Name --}}
                                    <td class="whitespace-nowrap px-6 py-4">

                                        <div class="text-sm font-semibold text-gray-900">

                                            {{ $person->last_name }},
                                            {{ $person->first_name }}

                                            @if ($person->middle_name)
                                                {{ strtoupper(substr($person->middle_name, 0, 1)) }}.
                                            @endif

                                            @if ($person->extension_name)
                                                {{ $person->extension_name }}
                                            @endif

                                        </div>

                                        <div class="text-xs text-gray-500">
                                            {{ $person->users->email ?? '' }}
                                        </div>

                                    </td>


                                    {{-- Sex --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                        {{ $person->sex ?? '—' }}
                                    </td>


                                    {{-- Birth Date --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">

                                        @if ($person->birth_date)
                                            {{ \Carbon\Carbon::parse($person->birth_date)->format('d/m/Y') }}
                                        @else
                                            —
                                        @endif

                                    </td>

                                    {{-- mobile_number --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                        {{ $person->mobile_number ?? '—' }}
                                    </td>

                                    {{-- user_role --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                        {{ $person->user->role ?? '—' }}
                                    </td>


                                    {{-- Status --}}
                                    <td class="whitespace-nowrap px-6 py-4">

                                        <span
                                            class="inline-flex rounded-full bg-green-100
                                                px-3 py-1 text-xs font-semibold
                                                text-green-800"
                                        >
                                            Active
                                        </span>

                                    </td>


                                    {{-- Date Created --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">

                                        {{ $person->created_at
                                            ? $person->created_at->format('d/m/Y')
                                            : '—'
                                        }}

                                    </td>


                                    {{-- Action --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-right">

                                        <a
                                            href="{{ route('data-management.personnel.view.individual.records', $person->id) }}"
                                            class="inline-flex items-center rounded-md
                                                bg-green-700 px-4 py-2
                                                text-sm font-semibold text-white
                                                transition hover:bg-green-800"
                                        >
                                            View Profile
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="7"
                                        class="px-6 py-10 text-center text-sm text-gray-500"
                                    >
                                        No personnel records found.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- PAGINATION --}}
                <div class="border-t border-gray-200 px-6 py-4">

                    <div class="flex items-center justify-between">

                        <div class="text-sm text-gray-500">
                            Showing
                            <span class="font-semibold text-gray-700">
                                {{ $personnel->count() }}
                            </span>
                            records
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


</x-app-layout>