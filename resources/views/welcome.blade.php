<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>{{ config('app.name', 'Personnel Database Management System') }}</title>

    {{-- FAVICON --}}
    <link rel="icon" type="image/png" href="{{ asset('images/pdms-favicon.png') }}">

@vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-gray-100 text-gray-800 antialiased">

{{-- TOP GOVERNMENT-STYLE HEADER --}}
<header class="border-b-4 border-green-700 bg-white shadow-sm">

    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8">

        {{-- BRANDING --}}
        <div class="flex items-center gap-4">

            {{-- Temporary Government Building Icon --}}
            <div class="flex h-16 w-16 items-center justify-center rounded-full border-2 border-green-700 bg-white overflow-hidden">

                <img
                    src="{{ asset('images/pdms-logo.png') }}"
                    alt="PDMS Logo"
                    class="h-14 w-14 object-contain"
                >

            </div>

            <div>

                <p class="text-xs font-semibold uppercase tracking-widest text-green-700">
                    Department of Education - Schools Division of Leyte
                </p>

                <h1 class="text-lg font-bold text-gray-800">
                    Personnel Database Management System
                </h1>

                <p class="text-xs text-gray-500">
                    Digital Personnel Unit Information Management
                </p>

            </div>

        </div>


        {{-- LOGIN --}}
        @if (Route::has('login'))

            <div class="flex items-center gap-3">

                @auth

                    <a
                        href="{{ url('/dashboard') }}"
                        class="rounded-lg bg-green-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-green-800"
                    >
                        Dashboard
                    </a>

                @else

                    <a
                        href="{{ route('login') }}"
                            class="hidden rounded-lg bg-green-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-green-800 sm:inline-block"
                    >
                        Sign In
                    </a>

                    <!-- @if (Route::has('register'))

                        <a
                            href="{{ route('register') }}"
                            class="hidden rounded-lg bg-green-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-green-800 sm:inline-block"
                        >
                            Register
                        </a>

                    @endif -->

                @endauth

            </div>

        @endif

    </div>

</header>


{{-- GOVERNMENT NOTICE BAR --}}
<div class="bg-green-800 text-white">

    <div class="mx-auto max-w-7xl px-6 py-2 lg:px-8">
    </div>

</div>


