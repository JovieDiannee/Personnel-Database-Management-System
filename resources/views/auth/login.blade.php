<x-guest-layout>


<div class="min-h-screen bg-gradient-to-br from-green-900 via-green-800 to-emerald-700">

    <div class="mx-auto flex min-h-screen max-w-7xl items-center justify-center px-6 py-10">

        <div class="grid w-full max-w-6xl overflow-hidden rounded-3xl bg-white shadow-2xl lg:grid-cols-2">


            {{-- =====================================================
            LEFT SIDE - LOGO
            ====================================================== --}}

            <div class="relative flex h-full items-center justify-center overflow-hidden bg-white-800 px-8 lg:w-2/5">

                {{-- Decorative Circle - Top Left --}}
                <div
                    class="absolute -left-32 -top-32
                        h-[420px] w-[420px]
                        rounded-full
                        border-[45px]
                        border-white/5"
                ></div>

                {{-- Decorative Circle - Bottom Right --}}
                <div
                    class="absolute -bottom-40 -right-40
                        h-[500px] w-[500px]
                        rounded-full
                        border-[50px]
                        border-white/5"
                ></div>

                {{-- Small Decorative Circle --}}
                <div
                    class="absolute left-1/4 top-1/4
                        h-24 w-24
                        rounded-full
                        bg-white/5
                        blur-sm"
                ></div>


                {{-- LOGO CONTENT --}}
                <div class="relative z-10 flex flex-col items-center text-center">

                    {{-- Logo --}}
                    <div class="flex items-center justify-center">

                        <img
                            src="{{ asset('images/pdms-logo.png') }}"
                            alt="Personnel Database Management System Logo"
                            class="h-64 w-64
                                object-contain
                                sm:h-72 sm:w-72
                                lg:h-[360px] lg:w-[360px]"
                        >

                    </div>

                    {{-- FOOTER --}}
                    <p class="mt-8 text-center text-xs text-gray-400">
                        © {{ date('Y') }} Personnel Database Management System | @joviegayo
                    </p>

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
                            Department of Education - Schools Division of Leyte
                        </p>

                        <h1 class="text-4xl font-bold leading-tight text-gray-900">
                            Personnel Database
                        </h1>

                        <h2 class="mt-1 text-3xl font-semibold text-black-700">
                            Management System
                        </h2>

                        <br>
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


                            <x-text-input
                                id="password"
                                class="mt-2 block w-full rounded-xl border-gray-300 px-4 py-3.5
                                       focus:border-green-600 focus:ring-green-600"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Enter your password"
                            />

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

                </div>

            </div>

        </div>

    </div>

</div>


</x-guest-layout>

