{{-- =====================================================
    PDMS LEFT SIDEBAR
====================================================== --}}

<aside
    class="fixed inset-y-0 left-0 z-50
           hidden flex-col lg:flex
           bg-white shadow-xl
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
        class="flex-1 overflow-y-auto px-3 py-3"

        x-data="{
            dataManagementOpen:
                {{ (request()->routeIs('data-management*') && !request()->routeIs('data-management.reports*') && !request()->routeIs('data-management.medical-allowance*')) ? 'true' : 'false' }},

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
            class="mb-2 px-3"
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
            class="group mb-1 flex items-center gap-3
                   rounded-xl px-2 py-1.5
                   text-sm font-semibold
                   transition-all duration-200

                   {{ request()->routeIs('dashboard')
                        ? 'bg-green-700 text-white shadow-md'
                        : 'text-gray-600 hover:bg-green-50 hover:text-green-800'
                   }}"
        >

            {{-- ICON --}}
            <span
                class="flex h-8 w-8 shrink-0
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


        {{-- =====================================================
            EMPLOYEE MANAGEMENT — START
        ====================================================== --}}

        @php
            $personnelRequestsActive = request()->routeIs(
                'admin.personnel-requests.*'
            );

            $employeeManagementActive = request()->routeIs(
                'data-management.personnel*',
                'data-management.employment-status*',
                'admin.personnel-requests.*',
                'add-personnel-requests.*'
            );
        @endphp

        <div
            class="mb-1"
            x-data="{
                employeeManagementOpen: {{ $employeeManagementActive ? 'true' : 'false' }}
            }"
        >
            <button
                type="button"
                @click="
                    if (!sidebarOpen) {
                        sidebarOpen = true;
                        employeeManagementOpen = true;
                    } else {
                        employeeManagementOpen = !employeeManagementOpen;
                    }
                "
                :aria-expanded="employeeManagementOpen && sidebarOpen"
                aria-controls="employee-management-submenu"
                aria-label="Employee Management"
                title="Employee Management"
                class="group flex w-full items-center gap-3 rounded-xl
                    px-2 py-1.5 text-sm font-semibold transition-all duration-200
                    {{ $employeeManagementActive ? 'shadow-md' : 'hover:bg-green-50' }}"
                style="{{ $employeeManagementActive
                    ? 'background-color: #15803d; color: #ffffff;'
                    : 'color: #4b5563;' }}"
            >
                {{-- ICON --}}
                <span
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg"
                    style="{{ $employeeManagementActive
                        ? 'background-color: rgba(255,255,255,0.15); color: #ffffff;'
                        : 'background-color: #f0fdf4; color: #15803d;' }}"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"
                        />
                        <circle cx="9" cy="7" r="4" />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"
                        />
                    </svg>
                </span>

                <span
                    x-show="sidebarOpen"
                    x-transition
                    class="min-w-0 flex-1 text-left"
                    style="{{ $employeeManagementActive
                        ? 'color: #ffffff;'
                        : 'color: #4b5563;' }}"
                >
                    Employee Management
                </span>

                <svg
                    x-show="sidebarOpen"
                    :class="{ 'rotate-180': employeeManagementOpen }"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 shrink-0 transition-transform duration-200"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 9l6 6 6-6"
                    />
                </svg>
            </button>

            {{-- SUBMENU --}}
            <div
                id="employee-management-submenu"
                x-show="employeeManagementOpen && sidebarOpen"
                x-transition
                class="ml-5 mt-1 space-y-0.5 border-l-2 pl-4"
                style="display: none; border-color: #dcfce7;"
            >
                {{-- USER ACCOUNTS --}}
                <a
                    href="{{ route('data-management.personnel') }}"
                    @if(request()->routeIs('data-management.personnel*'))
                        aria-current="page"
                    @endif
                    class="block rounded-lg px-3 py-1.5 text-sm transition hover:bg-green-50"
                    style="{{ request()->routeIs('data-management.personnel*')
                        ? 'background-color: #f0fdf4; color: #166534; font-weight: 600;'
                        : 'color: #6b7280;' }}"
                >
                    User Accounts
                </a>

                {{-- EMPLOYEE PROFILE --}}
                <a
                    href="{{ route('data-management.employment-status') }}"
                    @if(request()->routeIs('data-management.employment-status*'))
                        aria-current="page"
                    @endif
                    class="block rounded-lg px-3 py-1.5 text-sm transition hover:bg-green-50"
                    style="{{ request()->routeIs('data-management.employment-status*')
                        ? 'background-color: #f0fdf4; color: #166534; font-weight: 600;'
                        : 'color: #6b7280;' }}"
                >
                    Employee Profile
                </a>

                {{-- ADD PERSONNEL REQUESTS — SUPER ADMIN ONLY --}}
                @if(auth()->user()?->role === 'super_admin')
                    @php
                        $pendingPersonnelRequests =
                            \App\Models\AddPersonnelRequest::where('status', 'pending')
                                ->count();
                    @endphp

                    <a
                        href="{{ route('admin.personnel-requests.index') }}"
                        @if($personnelRequestsActive)
                            aria-current="page"
                        @endif
                        class="flex items-center gap-3 rounded-lg px-3 py-1.5
                            text-sm transition hover:bg-green-50"
                        style="{{ $personnelRequestsActive
                            ? 'background-color: #f0fdf4; color: #166534; font-weight: 600;'
                            : 'color: #6b7280;' }}"
                    >
                        <span class="flex-1">
                            Add Personnel Requests
                        </span>

                        @if($pendingPersonnelRequests > 0)
                            <span
                                class="ml-auto rounded-full px-2.5 py-0.5 text-xs font-bold"
                                style="background-color: #fee2e2; color: #b91c1c;"
                            >
                                {{ $pendingPersonnelRequests }}
                            </span>
                        @endif
                    </a>
                @endif
            </div>
        </div>

        {{-- EMPLOYEE MANAGEMENT — END --}}
  

        {{-- EMPLOYEE BENEFITS --}}
        @php
            $benefitsActive = request()->routeIs('data-management.medical-allowance*');
        @endphp
            <div
                class="mb-1"
                x-data="{ employeeBenefitsOpen: {{ $benefitsActive ? 'true' : 'false' }} }"
            >
                {{-- MAIN BUTTON --}}
                <button
                    type="button"
                    @click="
                        if (!sidebarOpen) {
                            sidebarOpen = true;
                            employeeBenefitsOpen = true;
                        } else {
                            employeeBenefitsOpen = !employeeBenefitsOpen;
                        }
                    "
                    :aria-expanded="employeeBenefitsOpen && sidebarOpen"
                    aria-controls="employee-benefits-submenu"
                    aria-label="Employee Benefits"
                    title="Employee Benefits"
                    class="group flex w-full items-center gap-3
                        rounded-xl px-2 py-1.5
                        text-sm font-semibold
                        transition-all duration-200
                        {{ $benefitsActive
                                ? 'bg-green-700 text-white shadow-md'
                                : 'text-gray-600 hover:bg-green-50 hover:text-green-800'
                        }}"
                >
                    {{-- ICON --}}
                    <span
                        class="flex h-8 w-8 shrink-0
                            items-center justify-center rounded-lg
                            {{ $benefitsActive
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
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M14 2v6h6M8 13h8M8 17h5"
                            />
                        </svg>
                    </span>

                    <span
                        x-show="sidebarOpen"
                        x-transition
                        class="flex-1 whitespace-nowrap text-left"
                    >
                        Employee Benefits
                    </span>

                    {{-- ARROW --}}
                    <svg
                        x-show="sidebarOpen"
                        :class="{ 'rotate-180': employeeBenefitsOpen }"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 shrink-0 transition-transform duration-200"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 9l6 6 6-6"
                        />
                    </svg>
                </button>

                {{-- SUBMENU --}}
                <div
                    id="employee-benefits-submenu"
                    x-show="employeeBenefitsOpen && sidebarOpen"
                    x-transition
                    style="display: none;"
                    class="ml-5 mt-1 space-y-0.5 border-l-2 border-green-100 pl-4"
                >
                    <a
                        href="{{ route('data-management.medical-allowance') }}"
                        @if(request()->routeIs('data-management.medical-allowance*'))
                            aria-current="page"
                        @endif
                        class="block rounded-lg px-3 py-1.5 text-sm transition
                            {{ request()->routeIs('data-management.medical-allowance*')
                                    ? 'bg-green-50 font-semibold text-green-800'
                                    : 'text-gray-500 hover:bg-green-50 hover:text-green-700'
                            }}"
                    >
                        Medical Allowance
                    </a>
                </div>
            </div>

        {{-- =====================================================
            PAYROLL SERVICES — START
        ====================================================== --}}

        @if(in_array(auth()->user()?->role, ['super_admin', 'admin'], true))
            @php
                $payrollActive = request()->routeIs('payroll-services.*');
            @endphp

            <div
                class="mb-1"
                x-data="{ payrollOpen: {{ $payrollActive ? 'true' : 'false' }} }"
            >
                <button
                    type="button"
                    @click="
                        if (!sidebarOpen) {
                            sidebarOpen = true;
                            payrollOpen = true;
                        } else {
                            payrollOpen = !payrollOpen;
                        }
                    "
                    :aria-expanded="payrollOpen && sidebarOpen"
                    aria-controls="payroll-services-submenu"
                    aria-label="Payroll Services"
                    title="Payroll Services"
                    class="group flex w-full items-center gap-3 rounded-xl
                        px-2 py-1.5 text-sm font-semibold transition-all duration-200
                        {{ $payrollActive ? 'shadow-md' : 'hover:bg-green-50' }}"
                    style="{{ $payrollActive
                        ? 'background-color: #15803d; color: #ffffff;'
                        : 'color: #4b5563;' }}"
                >
                    {{-- ICON --}}
                    <span
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg"
                        style="{{ $payrollActive
                            ? 'background-color: rgba(255,255,255,0.15); color: #ffffff;'
                            : 'background-color: #f0fdf4; color: #15803d;' }}"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                            aria-hidden="true"
                        >
                            <rect x="3" y="5" width="18" height="14" rx="2" />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 9h18M7 14h3M14 14h3"
                            />
                        </svg>
                    </span>

                    <span
                        x-show="sidebarOpen"
                        x-transition
                        class="min-w-0 flex-1 text-left"
                        style="{{ $payrollActive
                            ? 'color: #ffffff;'
                            : 'color: #4b5563;' }}"
                    >
                        Payroll Services
                    </span>

                    <svg
                        x-show="sidebarOpen"
                        :class="{ 'rotate-180': payrollOpen }"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 shrink-0 transition-transform duration-200"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 9l6 6 6-6"
                        />
                    </svg>
                </button>

                <div
                    id="payroll-services-submenu"
                    x-show="payrollOpen && sidebarOpen"
                    x-transition
                    class="ml-5 mt-1 space-y-0.5 border-l-2 pl-4"
                    style="display: none; border-color: #dcfce7;"
                >
                    <a
                        href="{{ route('payroll-services.inclusion') }}"
                        @if(request()->routeIs('payroll-services.inclusion'))
                            aria-current="page"
                        @endif
                        class="block rounded-lg px-3 py-1.5
                            text-sm transition hover:bg-green-50"
                        style="{{ request()->routeIs('payroll-services.inclusion')
                            ? 'background-color: #f0fdf4; color: #166534; font-weight: 600;'
                            : 'color: #6b7280;' }}"
                    >
                        Payroll Inclusion
                    </a>
                </div>
            </div>
        @endif

        {{-- PAYROLL SERVICES — END --}}

        {{-- =====================================================
            DATA MANAGEMENT — START
        ====================================================== --}}

        @if(auth()->user()?->role === 'super_admin')
            @php
                $dataManagementActive = request()->routeIs(
                    'data-management',
                    'data-management.plantilla*',
                    'data-management.schools*',
                    'data-management.enrollment*'
                );
            @endphp

            <div
                class="mb-1"
                x-data="{
                    dataManagementOpen: {{ $dataManagementActive ? 'true' : 'false' }}
                }"
            >
                <button
                    type="button"
                    @click="
                        if (!sidebarOpen) {
                            sidebarOpen = true;
                            dataManagementOpen = true;
                        } else {
                            dataManagementOpen = !dataManagementOpen;
                        }
                    "
                    :aria-expanded="dataManagementOpen && sidebarOpen"
                    aria-controls="data-management-submenu"
                    aria-label="Data Management"
                    title="Data Management"
                    class="group flex w-full items-center gap-3 rounded-xl
                        px-2 py-1.5 text-sm font-semibold transition-all duration-200
                        {{ $dataManagementActive ? 'shadow-md' : 'hover:bg-green-50' }}"
                    style="{{ $dataManagementActive
                        ? 'background-color: #15803d; color: #ffffff;'
                        : 'color: #4b5563;' }}"
                >
                    {{-- ICON --}}
                    <span
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg"
                        style="{{ $dataManagementActive
                            ? 'background-color: rgba(255,255,255,0.15); color: #ffffff;'
                            : 'background-color: #f0fdf4; color: #15803d;' }}"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3.75 6.75A2.25 2.25 0 016 4.5h4.125l2.25
                                2.25H18a2.25 2.25 0 012.25 2.25v8.25A2.25
                                2.25 0 0118 19.5H6a2.25 2.25 0
                                01-2.25-2.25V6.75z"
                            />
                        </svg>
                    </span>

                    <span
                        x-show="sidebarOpen"
                        x-transition
                        class="min-w-0 flex-1 text-left"
                        style="{{ $dataManagementActive
                            ? 'color: #ffffff;'
                            : 'color: #4b5563;' }}"
                    >
                        Data Management
                    </span>

                    <svg
                        x-show="sidebarOpen"
                        :class="{ 'rotate-180': dataManagementOpen }"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 shrink-0 transition-transform duration-200"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 9l6 6 6-6"
                        />
                    </svg>
                </button>

                {{-- SUBMENU --}}
                <div
                    id="data-management-submenu"
                    x-show="dataManagementOpen && sidebarOpen"
                    x-transition
                    class="ml-5 mt-1 space-y-0.5 border-l-2 pl-4"
                    style="display: none; border-color: #dcfce7;"
                >
                    {{-- PLANTILLA DATABASE --}}
                    <a
                        href="{{ route('data-management.plantilla') }}"
                        @if(request()->routeIs('data-management.plantilla*'))
                            aria-current="page"
                        @endif
                        class="block rounded-lg px-3 py-1.5 text-sm transition hover:bg-green-50"
                        style="{{ request()->routeIs('data-management.plantilla*')
                            ? 'background-color: #f0fdf4; color: #166534; font-weight: 600;'
                            : 'color: #6b7280;' }}"
                    >
                        Plantilla Database
                    </a>

                    {{-- SCHOOL DATABASE --}}
                    <a
                        href="{{ route('data-management.schools') }}"
                        @if(request()->routeIs('data-management.schools*'))
                            aria-current="page"
                        @endif
                        class="block rounded-lg px-3 py-1.5 text-sm transition hover:bg-green-50"
                        style="{{ request()->routeIs('data-management.schools*')
                            ? 'background-color: #f0fdf4; color: #166534; font-weight: 600;'
                            : 'color: #6b7280;' }}"
                    >
                        School Database
                    </a>

                    {{-- ENROLLMENT RECORDS --}}
                    <a
                        href="{{ route('data-management.enrollment') }}"
                        @if(request()->routeIs('data-management.enrollment*'))
                            aria-current="page"
                        @endif
                        class="block rounded-lg px-3 py-1.5 text-sm transition hover:bg-green-50"
                        style="{{ request()->routeIs('data-management.enrollment*')
                            ? 'background-color: #f0fdf4; color: #166534; font-weight: 600;'
                            : 'color: #6b7280;' }}"
                    >
                        Enrollment Records
                    </a>
                </div>
            </div>
        @endif

        {{-- DATA MANAGEMENT — END --}}

        {{-- =================================================
            HR TRANSACTIONS
        ================================================== --}}

         @if(auth()->user()->role === 'super_admin')
        <div class="mb-1">

            {{-- MAIN BUTTON --}}
            <button
                type="button"
                @click="hrTransactionsOpen = !hrTransactionsOpen"

                class="group flex w-full items-center gap-3
                       rounded-xl px-2 py-1.5
                       text-sm font-semibold
                       transition-all duration-200

                       {{ request()->routeIs('hr-transactions*')
                            ? 'bg-green-700 text-white shadow-md'
                            : 'text-gray-600 hover:bg-green-50 hover:text-green-800'
                       }}"
            >

                {{-- ICON --}}
                <span
                    class="flex h-8 w-8 shrink-0
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
                class="ml-5 mt-1 space-y-0.5
                       border-l-2 border-green-100
                       pl-4"
                >

                <a
                    href="{{ route('hr-transactions.service-records') }}"
                    class="block rounded-lg px-3 py-1.5
                           text-sm text-gray-500
                           transition hover:bg-green-50
                           hover:text-green-700"
                >
                    Service Records
                </a>


                <a
                    href="{{ route('hr-transactions.other-transactions') }}"
                    class="block rounded-lg px-3 py-1.5
                           text-sm text-gray-500
                           transition hover:bg-green-50
                           hover:text-green-700"
                >
                    Other Transactions
                </a>

            </div>

        </div>
        @endif

        {{-- =================================================
            REPORT MANAGEMENT
        ================================================== --}}
        @if(auth()->user()?->role === 'super_admin')
            @php
                $reportsActive = request()->routeIs('data-management.reports*');
            @endphp

            <div
                class="mb-1"
                x-data="{ reportsOpen: {{ $reportsActive ? 'true' : 'false' }} }"
            >
                {{-- MAIN BUTTON --}}
                <button
                    type="button"
                    @click="
                        if (!sidebarOpen) {
                            sidebarOpen = true;
                            reportsOpen = true;
                        } else {
                            reportsOpen = !reportsOpen;
                        }
                    "
                    :aria-expanded="reportsOpen && sidebarOpen"
                    aria-controls="report-management-submenu"
                    aria-label="Report Management"
                    title="Report Management"
                    class="group flex w-full items-center gap-3
                        rounded-xl px-2 py-1.5
                        text-sm font-semibold
                        transition-all duration-200
                        {{ $reportsActive
                                ? 'bg-green-700 text-white shadow-md'
                                : 'text-gray-600 hover:bg-green-50 hover:text-green-800'
                        }}"
                >
                    {{-- ICON --}}
                    <span
                        class="flex h-8 w-8 shrink-0
                            items-center justify-center rounded-lg
                            {{ $reportsActive
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
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M14 2v6h6M8 13h8M8 17h5"
                            />
                        </svg>
                    </span>

                    <span
                        x-show="sidebarOpen"
                        x-transition
                        class="flex-1 whitespace-nowrap text-left"
                    >
                        Report Management
                    </span>

                    {{-- ARROW --}}
                    <svg
                        x-show="sidebarOpen"
                        :class="{ 'rotate-180': reportsOpen }"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 shrink-0 transition-transform duration-200"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 9l6 6 6-6"
                        />
                    </svg>
                </button>

                {{-- SUBMENU --}}
                <div
                    id="report-management-submenu"
                    x-show="reportsOpen && sidebarOpen"
                    x-transition
                    style="display: none;"
                    class="ml-5 mt-1 space-y-0.5 border-l-2 border-green-100 pl-4"
                >
                    <a
                        href="{{ route('data-management.reports') }}"
                        @if(request()->routeIs('data-management.reports'))
                            aria-current="page"
                        @endif
                        class="block rounded-lg px-3 py-1.5 text-sm transition
                            {{ request()->routeIs('data-management.reports')
                                    ? 'bg-green-50 font-semibold text-green-800'
                                    : 'text-gray-500 hover:bg-green-50 hover:text-green-700'
                            }}"
                    >
                        Report List
                    </a>

                    <a
                        href="{{ route('data-management.reports.submissions') }}"
                        @if(request()->routeIs('data-management.reports.submissions*'))
                            aria-current="page"
                        @endif
                        class="block rounded-lg px-3 py-1.5 text-sm transition
                            {{ request()->routeIs('data-management.reports.submissions*')
                                    ? 'bg-green-50 font-semibold text-green-800'
                                    : 'text-gray-500 hover:bg-green-50 hover:text-green-700'
                            }}"
                    >
                        Report Submissions
                    </a>
                </div>
            </div>
        @endif

        {{-- DANGER ZONE: visible to super_admin and admin. --}}
        @if(in_array(auth()->user()?->role, ['super_admin', 'admin'], true))
            @php
                // Set this to your existing GET page route for selecting an employee to delete.
                // Do not point this navigation link at a DELETE/destroy endpoint.
                $deleteEmployeeRoute = 'danger-zone.delete-employee';
                $deleteEmployeeRouteExists = \Illuminate\Support\Facades\Route::has($deleteEmployeeRoute);
                $dangerZoneActive = request()->routeIs($deleteEmployeeRoute);
            @endphp

            <div
                class="mb-1"
                x-data="{ dangerZoneOpen: {{ $dangerZoneActive ? 'true' : 'false' }} }"
                >
                <button
                    type="button"
                    @click="
                        if (!sidebarOpen) {
                            sidebarOpen = true;
                            dangerZoneOpen = true;
                        } else {
                            dangerZoneOpen = !dangerZoneOpen;
                        }
                    "
                    :aria-expanded="dangerZoneOpen && sidebarOpen"
                    aria-controls="danger-zone-submenu"
                    aria-label="Danger Zone"
                    title="Danger Zone"
                    class="group flex w-full items-center gap-3 rounded-xl
                        px-2 py-1.5 text-sm font-semibold transition-all duration-200"
                    style="{{ $dangerZoneActive
                        ? 'background-color: #b91c1c; color: #ffffff;'
                        : 'background-color: transparent; color: #b91c1c;' }}"
                    >
                    <span
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg"
                        style="{{ $dangerZoneActive
                            ? 'background-color: rgba(255,255,255,0.15); color: #ffffff;'
                            : 'background-color: transparent; color: #b91c1c;' }}"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 3L2 21h20L12 3zM12 9v5m0 3h.01"
                            />
                        </svg>
                    </span>

                    <span
                        x-show="sidebarOpen"
                        x-transition
                        class="flex-1 whitespace-nowrap text-left"
                        style="{{ $dangerZoneActive
                            ? 'color: #ffffff;'
                            : 'color: #b91c1c;' }}"
                    >
                        Danger Zone
                    </span>

                    <svg
                        x-show="sidebarOpen"
                        :class="{ 'rotate-180': dangerZoneOpen }"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 shrink-0 transition-transform duration-200"
                        style="{{ $dangerZoneActive
                            ? 'color: #ffffff;'
                            : 'color: #b91c1c;' }}"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 9l6 6 6-6"
                        />
                    </svg>
                </button>

                <div
                    id="danger-zone-submenu"
                    x-show="dangerZoneOpen && sidebarOpen"
                    x-transition
                    style="display: none;"
                    class="ml-5 mt-1 space-y-0.5 border-l-2 border-red-100 pl-4"
                >
                    @if($deleteEmployeeRouteExists)
                        <a
                            href="{{ route($deleteEmployeeRoute) }}"
                            @if($dangerZoneActive) aria-current="page" @endif
                            class="block rounded-lg px-3 py-1.5 text-sm transition
                                   {{ $dangerZoneActive
                                        ? 'bg-red-50 font-semibold text-red-800'
                                        : 'text-red-600 hover:bg-red-50 hover:text-red-700' }}"
                        >
                            Delete Employee
                        </a>
                    @else
                        <button type="button" disabled aria-disabled="true"
                                class="block w-full cursor-not-allowed rounded-lg px-3 py-1.5 text-left text-sm text-red-400">
                            Delete Employee
                        </button>
                    @endif
                </div>
            </div>
        @endif
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