{{-- HERO SECTION --}}
<main>

    <section class="relative overflow-hidden bg-gradient-to-br from-green-950 via-green-900 to-green-800">

        {{-- Decorative Background --}}
        <div class="absolute -right-32 -top-32 h-96 w-96 rounded-full border-[40px] border-white/5">
        </div>

        <div class="absolute -bottom-48 -left-32 h-96 w-96 rounded-full border-[40px] border-white/5">
        </div>


        <div class="relative mx-auto grid max-w-7xl items-center gap-12 px-6 py-20 lg:grid-cols-2 lg:px-8 lg:py-28">

            {{-- HERO CONTENT --}}
            <div class="text-white">

                <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-green-400/30 bg-green-800/50 px-4 py-2 text-sm text-green-100">

                    <span class="h-2 w-2 rounded-full bg-green-300"></span>

                    Personnel Database Management System

                </div>


                <h2 class="text-4xl font-bold leading-tight tracking-tight sm:text-5xl lg:text-6xl">

                    Managing Personnel Information

                    <span class="block text-green-300">
                        Through Digital
                    </span>

                </h2>


                <p class="mt-6 max-w-xl text-lg leading-relaxed text-green-50/90">

                    A centralized and secure platform designed to support efficient
                    personnel information management, records monitoring, and
                    administrative services.

                </p>


                <div class="mt-8 flex flex-wrap gap-4">

                    @auth

                        <a
                            href="{{ url('/dashboard') }}"
                            class="rounded-lg bg-white px-6 py-3 font-semibold text-green-800 shadow-lg transition hover:bg-green-50"
                        >
                            Go to Dashboard
                        </a>

                    @else

                        <a
                            href="{{ route('login') }}"
                            class="rounded-lg bg-white px-6 py-3 font-semibold text-green-800 shadow-lg transition hover:bg-green-50"
                        >
                            Access the System
                        </a>

                    @endauth

                    <a
                        href="#about"
                        class="rounded-lg border border-white/40 px-6 py-3 font-semibold text-white transition hover:bg-white/10"
                    >
                        Learn More
                    </a>

                </div>

            </div>


            {{-- SYSTEM OVERVIEW CARD --}}
            <div class="relative">

                <div class="rounded-2xl border border-white/20 bg-white/10 p-6 shadow-2xl backdrop-blur-md">

                    <div class="mb-6 border-b border-white/20 pb-5">

                        <p class="text-sm font-medium uppercase tracking-widest text-green-200">
                            System Overview
                        </p>

                        <h3 class="mt-2 text-2xl font-bold text-white">
                            Personnel Management Services
                        </h3>

                    </div>


                    <div class="grid gap-4 sm:grid-cols-2">

                        <div class="rounded-xl bg-white/10 p-5">

                            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-green-400/20 text-green-200">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 21a8 8 0 0116 0"
                                    />
                                </svg>

                            </div>

                            <h4 class="font-semibold text-white">
                                Personnel Profiles
                            </h4>

                            <p class="mt-1 text-sm text-green-100/70">
                                Centralized personnel information and records.
                            </p>

                        </div>


                        <div class="rounded-xl bg-white/10 p-5">

                            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-green-400/20 text-green-200">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l4 4v12a2 2 0 01-2 2z"
                                    />
                                </svg>

                            </div>

                            <h4 class="font-semibold text-white">
                                Digital Records
                            </h4>

                            <p class="mt-1 text-sm text-green-100/70">
                                Organized and accessible personnel documentation.
                            </p>

                        </div>


                        <div class="rounded-xl bg-white/10 p-5">

                            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-green-400/20 text-green-200">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M12 15v2m0-8v2m0 4h.01M5.07 19h13.86a2 2 0 001.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16a2 2 0 001.73 3z"
                                    />
                                </svg>

                            </div>

                            <h4 class="font-semibold text-white">
                                Secure Access
                            </h4>

                            <p class="mt-1 text-sm text-green-100/70">
                                Controlled access to authorized personnel.
                            </p>

                        </div>


                        <div class="rounded-xl bg-white/10 p-5">

                            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-green-400/20 text-green-200">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2z"
                                    />
                                </svg>

                            </div>

                            <h4 class="font-semibold text-white">
                                Reports
                            </h4>

                            <p class="mt-1 text-sm text-green-100/70">
                                Support for administrative reporting and monitoring.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- ABOUT SECTION --}}
    <section
        id="about"
        class="bg-white py-20"
    >

        <div class="mx-auto max-w-7xl px-6 lg:px-8">

            <div class="mx-auto max-w-3xl text-center">

                <p class="text-sm font-semibold uppercase tracking-widest text-green-700">
                    About the System
                </p>

                <h2 class="mt-3 text-3xl font-bold text-gray-900 sm:text-4xl">
                    A Digital Approach to Personnel Management
                </h2>

                <p class="mt-5 text-lg leading-relaxed text-gray-600">

                    The Personnel Database Management System provides a centralized
                    platform for managing personnel records and supporting efficient,
                    secure, and data-driven administrative operations.

                </p>

            </div>


            <div class="mt-12 grid gap-6 md:grid-cols-3">

                <div class="border-t-4 border-green-700 bg-gray-50 p-6 shadow-sm">

                    <h3 class="font-bold text-gray-900">
                        Centralized Information
                    </h3>

                    <p class="mt-3 text-sm leading-relaxed text-gray-600">
                        Maintain personnel information in a centralized and organized system.
                    </p>

                </div>


                <div class="border-t-4 border-green-700 bg-gray-50 p-6 shadow-sm">

                    <h3 class="font-bold text-gray-900">
                        Efficient Administration
                    </h3>

                    <p class="mt-3 text-sm leading-relaxed text-gray-600">
                        Improve access to information and support faster administrative processes.
                    </p>

                </div>


                <div class="border-t-4 border-green-700 bg-gray-50 p-6 shadow-sm">

                    <h3 class="font-bold text-gray-900">
                        Secure and Authorized Access
                    </h3>

                    <p class="mt-3 text-sm leading-relaxed text-gray-600">
                        Protect personnel information through controlled user access.
                    </p>

                </div>

            </div>

        </div>

    </section>

</main>


{{-- FOOTER --}}
<footer class="bg-gray-900 text-gray-300">

    <div class="mx-auto max-w-7xl px-6 py-10 lg:px-8">

        <div class="grid gap-8 md:grid-cols-3">

            <div>

                <h3 class="font-semibold text-white">
                    Personnel Database Management System
                </h3>

                <p class="mt-3 text-sm leading-relaxed text-gray-400">
                    A digital platform for efficient and secure personnel information management.
                </p>

            </div>


            <div>

                <h3 class="font-semibold text-white">
                    System Information
                </h3>

                <ul class="mt-3 space-y-2 text-sm text-gray-400">

                    <li>
                        Personnel Information Management
                    </li>

                    <li>
                        Secure User Authentication
                    </li>

                    <li>
                        Administrative Reporting

                    </li>

                </ul>

            </div>


            <div>

                <h3 class="font-semibold text-white">
                    Authorized Access
                </h3>

                <p class="mt-3 text-sm leading-relaxed text-gray-400">
                    This system is intended for authorized personnel only.
                    Unauthorized access is prohibited.
                </p>

            </div>

        </div>


        <div class="mt-10 border-t border-gray-800 pt-6 text-center text-xs text-gray-500">

            <p>
                © {{ date('Y') }} Personnel Database Management System.
                All rights reserved.
            </p>

            <p class="mt-2">
                Developed for digital personnel information management.
            </p>

        </div>

    </div>

</footer>

</body>

</html>
