<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- HEADER --}}
            <div
                class="relative mb-8 overflow-hidden rounded-2xl
                       bg-gradient-to-br from-green-950 via-green-900 to-green-800
                       px-6 py-8 text-white shadow-lg"
            >
                <div class="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-green-700/30"></div>
                <div class="absolute -bottom-20 -left-16 h-48 w-48 rounded-full bg-green-600/20"></div>

                <div class="relative flex items-center gap-5">
                    <div
                        class="flex h-20 w-20 shrink-0 items-center justify-center
                               rounded-2xl bg-white/10 ring-2 ring-green-300/40"
                    >
                        {{-- Wrench icon --}}
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-10 w-10 text-green-200"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M14.7 6.3a4.5 4.5 0 01-5.99 5.99L3 18l3 3 5.71-5.71A4.5 4.5 0 0017.7 9.3L15 12l-3-3 2.7-2.7z"
                            />
                        </svg>
                    </div>

                    <div>
                        <p class="text-sm font-semibold uppercase tracking-widest text-green-300">
                            System Update
                        </p>

                        <h1 class="mt-1 text-2xl font-bold sm:text-3xl">
                            Feature Under Maintenance
                        </h1>

                        <p class="mt-1 text-sm text-green-100">
                            This HR Transactions feature is currently being improved.
                        </p>
                    </div>
                </div>
            </div>

           {{-- MAIN CARD --}}
            <div
                class="overflow-hidden rounded-2xl bg-white
                    shadow-sm ring-1 ring-gray-100"
            >
                

                {{-- Center content --}}
                <div class="px-6 py-12 text-center sm:px-10">
                    <div
                        class="mx-auto flex h-20 w-20 items-center justify-center
                            rounded-2xl bg-green-50 text-green-700
                            ring-1 ring-green-100"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-10 w-10"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.7"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M14.7 6.3a4.5 4.5 0 01-5.99 5.99L3 18l3 3
                                5.71-5.71A4.5 4.5 0 0017.7 9.3L15 12l-3-3
                                2.7-2.7z"
                            />
                        </svg>
                    </div>

                    <h3 class="mt-6 text-2xl font-bold text-gray-800">
                        We’ll Be Back Soon
                    </h3>

                    <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-gray-500">
                        Personnel Requests, Service Records, and Other Transactions are
                        currently under development. We are working to make these features
                        available as soon as possible.
                    </p>

                    <div
                        class="mx-auto mt-7 flex max-w-xl items-center justify-center gap-3
                            rounded-xl border border-amber-200 bg-amber-50
                            px-5 py-4 text-center"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 shrink-0 text-amber-700"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <circle cx="12" cy="12" r="9" />
                            <path stroke-linecap="round" d="M12 11v5" />
                            <path stroke-linecap="round" d="M12 8.25h.01" />
                        </svg>

                        <p class="text-sm leading-5 text-amber-800">
                            Need assistance? Please contact the Personnel Unit.
                        </p>
                    </div>

                    <a
                        href="{{ route('dashboard') }}"
                        class="mt-8 inline-flex items-center justify-center gap-2
                            rounded-lg bg-green-700 px-6 py-3
                            text-sm font-semibold text-white shadow-sm
                            transition hover:bg-green-800
                            focus:outline-none focus:ring-2
                            focus:ring-green-500 focus:ring-offset-2"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"
                            />
                        </svg>

                        Back to Dashboard
                    </a>
                </div>
            </div>

            {{-- FOOTER CARD --}}
            <div class="mt-6 rounded-2xl border border-green-100 bg-white px-6 py-5 shadow-sm">
                <div class="flex flex-col gap-4 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
                    <div>
                        <p class="font-bold text-gray-800">
                            Personnel Database Management System
                        </p>
                    </div>

                    <div class="text-xs text-gray-400">
                        <p>For system assistance, please contact the Personnel Unit.</p>
                        <p class="mt-1">
                            © {{ date('Y') }} Department of Education - Leyte Division • Personnel Unit
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>