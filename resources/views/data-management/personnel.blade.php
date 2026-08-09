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

            {{-- IMPORT SECTION --}}
            <div class="mb-6 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">

                <div class="mb-4">

                    <h2 class="text-lg font-semibold text-gray-800">
                        Import Personnel Data
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Upload an Excel file containing personnel information.
                    </p>

                </div>


                <form
                    action="#"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    @csrf

                    <div class="flex flex-col gap-4 md:flex-row md:items-end">

                        <div class="flex-1">

                            <label
                                for="file"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Excel File
                            </label>

                            <input
                                type="file"
                                id="file"
                                name="file"
                                accept=".xlsx,.xls,.csv"
                                class="block w-full rounded-md border border-gray-300
                                       bg-white text-sm text-gray-700
                                       file:mr-4 file:border-0
                                       file:bg-green-700
                                       file:px-4 file:py-2
                                       file:text-sm file:font-semibold
                                       file:text-white
                                       hover:file:bg-green-800"
                            >

                        </div>


                        <button
                            type="submit"
                            class="rounded-md bg-green-700 px-5 py-2.5
                                   text-sm font-semibold text-white
                                   hover:bg-green-800"
                        >
                            Upload and Import
                        </button>

                    </div>

                </form>

            </div>


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