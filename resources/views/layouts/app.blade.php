<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ config('app.name', 'Personnel Database Management System') }}
    </title>

     {{-- FAVICON --}}
    <link rel="icon" type="image/png" href="{{ asset('images/pdms-favicon.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">

    <link
        href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
        rel="stylesheet"
    >

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    @stack('styles')

</head>


<body class="font-sans antialiased">

    {{-- =====================================================
        GLOBAL ALPINE STATE
    ====================================================== --}}

    <div
        x-data="{ sidebarOpen: true }"
        class="min-h-screen bg-gray-50"
    >


        {{-- =================================================
            SIDEBAR
        ================================================== --}}

        @include('layouts.sidebar')


        {{-- =================================================
            MAIN APPLICATION

            IMPORTANT:
            The margin MUST match the sidebar width.
        ================================================== --}}

        <div
            class="min-h-screen
                   transition-all duration-300 ease-in-out"

            :class="sidebarOpen
                ? 'ml-72'
                : 'ml-20'"
        >


            {{-- =================================================
                TOP NAVIGATION
            ================================================== --}}

            @include('layouts.navigation')


            {{-- =================================================
                PAGE HEADING
            ================================================== --}}

            @isset($header)

                <header
                    class="border-b-4
                           border-green-700
                           bg-white
                           shadow-sm"
                >

                    <div
                        class="mx-auto max-w-7xl
                               px-4 py-6
                               sm:px-6 lg:px-8"
                    >

                        {{ $header }}

                    </div>

                </header>

            @endisset


            {{-- =================================================
                PAGE CONTENT
            ================================================== --}}

            <main>

                {{ $slot }}

            </main>

        </div>

    </div>

    @stack('scripts')

</body>

</html>