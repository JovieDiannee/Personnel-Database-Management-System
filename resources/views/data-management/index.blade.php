<x-app-layout>

    <div class="min-h-screen bg-gray-50 py-8">

        <div class="mx-auto max-w-7xl px-6">

            <div class="mb-8">
                <h1 class="text-2xl font-bold text-green-900">
                    Data Management
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Manage and import personnel, school, plantilla, and enrollment data.
                </p>
            </div>

            {{-- Personnel Data --}}
            <div class="mb-8">

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">

                    {{-- Personnel Information --}}
                    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                        <h3 class="text-base font-semibold text-green-900">
                            Personnel Information
                        </h3>

                        <p class="mt-2 text-sm text-gray-500">
                            Maintain and manage official personnel information and records.
                        </p>

                        <div class="mt-5">
                            <a
                                href="#"
                                class="inline-flex items-center rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-800"
                            >
                                Manage Data
                            </a>
                        </div>
                    </div>


                    {{-- Plantilla Position Records --}}
                    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                        <h3 class="text-base font-semibold text-green-900">
                            Plantilla Position Records
                        </h3>

                        <p class="mt-2 text-sm text-gray-500">
                            Manage records of authorized plantilla positions and corresponding personnel assignments.
                        </p>

                        <div class="mt-5">
                            <a
                                href="#"
                                class="inline-flex items-center rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-800"
                            >
                                Manage Data
                            </a>
                        </div>
                    </div>


                    {{-- School Database Information Records --}}
                    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                        <h3 class="text-base font-semibold text-green-900">
                            School Database Information Records
                        </h3>

                        <p class="mt-2 text-sm text-gray-500">
                            Maintain official information and records of schools within the Division.
                        </p>

                        <div class="mt-5">
                            <a
                                href="#"
                                class="inline-flex items-center rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-800"
                            >
                                Manage Data
                            </a>
                        </div>
                    </div>


                    {{-- Medical Allowance Records --}}
                    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                        <h3 class="text-base font-semibold text-green-900">
                            Medical Allowance Records
                        </h3>

                        <p class="mt-2 text-sm text-gray-500">
                            Manage and monitor personnel records related to medical allowance benefits.
                        </p>

                        <div class="mt-5">
                            <a
                                href="#"
                                class="inline-flex items-center rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-800"
                            >
                                Manage Data
                            </a>
                        </div>
                    </div>


                    {{-- Enrollment Records --}}
                    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                        <h3 class="text-base font-semibold text-green-900">
                            Enrollment Records
                        </h3>

                        <p class="mt-2 text-sm text-gray-500">
                            Maintain and manage enrollment data for reporting and personnel-related information management.
                        </p>

                        <div class="mt-5">
                            <a
                                href="#"
                                class="inline-flex items-center rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-800"
                            >
                                Manage Data
                            </a>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>