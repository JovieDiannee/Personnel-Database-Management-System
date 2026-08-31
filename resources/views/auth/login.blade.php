<x-guest-layout>


<div class="min-h-screen bg-gradient-to-br from-green-900 via-green-800 to-emerald-700">

    <div class="mx-auto flex min-h-screen max-w-7xl items-center justify-center px-6 py-10">

        <div class="grid w-full max-w-6xl overflow-hidden rounded-3xl bg-white shadow-2xl lg:grid-cols-2">


        {{-- =====================================================
            LEFT SIDE - LOGOS
        ====================================================== --}}

        <div
            class="relative flex min-h-[500px] w-full
                items-center justify-center
                overflow-hidden
                bg-gradient-to-b from-white via-white to-green-50
                px-6
                pt-6
                sm:pt-8
                lg:pt-0
                lg:min-h-full"
            >

            <div class="flex w-full flex-col items-center justify-center text-center">

                {{-- =====================================================
                    GOVERNMENT / OFFICE LOGOS
                ====================================================== --}}

                <div class="flex items-center justify-center gap-6 sm:gap-8">

                    {{-- Bagong Pilipinas / Government Logo --}}
                    <img
                        src="{{ asset('images/bagong-pilipinas.png') }}"
                        alt="Bagong Pilipinas"
                        class="h-16 w-16 object-contain sm:h-20 sm:w-20"
                    >

                    {{-- DepEd Logo --}}
                    <img
                        src="{{ asset('images/deped-logo-2.png') }}"
                        alt="Department of Education"
                        class="h-16 w-24 object-contain sm:h-20 sm:w-28"
                    >

                    {{-- Schools Division of Leyte --}}
                    <img
                        src="{{ asset('images/deped-leyte-logo.png') }}"
                        alt="Schools Division of Leyte"
                        class="h-16 w-16 object-contain sm:h-20 sm:w-20"
                    >

                </div>


                {{-- =====================================================
                    SMALL SYSTEM LABEL
                ====================================================== --}}

                <p class="mt-5 text-xs font-semibold uppercase tracking-[0.25em] text-black-700">
                    DEPARTMENT OF EDUCATION - SCHOOLS DIVISION OF LEYTE
                </p>


                {{-- =====================================================
                    PDMS LOGO
                ====================================================== --}}

                <img
                    src="{{ asset('images/pdms-logo.png') }}"
                    alt="Personnel Database Management System Logo"
                    class="mt-3 h-[380px] w-[380px]
                        object-contain
                        sm:h-[420px] sm:w-[420px]
                        lg:h-[450px] lg:w-[450px]"
                >
            </div>

        </div>


            {{-- =====================================================
                RIGHT SIDE - LOGIN
            ====================================================== --}}
            <div class="flex items-center justify-center bg-white px-8 py-12 sm:px-14 lg:px-16">

                <div class="w-full max-w-md">


                    {{-- TITLE --}}
                    <div class="mb-10">

                        <p class="mb-3 text-sm font-bold uppercase tracking-[0.2em] text-green-700">
                            SCHOOLS DIVISION OF LEYTE - PERSONNEL UNIT
                        </p>

                        <h1 class="text-4xl font-bold leading-tight text-gray-900">
                            Personnel Database
                        </h1>

                        <h2 class="mt-1 text-3xl font-semibold text-black-700">
                            Management System
                        </h2>
                    </div>


                    {{-- SESSION STATUS --}}
                    <x-auth-session-status
                        class="mb-5"
                        :status="session('status')"
                    />


                    {{-- LOGIN FORM --}}
                    <form method="POST" action="{{ route('login') }}">

                        @csrf


                        {{-- EMAIL --}}
                        <div>

                            <x-input-label
                                for="email"
                                :value="__('Email Address')"
                                class="font-medium text-gray-700"
                            />

                            <x-text-input
                                id="email"
                                class="mt-2 block w-full rounded-xl border-gray-300 px-4 py-3.5
                                       focus:border-green-600 focus:ring-green-600"
                                type="email"
                                name="email"
                                :value="old('email')"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="Enter your email address"
                            />

                            <x-input-error
                                :messages="$errors->get('email')"
                                class="mt-2"
                            />

                        </div>


                        {{-- PASSWORD --}}
                        <div class="mt-5">

                            <div class="flex items-center justify-between">

                                <x-input-label
                                    for="password"
                                    :value="__('Password')"
                                    class="font-medium text-gray-700"
                                />

                                @if (Route::has('password.request'))

                                    <a
                                        href="{{ route('password.request') }}"
                                        class="text-sm font-medium text-green-700 hover:text-green-900"
                                    >
                                        Forgot password?
                                    </a>

                                @endif

                            </div>


                            {{-- PASSWORD INPUT WITH SHOW / HIDE --}}
                            <div class="relative mt-2">

                                <x-text-input
                                    id="password"
                                    class="block w-full rounded-xl border-gray-300
                                        px-4 py-3.5 pr-12
                                        focus:border-green-600
                                        focus:ring-green-600"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Enter your password"
                                />


                                {{-- SHOW / HIDE PASSWORD BUTTON --}}
                                <button
                                    type="button"
                                    onclick="toggleLoginPassword()"
                                    class="absolute inset-y-0 right-0
                                        flex w-12 items-center justify-center
                                        text-gray-400
                                        transition
                                        hover:text-green-700
                                        focus:outline-none"
                                    aria-label="Show or hide password"
                                >

                                    {{-- EYE ICON --}}
                                    <svg
                                        id="loginEyeIcon"
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
                                            d="M2.25 12s3.75-6 9.75-6
                                            9.75 6 9.75 6
                                            -3.75 6-9.75 6
                                            S2.25 12 2.25 12z"
                                        />

                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="2.5"
                                        />
                                    </svg>


                                    {{-- EYE OFF ICON --}}
                                    <svg
                                        id="loginEyeOffIcon"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="hidden h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3 3l18 18"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M10.7 10.7a2 2 0 002.6 2.6"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9.9 4.3A9.6 9.6 0 0112 4
                                            c6 0 9.75 8 9.75 8
                                            a17.3 17.3 0 01-3.1 4.1
                                            M6.2 6.2C3.7 8 2.25 12 2.25 12
                                            S6 20 12 20
                                            c1.7 0 3.2-.5 4.5-1.2"
                                        />
                                    </svg>

                                </button>

                            </div>


                            <x-input-error
                                :messages="$errors->get('password')"
                                class="mt-2"
                            />

                        </div>

                        {{-- REMEMBER ME --}}
                        <div class="mt-5 flex items-center">

                            <input
                                id="remember_me"
                                type="checkbox"
                                name="remember"
                                class="h-4 w-4 rounded border-gray-300
                                       text-green-700
                                       focus:ring-green-600"
                            >

                            <label
                                for="remember_me"
                                class="ms-3 text-sm text-gray-600"
                            >
                                Remember me
                            </label>

                        </div>
                        <br>


                        {{-- LOGIN BUTTON --}}
                            <button
                                type="submit"
                                class="mt-7 w-full rounded-xl bg-green-700 px-5 py-4
                                    text-lg font-bold text-white
                                    shadow-lg shadow-green-700/20
                                    transition
                                    hover:bg-green-800
                                    focus:outline-none
                                    focus:ring-2
                                    focus:ring-green-600
                                    focus:ring-offset-2"
                            >
                                Sign In
                            </button>

                    </form>


                    {{-- SECURITY NOTICE --}}
                    <div class="mt-8 rounded-xl border border-green-100 bg-green-50 p-4">

                        <p class="text-center text-xs leading-relaxed text-green-900/70">
                            This system is intended for authorized personnel only.
                            Please keep your account credentials confidential.
                        </p>

                    </div>

                    {{-- =====================================================
                        FOOTER
                    ====================================================== --}}

                    <p class="mt-5 text-xs text-gray-400 text-center">
                        © {{ date('Y') }} Personnel Unit | PDMS | @joviegayo
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>


</x-guest-layout>

<script>
    function toggleLoginPassword() {

        const password =
            document.getElementById('password');

        const eye =
            document.getElementById('loginEyeIcon');

        const eyeOff =
            document.getElementById('loginEyeOffIcon');


        if (password.type === 'password') {

            password.type = 'text';

            eye.classList.add('hidden');

            eyeOff.classList.remove('hidden');

        } else {

            password.type = 'password';

            eye.classList.remove('hidden');

            eyeOff.classList.add('hidden');

        }
    }
</script>