<x-app-layout>

    <div class="min-h-screen bg-gray-50 py-8">

        <div class="mx-auto max-w-7xl px-6">


            {{-- PAGE HEADER --}}
            <div class="relative mb-6 overflow-hidden rounded-2xl
                        bg-gradient-to-br from-green-950 via-green-900 to-green-800
                        py-6 text-white shadow-lg">

                <div class="relative flex items-center justify-between px-6 py-5">

                    <div>

                        <h1 class="text-2xl font-bold tracking-tight">
                            School Information Records
                        </h1>

                        <p class="mt-1 text-sm text-green-100">
                            Maintain official school information and records
                            within the Schools Division.
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


                    <div class="mb-6">

                        <label
                            for="file"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            School Database Excel File
                        </label>


                        <input
                            type="file"
                            id="file"
                            name="file"
                            accept=".xlsx,.xls"
                            required
                            class="mt-2 block w-full rounded-lg
                                border border-gray-300 bg-white text-sm
                                file:mr-4 file:rounded-md file:border-0
                                file:bg-green-700 file:px-4 file:py-2
                                file:font-semibold file:text-white
                                hover:file:bg-green-800"
                        >


                        <p class="mt-1 text-xs text-gray-500">
                            Accepted formats: .xlsx and .xls.
                            Maximum file size: 10 MB.
                        </p>

                    </div>


                    <button
                        type="submit"
                        class="rounded-lg bg-green-700 px-5 py-2.5
                            text-sm font-semibold text-white
                            shadow-sm hover:bg-green-800"
                    >
                        Upload & Preview
                    </button>

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