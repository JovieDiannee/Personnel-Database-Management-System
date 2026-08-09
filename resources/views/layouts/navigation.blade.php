<nav
    x-data="{ open: false }"
    class="sticky top-0 z-50 border-b-4 border-green-700 bg-white shadow-md"
>

    {{-- TOP NAVIGATION --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="flex h-20 items-center justify-between">

            {{-- LEFT SIDE --}}
            <div class="flex items-center">

                {{-- LOGO AND BRAND --}}
                <a
                    href="{{ route('dashboard') }}"
                    class="flex items-center gap-3"
                >

                    {{-- Logo --}}
                    <div class="flex h-14 w-14 items-center justify-center">

                        <img
                            src="{{ asset('images/pdms-logo.png') }}"
                            alt="PDMS Logo"
                            class="h-full w-full object-contain"
                        >

                    </div>


                    {{-- Brand Name --}}
                    <div class="border-l-2 border-green-600 pl-3">

                        <div class="text-xs font-bold uppercase tracking-widest text-green-700">
                            Personnel Unit
                        </div>

                        <div class="text-lg font-bold leading-tight text-gray-800">
                            Personnel Database
                        </div>

                        <div class="text-xs font-medium text-gray-500">
                            Management System
                        </div>

                    </div>

                </a>


                {{-- DESKTOP NAVIGATION --}}
                <div class="ml-12 hidden items-center gap-1 sm:flex">

                    {{-- Dashboard --}}
                    <a
                        href="{{ route('dashboard') }}"
                        class="relative rounded-md px-4 py-3 text-sm font-semibold transition duration-200
                        {{ request()->routeIs('dashboard')
                            ? 'bg-green-700 text-white'
                            : 'text-gray-600 hover:bg-green-100 hover:text-green-800'
                        }}"
                    >
                        Dashboard

                        @if(request()->routeIs('dashboard'))
                            <span
                                class="absolute bottom-0 left-4 right-4 h-1 rounded-t-full bg-green-600"
                            ></span>
                        @endif
                    </a>

                    {{-- Data Management --}}
                    <a
                        href="{{ route('data-management') }}"
                        class="relative rounded-md px-4 py-3 text-sm font-semibold transition duration-200
                        {{ request()->routeIs('data-management')
                            ? 'bg-green-700 text-white'
                            : 'text-gray-600 hover:bg-green-100 hover:text-green-800'
                        }}"
                    >
                        Data Management

                        @if(request()->routeIs('data-management'))
                            <span
                                class="absolute bottom-0 left-4 right-4 h-1 rounded-t-full bg-green-600"
                            ></span>
                        @endif
                    </a>


                    {{-- HR Transactions --}}
                    <a
                        href="#"
                        class="rounded-md px-4 py-3 text-sm font-semibold text-gray-600
                        transition duration-200
                        hover:bg-green-100
                        hover:text-green-800"
                    >
                        HR Transactions
                    </a>

                </div>
            </div>


            {{-- RIGHT SIDE --}}
            <div class="hidden items-center gap-5 sm:flex">

                {{-- Notification --}}
                <button
                    type="button"
                    class="relative rounded-lg p-2 text-gray-500 transition hover:bg-green-50 hover:text-green-700"
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
                            d="M15 17h5l-1.5-2V9a6.5 6.5 0 00-13 0v6L4 17h5m6 0a3 3 0 01-6 0"
                        />

                    </svg>


                    {{-- Notification Badge --}}
                    <span
                        class="absolute right-1 top-1 h-2 w-2 rounded-full bg-green-600"
                    ></span>

                </button>


                {{-- USER DROPDOWN --}}
                <x-dropdown align="right" width="72">

                    <x-slot name="trigger">

                        <button
                            type="button"
                            class="flex items-center gap-3 rounded-lg px-3 py-2
                                transition hover:bg-green-50"
                        >

                            {{-- User Details --}}
                            <div class="text-right">

                                <div class="whitespace-nowrap text-sm font-semibold text-gray-800">
                                    {{ Auth::user()->name }}
                                </div>

                                <div class="whitespace-nowrap text-xs text-gray-500">
                                    {{ Auth::user()->email }}
                                </div>

                            </div>


                            {{-- Arrow --}}
                            <svg
                                class="h-4 w-4 shrink-0 text-gray-500"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >

                                <path
                                    fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />

                            </svg>

                        </button>

                    </x-slot>


                    <x-slot name="content">

                        {{-- Account Header --}}
                        <div class="border-b border-gray-100 bg-green-50 px-4 py-3">

                            <div class="text-xs font-bold uppercase tracking-wider text-green-700">
                                Account
                            </div>

                            <div class="mt-1 font-semibold text-gray-800">
                                {{ Auth::user()->name }}
                            </div>

                            <div class="mt-1 text-xs text-gray-500">
                                {{ Auth::user()->email }}
                            </div>

                        </div>


                        {{-- Profile --}}
                        <x-dropdown-link :href="route('profile.edit')">

                            <span class="flex items-center gap-2">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 text-green-700"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 0115 0"
                                    />

                                </svg>

                                Profile

                            </span>

                        </x-dropdown-link>


                        {{-- Logout --}}
                        <form method="POST" action="{{ route('logout') }}">

                            @csrf

                            <x-dropdown-link
                                :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                            >

                                <span class="flex items-center gap-2 text-red-600">

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
                                            d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3-3H9m0 0l3-3m-3 3l3 3"
                                        />

                                    </svg>

                                    Log Out

                                </span>

                            </x-dropdown-link>

                        </form>

                    </x-slot>

                </x-dropdown>

            </div>


            {{-- MOBILE BUTTON --}}
            <div class="flex items-center sm:hidden">

                <button
                    @click="open = ! open"
                    type="button"
                    class="rounded-lg p-2 text-gray-600 transition hover:bg-green-50 hover:text-green-700"
                >

                    <svg
                        class="h-7 w-7"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 24 24"
                    >

                        <path
                            :class="{ 'hidden': open, 'inline-flex': !open }"
                            class="inline-flex"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />

                        <path
                            :class="{ 'hidden': !open, 'inline-flex': open }"
                            class="hidden"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />

                    </svg>

                </button>

            </div>

        </div>

    </div>


    {{-- MOBILE NAVIGATION --}}
    <div
        :class="{ 'block': open, 'hidden': !open }"
        class="hidden border-t border-green-100 bg-white sm:hidden"
    >

        <div class="space-y-1 px-4 pb-4 pt-3">

            <a
                href="{{ route('dashboard') }}"
                class="relative rounded-md px-4 py-3 text-sm font-semibold transition duration-200
                {{ request()->routeIs('dashboard')
                    ? 'bg-green-700 text-white'
                    : 'text-gray-600 hover:bg-green-100 hover:text-green-800'
                }}"
            >
                Dashboard
            </a>

            <a
                href="#"
                class="rounded-md px-4 py-3 text-sm font-semibold text-gray-600
                transition duration-200
                hover:bg-green-100
                hover:text-green-800"
            >
                Employees
            </a>

            <a
                href="#"
                class="rounded-md px-4 py-3 text-sm font-semibold text-gray-600
                transition duration-200
                hover:bg-green-100
                hover:text-green-800"
            >
                Schools
            </a>

            <a
                href="#"
                class="rounded-md px-4 py-3 text-sm font-semibold text-gray-600
                transition duration-200
                hover:bg-green-100
                hover:text-green-800"
            >
                HR Transactions
            </a>

        </div>


        {{-- MOBILE USER --}}
        <div class="border-t border-green-100 bg-green-50 px-4 py-4">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-700 font-bold text-white">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>

                <div>

                    <div class="font-semibold text-gray-800">
                        {{ Auth::user()->name }}
                    </div>

                    <div class="text-sm text-gray-500">
                        {{ Auth::user()->email }}
                    </div>

                </div>

            </div>


            <div class="mt-4 space-y-1">

                <a
                    href="{{ route('profile.edit') }}"
                    class="block rounded-lg px-4 py-2 text-gray-700 hover:bg-white hover:text-green-700"
                >
                    Profile
                </a>


                <form method="POST" action="{{ route('logout') }}">

                    @csrf

                    <button
                        type="submit"
                        class="w-full rounded-lg px-4 py-2 text-left text-red-600 hover:bg-white"
                    >
                        Log Out
                    </button>

                </form>

            </div>

        </div>

    </div>

</nav>