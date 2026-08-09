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

                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-5">

                    <h3 class="text-lg font-bold text-green-900">
                        Import Completed
                    </h3>

                    <div class="mt-3 grid gap-4 sm:grid-cols-3">

                        <div>
                            <p class="text-sm text-gray-500">
                                Successfully Imported
                            </p>

                            <p class="text-2xl font-bold text-green-700">
                                {{ session('import_result.imported') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Skipped Records
                            </p>

                            <p class="text-2xl font-bold text-yellow-600">
                                {{ session('import_result.skipped') }}
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


                {{-- Validation Errors --}}
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
                    action="{{ route('data-management.personnel.import') }}"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    @csrf


                    {{-- Excel File --}}
                    <div class="mb-6">

                        <label
                            for="file"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Personnel Information Excel File
                        </label>

                        <input
                            type="file"
                            id="file"
                            name="file"
                            accept=".xlsx,.xls"
                            required
                            class="mt-2 block w-full rounded-lg border
                                border-gray-300 bg-white text-sm
                                file:mr-4 file:rounded-md file:border-0
                                file:bg-green-700 file:px-4 file:py-2
                                file:font-semibold file:text-white
                                hover:file:bg-green-800"
                        >

                        <p class="mt-1 text-xs text-gray-500">
                            Accepted formats: .xlsx and .xls. Maximum file size: 10 MB.
                        </p>

                    </div>


                    <div class="flex items-center gap-3">

                        <button
                            type="submit"
                            class="rounded-lg bg-green-700 px-5 py-2.5
                                text-sm font-semibold text-white
                                shadow-sm transition
                                hover:bg-green-800"
                        >
                            Upload & Preview
                        </button>

                    </div>

                </form>

            </div>

            <br>

            {{-- RECORDS SECTION --}}
            <div class="rounded-lg border border-gray-200 bg-white shadow-sm">

                <div class="flex items-center justify-between border-b border-gray-200 p-6">

                    <div>

                        <h2 class="text-lg font-semibold text-gray-800">
                            Personnel Records
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            List of personnel records maintained in the system.
                        </p>

                    </div>

                    <a
                        href="#"
                        class="rounded-md border border-green-700 px-4 py-2
                               text-sm font-semibold text-green-700
                               hover:bg-green-50"
                    >
                        Download Template
                    </a>

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

                                <th class="px-6 py-3 text-left text-xs font-semibold
                                           uppercase tracking-wider text-gray-600">
                                    Employee No.
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold
                                           uppercase tracking-wider text-gray-600">
                                    Name
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold
                                           uppercase tracking-wider text-gray-600">
                                    Sex
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold
                                           uppercase tracking-wider text-gray-600">
                                    Birth Date
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold
                                           uppercase tracking-wider text-gray-600">
                                    Status
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold
                                           uppercase tracking-wider text-gray-600">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-200 bg-white">

                            <tr>

                                <td
                                    colspan="6"
                                    class="px-6 py-10 text-center text-sm text-gray-500"
                                >
                                    No personnel records found.

                                </td>

                            </tr>

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