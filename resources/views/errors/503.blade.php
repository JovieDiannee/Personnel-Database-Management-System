<x-app-layout>

    {{-- =====================================================
    CUSTOM ANIMATION
    ====================================================== --}}
    <style>
        @keyframes slow-spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .animate-slow-spin {
            animation: slow-spin 4s linear infinite;
            transform-origin: center;
        }
    </style>


    <div class="min-h-screen bg-gray-50 py-10">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


            {{-- =====================================================
            HEADER
            ====================================================== --}}

            <div
                class="relative mb-8 overflow-hidden rounded-2xl
                       bg-gradient-to-br from-green-950 via-green-900 to-green-800
                       px-6 py-8 text-white shadow-lg"
            >

                {{-- Decorative Circles --}}
                <div
                    class="absolute -right-16 -top-16
                           h-48 w-48 rounded-full
                           bg-green-700/30"
                ></div>

                <div
                    class="absolute -bottom-20 -left-16
                           h-48 w-48 rounded-full
                           bg-green-600/20"
                ></div>


                <div class="relative flex items-center gap-5">

                    {{-- ICON CONTAINER --}}
                    <div
                        class="flex h-20 w-20 shrink-0
                               items-center justify-center
                               rounded-2xl bg-white/10
                               ring-2 ring-green-300/40"
                    >

                        {{-- Animated Settings / Gear Icon --}}
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-10 w-10 animate-slow-spin text-green-200"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.592
                                   c.55 0 1.02.398 1.11.94l.213 1.281
                                   c.063.374.313.686.645.87.074.04.147.083.22.127
                                   .325.196.72.257 1.075.124l1.217-.456
                                   a1.125 1.125 0 011.37.49l1.296 2.244
                                   c.275.476.164 1.08-.26 1.43l-1.003.827
                                   c-.293.241-.438.613-.43.992a6.76 6.76 0 010 .255
                                   c-.008.378.137.75.43.991l1.004.827
                                   c.424.35.534.954.26 1.43l-1.298 2.244
                                   a1.125 1.125 0 01-1.369.49l-1.217-.456
                                   c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128
                                   c-.331.183-.581.495-.644.869l-.213 1.281
                                   c-.09.542-.56.94-1.11.94h-2.594
                                   c-.55 0-1.019-.398-1.11-.94l-.213-1.281
                                   c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127
                                   c-.325-.196-.72-.257-1.075-.124l-1.217.456
                                   a1.125 1.125 0 01-1.37-.49L3.53 15.37
                                   a1.125 1.125 0 01.26-1.43l1.003-.827
                                   c.293-.241.438-.613.43-.991a6.94 6.94 0 010-.255
                                   c.008-.379-.137-.751-.43-.992L3.79 10.05
                                   a1.125 1.125 0 01-.26-1.43l1.297-2.244
                                   a1.125 1.125 0 011.37-.49l1.217.456
                                   c.355.133.75.072 1.075-.124.072-.044.146-.086.22-.128
                                   .332-.183.582-.495.644-.869l.213-1.281z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                        </svg>

                    </div>


                    {{-- HEADER TEXT --}}
                    <div>

                        <p
                            class="text-sm font-semibold uppercase
                                   tracking-widest text-green-300"
                        >
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


            {{-- =====================================================
            MAIN CARD
            ====================================================== --}}

            <div
                class="overflow-hidden rounded-2xl bg-white
                       shadow-sm ring-1 ring-gray-100"
            >

                <div class="px-6 py-12 text-center sm:px-10">


                    {{-- ANIMATED ICON --}}
                    <div
                        class="mx-auto flex h-20 w-20
                               items-center justify-center
                               rounded-2xl bg-green-50
                               text-green-700
                               ring-1 ring-green-100"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-10 w-10 animate-slow-spin"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.7"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.592
                                   c.55 0 1.02.398 1.11.94l.213 1.281
                                   c.063.374.313.686.645.87.074.04.147.083.22.127
                                   .325.196.72.257 1.075.124l1.217-.456
                                   a1.125 1.125 0 011.37.49l1.296 2.244
                                   c.275.476.164 1.08-.26 1.43l-1.003.827
                                   c-.293.241-.438.613-.43.992a6.76 6.76 0 010 .255
                                   c-.008.378.137.75.43.991l1.004.827
                                   c.424.35.534.954.26 1.43l-1.298 2.244
                                   a1.125 1.125 0 01-1.369.49l-1.217-.456
                                   c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128
                                   c-.331.183-.581.495-.644.869l-.213 1.281
                                   c-.09.542-.56.94-1.11.94h-2.594
                                   c-.55 0-1.019-.398-1.11-.94l-.213-1.281
                                   c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127
                                   c-.325-.196-.72-.257-1.075-.124l-1.217.456
                                   a1.125 1.125 0 01-1.37-.49L3.53 15.37
                                   a1.125 1.125 0 01.26-1.43l1.003-.827
                                   c.293-.241.438-.613.43-.991a6.94 6.94 0 010-.255
                                   c.008-.379-.137-.751-.43-.992L3.79 10.05
                                   a1.125 1.125 0 01-.26-1.43l1.297-2.244
                                   a1.125 1.125 0 011.37-.49l1.217.456
                                   c.355.133.75.072 1.075-.124.072-.044.146-.086.22-.128
                                   .332-.183.582-.495.644-.869l.213-1.281z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                        </svg>

                    </div>


                    {{-- TITLE --}}
                    <h3 class="mt-6 text-2xl font-bold text-gray-800">
                        We'll Be Back Soon
                    </h3>


                    {{-- DESCRIPTION --}}
                    <p
                        class="mx-auto mt-3 max-w-xl
                               text-sm leading-6 text-gray-500"
                    >
                        Personnel Requests, Service Records, and Other Transactions
                        are currently under development. We are working to make
                        these features available as soon as possible.
                    </p>


                    {{-- NOTICE --}}
                    <div
                        class="mx-auto mt-7 flex max-w-xl
                               items-center justify-center gap-3
                               rounded-xl border border-amber-200
                               bg-amber-50 px-5 py-4 text-center"
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

                            <path
                                stroke-linecap="round"
                                d="M12 11v5"
                            />

                            <path
                                stroke-linecap="round"
                                d="M12 8.25h.01"
                            />
                        </svg>


                        <p class="text-sm leading-5 text-amber-800">
                            Need assistance? Please contact the Personnel Unit.
                        </p>

                    </div>


                    {{-- BACK BUTTON --}}
                    <a
                        href="{{ route('dashboard') }}"
                        class="mt-8 inline-flex
                               items-center justify-center gap-2
                               rounded-lg bg-green-700
                               px-6 py-3
                               text-sm font-semibold text-white
                               shadow-sm
                               transition
                               hover:bg-green-800
                               focus:outline-none
                               focus:ring-2
                               focus:ring-green-500
                               focus:ring-offset-2"
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


            {{-- =====================================================
            FOOTER CARD
            ====================================================== --}}

            <div
                class="mt-6 rounded-2xl
                       border border-green-100
                       bg-white px-6 py-5 shadow-sm"
            >

                <div
                    class="flex flex-col gap-4 text-center
                           sm:flex-row
                           sm:items-center
                           sm:justify-between
                           sm:text-left"
                >

                    <div>

                        <p class="font-bold text-gray-800">
                            Personnel Database Management System
                        </p>

                    </div>


                    <div class="text-xs text-gray-400">

                        <p>
                            For system assistance, please contact the Personnel Unit.
                        </p>

                        <p class="mt-1">
                            © {{ date('Y') }}
                            Department of Education - Leyte Division
                            • Personnel Unit
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>