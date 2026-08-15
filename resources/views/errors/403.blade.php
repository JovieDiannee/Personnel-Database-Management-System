<x-app-layout>

    <div class="min-h-screen bg-gray-50 py-10">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- =====================================================
                ERROR HEADER
            ====================================================== --}}
            <div
                class="relative mb-8 overflow-hidden rounded-2xl
                       bg-gradient-to-br from-green-950 via-green-900 to-green-800
                       px-6 py-8 text-white shadow-lg"
            >

                {{-- Decorative Background --}}
                <div
                    class="absolute -right-16 -top-16 h-48 w-48 rounded-full
                           bg-green-700/30"
                ></div>

                <div
                    class="absolute -bottom-20 -left-16 h-48 w-48 rounded-full
                           bg-green-600/20"
                ></div>

                <div class="relative flex items-center gap-5">

                    {{-- Shield Icon --}}
                    <div
                        class="flex h-20 w-20 shrink-0 items-center justify-center
                               rounded-2xl bg-white/10
                               ring-2 ring-green-300/40"
                    >

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
                                d="M12 3l7.5 3v5.25c0 4.75-3.2 8.65-7.5 9.75-4.3-1.1-7.5-5-7.5-9.75V6L12 3z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9.75 11.25a2.25 2.25 0 114.5 0v1.5a2.25 2.25 0 01-4.5 0v-1.5z"
                            />
                        </svg>

                    </div>


                    {{-- Header Information --}}
                    <div>

                        <p class="text-sm font-semibold uppercase tracking-widest text-green-300">
                            Access Restricted
                        </p>

                        <h1 class="mt-1 text-2xl font-bold sm:text-3xl">
                            Unauthorized Action
                        </h1>

                        <p class="mt-1 text-sm text-green-100">
                            You do not have sufficient permission to access this resource.
                        </p>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                403 CONTENT
            ====================================================== --}}
            <div
                class="overflow-hidden rounded-2xl bg-white
                       shadow-sm ring-1 ring-gray-100"
            >

                {{-- Section Header --}}
                <div
                    class="border-b border-green-100
                           bg-green-50 px-6 py-4"
                >

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 items-center justify-center
                                   rounded-lg bg-green-700 text-white"
                        >

                            {{-- Lock Icon --}}
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
                                    d="M16.5 10.5V6.75a4.5 4.5 0 00-9 0v3.75"
                                />

                                <rect
                                    width="15"
                                    height="10"
                                    x="4.5"
                                    y="10.5"
                                    rx="2.25"
                                />
                            </svg>

                        </div>

                        <div>

                            <h2 class="font-bold text-gray-800">
                                Permission Required
                            </h2>

                            <p class="text-sm text-gray-500">
                                This resource is restricted based on your account role.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Main Content --}}
                <div class="px-6 py-10 sm:px-10 sm:py-14">

                    <div class="mx-auto max-w-3xl">

                        <div class="grid gap-8 md:grid-cols-2 md:items-center">

                            {{-- =================================================
                                LEFT - 403 DISPLAY
                            ================================================== --}}
                            <div class="text-center md:text-left">

                                <div
                                    class="text-7xl font-black tracking-tight
                                           text-green-900 sm:text-8xl"
                                >
                                    403
                                </div>

                                <div
                                    class="mt-3 h-1 w-20 rounded-full
                                           bg-green-600
                                           mx-auto md:mx-0"
                                ></div>

                                <h3
                                    class="mt-5 text-xl font-bold text-gray-800"
                                >
                                    Unauthorized Action
                                </h3>

                                <p
                                    class="mt-3 text-sm leading-6 text-gray-500"
                                >
                                    You are currently signed in, but your account
                                    does not have permission to perform this action
                                    or access this section of the Personnel Database
                                    Management System.
                                </p>

                            </div>


                            {{-- =================================================
                                RIGHT - ACCESS INFORMATION
                            ================================================== --}}
                            <div>

                                {{-- Current Account --}}
                                <div
                                    class="rounded-xl border border-green-100
                                           bg-green-50 p-5"
                                >

                                    <div class="flex items-start gap-4">

                                        <div
                                            class="flex h-11 w-11 shrink-0
                                                   items-center justify-center
                                                   rounded-lg bg-green-700
                                                   text-white"
                                        >

                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-6 w-6"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"
                                                />

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M4.5 20.25a8.25 8.25 0 0115 0"
                                                />
                                            </svg>

                                        </div>


                                        <div class="min-w-0">

                                            <p
                                                class="text-xs font-semibold
                                                       uppercase tracking-wider
                                                       text-green-700"
                                            >
                                                Current Account
                                            </p>

                                            <p
                                                class="mt-1 truncate
                                                       font-bold text-gray-800"
                                            >
                                                {{ Auth::user()->name }}
                                            </p>

                                            <p class="text-sm text-gray-500">
                                                {{ Auth::user()->email }}
                                            </p>

                                        </div>

                                    </div>

                                </div>


                                {{-- Role --}}
                                <div
                                    class="mt-4 flex items-center justify-between
                                           rounded-xl border border-gray-100
                                           bg-white px-5 py-4
                                           shadow-sm"
                                >

                                    <div>

                                        <p
                                            class="text-xs font-semibold
                                                   uppercase tracking-wider
                                                   text-gray-400"
                                        >
                                            Your Role
                                        </p>

                                        <p class="mt-1 font-bold text-gray-800">

                                            {{ ucwords(str_replace('_', ' ', Auth::user()->role)) }}

                                        </p>

                                    </div>


                                    <div
                                        class="flex h-10 w-10 items-center
                                               justify-center rounded-lg
                                               bg-green-100 text-green-700"
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
                                                d="M9 12.75L11.25 15 15 9.75"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M12 3l7.5 3v5.25c0 4.75-3.2 8.65-7.5 9.75-4.3-1.1-7.5-5-7.5-9.75V6L12 3z"
                                            />
                                        </svg>

                                    </div>

                                </div>


                                {{-- Information --}}
                                <div
                                    class="mt-4 flex gap-3 rounded-xl
                                           border border-green-100
                                           bg-green-50/70 p-4"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="mt-0.5 h-5 w-5 shrink-0 text-green-700"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="9"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            d="M12 11v5"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            d="M12 8.25h.01"
                                        />
                                    </svg>


                                    <p
                                        class="text-sm leading-5 text-gray-600"
                                    >
                                        If you believe you should have access to
                                        this resource, please contact the system
                                        administrator or the Personnel Unit.
                                    </p>

                                </div>


                                {{-- Back Button --}}
                                <div class="mt-6">

                                    <a
                                        href="{{ route('dashboard') }}"
                                        class="inline-flex w-full items-center
                                               justify-center gap-2 rounded-lg
                                               bg-green-700 px-5 py-3
                                               text-sm font-semibold text-white
                                               shadow-sm transition
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

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                GOVERNMENT / SYSTEM INFORMATION
            ====================================================== --}}
            <div class="mt-6">

                <div
                    class="rounded-2xl border border-green-100
                           bg-white px-6 py-5 shadow-sm"
                >

                    <div
                        class="flex flex-col gap-4
                               text-center sm:flex-row
                               sm:items-center sm:justify-between
                               sm:text-left py-4"
                    >

                        <div class="flex items-center justify-center gap-3 sm:justify-start">

                            <div
                                class="flex h-10 w-10 items-center
                                       justify-center rounded-lg
                                       bg-green-100 text-green-700"
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
                                        d="M3 21h18M5.25 21V8.25L12 3l6.75 5.25V21M9 21v-6h6v6"
                                    />
                                </svg>

                            </div>

                            <div>

                                <p class="font-bold text-gray-800">
                                    Personnel Database Management System
                                </p>

                            </div>

                        </div>


                        <div class="text-xs text-gray-400">

                            <p>
                                For system assistance, please contact the
                                Personnel Unit.
                            </p>

                            <p class="mt-1">
                                © {{ date('Y') }} Department of Education -
                                Leyte Division • Personnel Unit | @joviegayo
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>