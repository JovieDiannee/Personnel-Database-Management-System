<x-app-layout>

    <div class="min-h-screen min-w-0 bg-gray-50 py-4 sm:py-8">

        <div class="mx-auto w-full min-w-0 max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- PAGE HEADER --}}
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-950 via-green-900 to-green-800 text-white shadow-lg">

                <div class="flex min-w-0 flex-col gap-5 px-4 py-6 sm:px-6 sm:py-8 md:flex-row md:items-center md:justify-between">

                    <div>
                        <h1 class="break-words text-xl font-bold text-white sm:text-2xl">
                            Data Management
                        </h1>

                        <p class="mt-1 text-sm text-green-100">
                            Manage and import personnel, school, plantilla, and enrollment data.
                        </p>
                    </div>

                    <a
                        href="{{ route('dashboard') }}"
                        class="inline-flex min-h-11 w-full shrink-0 items-center justify-center rounded-md border border-white/30 bg-white/10 px-4 py-2
                            text-sm font-semibold text-white
                            backdrop-blur-sm
                            transition duration-200
                            hover:bg-white hover:text-green-800 md:w-auto focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-green-900"
                    >
                        ← Back to Home
                    </a>

                </div>

            </div>
            <div class="h-4 sm:h-6" aria-hidden="true"></div>

            {{-- Personnel Data --}}
            <div class="mb-8">

                <div class="grid min-w-0 grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3 sm:gap-6">

                    {{-- Personnel Information --}}
                    <div class="flex min-w-0 flex-col rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 sm:p-6">
                        <h3 class="break-words text-base font-semibold text-green-900">
                            Personnel Information
                        </h3>

                        <p class="mt-2 break-words text-sm leading-6 text-gray-500">
                            Maintain and manage official personnel information and records.
                        </p>

                        <div class="mt-auto pt-5">
                            <a
                                href="{{ route('data-management.personnel') }}"
                                class="inline-flex min-h-11 w-full items-center justify-center rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-800 sm:w-auto focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2"
                            >
                                Manage Data
                            </a>
                        </div>
                    </div>

                    {{-- Employment Status Records --}}
                    <div class="flex min-w-0 flex-col rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 sm:p-6">
                        <h3 class="break-words text-base font-semibold text-green-900">
                            Employment Status Records
                        </h3>

                        <p class="mt-2 break-words text-sm leading-6 text-gray-500">
                            Manage records of personnel employment and corresponding personnel assignments.
                        </p>

                        <div class="mt-auto pt-5">
                            <a
                                href="{{ route('data-management.employment-status') }}"
                                class="inline-flex min-h-11 w-full items-center justify-center rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-800 sm:w-auto focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2"
                            >
                                Manage Data
                            </a>
                        </div>
                    </div>

                    {{-- Medical Allowance Records --}}
                    <div class="flex min-w-0 flex-col rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 sm:p-6">
                        <h3 class="break-words text-base font-semibold text-green-900">
                            Medical Allowance Records
                        </h3>

                        <p class="mt-2 break-words text-sm leading-6 text-gray-500">
                            Manage and monitor personnel records related to medical allowance benefits.
                        </p>

                        <div class="mt-auto pt-5">
                            <a
                                href="{{ route('data-management.medical-allowance') }}"
                                class="inline-flex min-h-11 w-full items-center justify-center rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-800 sm:w-auto focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2"
                            >
                                Manage Data
                            </a>
                        </div>
                    </div>

                    @if(auth()->user()->role === 'super_admin')
                    
                        {{-- Plantilla Position Records --}}
                        <div class="flex min-w-0 flex-col rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 sm:p-6">
                            <h3 class="break-words text-base font-semibold text-green-900">
                                Plantilla Position Records
                            </h3>

                            <p class="mt-2 break-words text-sm leading-6 text-gray-500">
                                Manage records of authorized plantilla item positions.
                            </p>

                            <div class="mt-auto pt-5">
                                <a
                                    href="{{ route('data-management.plantilla') }}"
                                    class="inline-flex min-h-11 w-full items-center justify-center rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-800 sm:w-auto focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2"
                                >
                                    Manage Data
                                </a>
                            </div>
                        </div>


                        {{-- School Database Information Records --}}
                        <div class="flex min-w-0 flex-col rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 sm:p-6">
                            <h3 class="break-words text-base font-semibold text-green-900">
                                School Database Information Records
                            </h3>

                            <p class="mt-2 break-words text-sm leading-6 text-gray-500">
                                Maintain official information and records of schools within the Division.
                            </p>

                            <div class="mt-auto pt-5">
                                <a
                                    href="{{ route('data-management.schools') }}"
                                    class="inline-flex min-h-11 w-full items-center justify-center rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-800 sm:w-auto focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2"
                                >
                                    Manage Data
                                </a>
                            </div>
                        </div>


                        {{-- Enrollment Records --}}
                        <div class="flex min-w-0 flex-col rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 sm:p-6">
                            <h3 class="break-words text-base font-semibold text-green-900">
                                Enrollment Records
                            </h3>

                            <p class="mt-2 break-words text-sm leading-6 text-gray-500">
                                Maintain and manage enrollment data for reporting and personnel-related information management.
                            </p>

                            <div class="mt-auto pt-5">
                                <a
                                    href="{{ route('data-management.enrollment') }}"
                                    class="inline-flex min-h-11 w-full items-center justify-center rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-800 sm:w-auto focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2"
                                >
                                    Manage Data
                                </a>
                            </div>
                        </div>

                        {{-- Reports Management --}}
                        <div class="flex min-w-0 flex-col rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 sm:p-6">
                            <h3 class="break-words text-base font-semibold text-green-900">
                                Reports Management
                            </h3>

                            <p class="mt-2 break-words text-sm leading-6 text-gray-500">
                                Create reports, set deadlines, assign school sectors,
                                and monitor school submissions and validation status.
                            </p>

                            <div class="mt-auto pt-5">
                                <a
                                    href="{{ route('data-management.reports') }}"
                                    class="inline-flex min-h-11 w-full items-center justify-center
                                        rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold
                                        text-white transition hover:bg-green-800 sm:w-auto
                                        focus:outline-none focus:ring-2 focus:ring-green-600
                                        focus:ring-offset-2"
                                >
                                    Manage Reports
                                </a>
                            </div>
                        </div>
                    @endif

                </div>

            </div>

        </div>

    </div>

</x-app-layout>