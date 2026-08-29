{{-- =====================================================
    PDMS LEFT SIDEBAR
====================================================== --}}

<aside
    class="fixed inset-y-0 left-0 z-50
           flex flex-col
           bg-white
           shadow-xl
           transition-all duration-300"

    :class="sidebarOpen ? 'w-72' : 'w-20'"
>


        {{-- =================================================
            SIDEBAR HEADER
        ================================================== --}}

        <div
            class="flex h-16 shrink-0 items-center
                bg-gradient-to-br from-green-950 via-green-900 to-green-800
                px-3"
        >

            {{-- ================================================
                LOGO + SYSTEM TITLE
            ================================================= --}}

            <div
                x-show="sidebarOpen"
                x-transition
                class="flex min-w-0 flex-1 items-center gap-3"
            >

                {{-- PDMS LOGO --}}
                <div
                    class="flex h-11 w-11 shrink-0
                        items-center justify-center
                        overflow-hidden
                        rounded-xl
                        bg-white/95
                        shadow-sm"
                >
                    <img
                        src="{{ asset('images/pdms-favicon.png') }}"
                        alt="PDMS Logo"
                        class="h-10 w-10 object-contain"
                    >
                </div>


                {{-- SYSTEM TITLE --}}
                <div class="min-w-0">

                    <h1
                        class="truncate text-[15px]
                            font-bold leading-tight
                            text-white"
                    >
                        Personnel Database
                    </h1>

                    <p
                        class="mt-0.5 truncate text-[11px]
                            font-medium text-green-100"
                    >
                        Management System
                    </p>

                </div>

            </div>


            {{-- ================================================
                SIDEBAR TOGGLE BUTTON
            ================================================= --}}

            <button
                type="button"
                @click="sidebarOpen = !sidebarOpen"
                class="flex h-9 w-9 shrink-0
                    items-center justify-center
                    rounded-lg
                    bg-white/10
                    text-white
                    backdrop-blur-sm
                    transition-all duration-200
                    hover:bg-white/20
                    hover:text-white
                    focus:outline-none
                    focus:ring-2
                    focus:ring-green-300/70"
                aria-label="Toggle Sidebar"
            >

                {{-- HAMBURGER --}}
                <svg
                    x-show="!sidebarOpen"
                    x-transition
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                </svg>


                {{-- CLOSE --}}
                <svg
                    x-show="sidebarOpen"
                    x-transition
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>

            </button>

        </div>

    {{-- =====================================================
        NAVIGATION
    ====================================================== --}}

    <nav
        class="flex-1 overflow-y-auto px-3 py-5"

        x-data="{
            dataManagementOpen:
                {{ request()->routeIs('data-management*') ? 'true' : 'false' }},

            hrTransactionsOpen:
                {{ request()->routeIs('hr-transactions*') ? 'true' : 'false' }}
        }"
    >


        {{-- =================================================
            MAIN MENU LABEL
        ================================================== --}}

        <div
            x-show="sidebarOpen"
            x-transition
            class="mb-3 px-3"
        >

            <p
                class="text-[10px] font-bold uppercase
                       tracking-widest text-gray-400"
            >
                Main Menu
            </p>

        </div>


        {{-- =================================================
            DASHBOARD
        ================================================== --}}

        <a
            href="{{ route('dashboard') }}"
            class="group mb-2 flex items-center gap-3
                   rounded-xl px-2 py-3
                   text-sm font-semibold
                   transition-all duration-200

                   {{ request()->routeIs('dashboard')
                        ? 'bg-green-700 text-white shadow-md'
                        : 'text-gray-600 hover:bg-green-50 hover:text-green-800'
                   }}"
        >

            {{-- ICON --}}
            <span
                class="flex h-10 w-10 shrink-0
                       items-center justify-center
                       rounded-lg

                       {{ request()->routeIs('dashboard')
                            ? 'bg-white/15 text-white'
                            : 'bg-green-50 text-green-700'
                       }}"
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
                        d="M3 12l9-9 9 9"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5.25 10.5V21h13.5V10.5"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9.75 21v-6h4.5v6"
                    />

                </svg>

            </span>


            {{-- TEXT --}}
            <span
                x-show="sidebarOpen"
                x-transition
                class="flex-1 whitespace-nowrap"
            >
                Dashboard
            </span>

        </a>


        {{-- =================================================
            DATA MANAGEMENT
        ================================================== --}}

        <div class="mb-2">

            {{-- MAIN BUTTON --}}
            <button
                type="button"
                @click="dataManagementOpen = !dataManagementOpen"

                class="group flex w-full items-center gap-3
                       rounded-xl px-2 py-3
                       text-sm font-semibold
                       transition-all duration-200

                       {{ request()->routeIs('data-management*')
                            ? 'bg-green-700 text-white shadow-md'
                            : 'text-gray-600 hover:bg-green-50 hover:text-green-800'
                       }}"
                >

                {{-- ICON --}}
                <span
                    class="flex h-10 w-10 shrink-0
                           items-center justify-center
                           rounded-lg

                           {{ request()->routeIs('data-management*')
                                ? 'bg-white/15 text-white'
                                : 'bg-green-50 text-green-700'
                           }}"
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
                            d="M3.75 6.75A2.25 2.25 0
                               016 4.5h4.125l2.25
                               2.25H18a2.25 2.25
                               0 012.25 2.25v8.25A2.25
                               2.25 0 0118 19.5H6a2.25
                               2.25 0 01-2.25-2.25V6.75z"
                        />

                    </svg>

                </span>


                {{-- TEXT --}}
                <span
                    x-show="sidebarOpen"
                    x-transition
                    class="flex-1 whitespace-nowrap text-left"
                >
                    Data Management
                </span>


                {{-- ARROW --}}
                <svg
                    x-show="sidebarOpen"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 shrink-0 transition-transform duration-200"
                    :class="{
                        'rotate-180': dataManagementOpen
                    }"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 9l6 6 6-6"
                    />

                </svg>

            </button>


            {{-- =================================================
                DATA MANAGEMENT SUBMENU
            ================================================== --}}

            <div
                x-show="dataManagementOpen && sidebarOpen"
                x-transition
                class="ml-5 mt-1 space-y-1
                       border-l-2 border-green-100
                       pl-4"
            >

                {{-- PERSONNEL INFORMATION --}}
                <a
                    href="{{ route('data-management.personnel') }}"
                    class="block rounded-lg px-3 py-2.5
                           text-sm transition

                           {{ request()->routeIs('data-management.personnel*')
                                ? 'bg-green-50 font-semibold text-green-800'
                                : 'text-gray-500 hover:bg-green-50 hover:text-green-700'
                           }}"
                >
                    Personnel Information
                </a>


                {{-- EMPLOYMENT STATUS --}}
                <a
                    href="{{ route('data-management.employment-status') }}"
                    class="block rounded-lg px-3 py-2.5
                           text-sm transition

                           {{ request()->routeIs('data-management.employment-status*')
                                ? 'bg-green-50 font-semibold text-green-800'
                                : 'text-gray-500 hover:bg-green-50 hover:text-green-700'
                           }}"
                >
                    Employment Status
                </a>


                {{-- PLANTILLA --}}
                <a
                    href="{{ route('data-management.plantilla') }}"
                    class="block rounded-lg px-3 py-2.5
                           text-sm transition

                           {{ request()->routeIs('data-management.plantilla*')
                                ? 'bg-green-50 font-semibold text-green-800'
                                : 'text-gray-500 hover:bg-green-50 hover:text-green-700'
                           }}"
                >
                    Plantilla Database
                </a>


                {{-- SCHOOL DATABASE --}}
                <a
                    href="{{ route('data-management.schools') }}"
                    class="block rounded-lg px-3 py-2.5
                           text-sm transition

                           {{ request()->routeIs('data-management.schools*')
                                ? 'bg-green-50 font-semibold text-green-800'
                                : 'text-gray-500 hover:bg-green-50 hover:text-green-700'
                           }}"
                >
                    School Database
                </a>


                {{-- ENROLLMENT --}}
                <a
                    href="{{ route('data-management.enrollment') }}"
                    class="block rounded-lg px-3 py-2.5
                           text-sm transition

                           {{ request()->routeIs('data-management.enrollment*')
                                ? 'bg-green-50 font-semibold text-green-800'
                                : 'text-gray-500 hover:bg-green-50 hover:text-green-700'
                           }}"
                >
                    Enrollment Records
                </a>

                {{-- MEDICAL ALLOWANCE --}}
                <a
                    href="{{ route('data-management.medical-allowance') }}"
                    class="block rounded-lg px-3 py-2.5
                           text-sm transition

                           {{ request()->routeIs('data-management.medical-allowance*')
                                ? 'bg-green-50 font-semibold text-green-800'
                                : 'text-gray-500 hover:bg-green-50 hover:text-green-700'
                           }}"
                >
                    Medical Allowance
                </a>

            </div>

        </div>


        {{-- =================================================
            HR TRANSACTIONS
        ================================================== --}}

        <div class="mb-2">

            {{-- MAIN BUTTON --}}
            <button
                type="button"
                @click="hrTransactionsOpen = !hrTransactionsOpen"

                class="group flex w-full items-center gap-3
                       rounded-xl px-2 py-3
                       text-sm font-semibold
                       transition-all duration-200

                       {{ request()->routeIs('hr-transactions*')
                            ? 'bg-green-700 text-white shadow-md'
                            : 'text-gray-600 hover:bg-green-50 hover:text-green-800'
                       }}"
            >

                {{-- ICON --}}
                <span
                    class="flex h-10 w-10 shrink-0
                           items-center justify-center
                           rounded-lg

                           {{ request()->routeIs('hr-transactions*')
                                ? 'bg-white/15 text-white'
                                : 'bg-green-50 text-green-700'
                           }}"
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
                            d="M16 21v-2a4 4 0
                               00-4-4H6a4 4 0
                               00-4 4v2"
                        />

                        <circle
                            cx="9"
                            cy="7"
                            r="4"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M19 8v6M22 11h-6"
                        />

                    </svg>

                </span>


                {{-- TEXT --}}
                <span
                    x-show="sidebarOpen"
                    x-transition
                    class="flex-1 whitespace-nowrap text-left"
                >
                    HR Transactions
                </span>


                {{-- ARROW --}}
                <svg
                    x-show="sidebarOpen"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 shrink-0 transition-transform duration-200"
                    :class="{
                        'rotate-180': hrTransactionsOpen
                    }"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 9l6 6 6-6"
                    />

                </svg>

            </button>


            {{-- HR TRANSACTIONS SUBMENU --}}
            <div
                x-show="hrTransactionsOpen && sidebarOpen"
                x-transition
                class="ml-5 mt-1 space-y-1
                       border-l-2 border-green-100
                       pl-4"
            >

                <a
                    href="#"
                    class="block rounded-lg px-3 py-2.5
                           text-sm text-gray-500
                           transition hover:bg-green-50
                           hover:text-green-700"
                >
                    Personnel Requests
                </a>


                <a
                    href="#"
                    class="block rounded-lg px-3 py-2.5
                           text-sm text-gray-500
                           transition hover:bg-green-50
                           hover:text-green-700"
                >
                    Service Records
                </a>


                <a
                    href="#"
                    class="block rounded-lg px-3 py-2.5
                           text-sm text-gray-500
                           transition hover:bg-green-50
                           hover:text-green-700"
                >
                    Other Transactions
                </a>

            </div>

        </div>

    </nav>

    {{-- SIDEBAR FOOTER --}}
    <div
        class="mt-auto shrink-0
               border-t border-gray-100
               bg-white px-4 py-4
               text-center"
    >

        <p class="text-[10px] leading-relaxed text-gray-500">
            © {{ date('Y') }} Department of Education -<br>
            Leyte Division • Personnel Unit • @joviegayo
        </p>

    </div>
</aside>