<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-green-900 via-green-800 to-emerald-700">
        <div class="mx-auto flex min-h-screen items-center justify-center px-4 py-10 sm:px-6">
            <div class="w-full max-w-xl overflow-hidden rounded-3xl bg-white shadow-2xl">
                <div class="px-8 py-10 sm:px-14 sm:py-12">
                    <div class="mx-auto w-full max-w-md">

                        {{-- LOGO AND TITLE --}}
                        <div class="mb-9 text-center">
                            <img
                                src="{{ asset('images/pdms-logo.png') }}"
                                alt="Personnel Database Management System Logo"
                                class="mx-auto mb-5 h-32 w-32 object-contain sm:h-40 sm:w-40"
                            >

                            <h3 class="mb-3 text-center text-sm font-bold uppercase tracking-[0.12em] text-green-700 sm:text-xl">
                                SCHOOLS DIVISION OF LEYTE - PERSONNEL UNIT
                            </h3>

                            <h1 class="text-3xl font-bold leading-tight text-gray-900 sm:text-4xl">
                                Personnel Database
                            </h1>

                            <h2 class="mt-1 text-2xl font-semibold text-gray-900 sm:text-3xl">
                                Management System
                            </h2>
                        </div>
                        <br>

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
                                    class="mt-2 block w-full rounded-xl border-gray-300 px-4 py-3.5 focus:border-green-600 focus:ring-green-600"
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

                                <div class="relative mt-2">
                                    <x-text-input
                                        id="password"
                                        class="block w-full rounded-xl border-gray-300 px-4 py-3.5 pr-12 focus:border-green-600 focus:ring-green-600"
                                        type="password"
                                        name="password"
                                        required
                                        autocomplete="current-password"
                                        placeholder="Enter your password"
                                    />

                                    <button
                                        type="button"
                                        onclick="toggleLoginPassword()"
                                        class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-gray-400 transition hover:text-green-700 focus:outline-none"
                                        aria-label="Show or hide password"
                                    >
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
                                                d="M2.25 12s3.75-6 9.75-6 9.75 6 9.75 6-3.75 6-9.75 6S2.25 12 2.25 12z"
                                            />
                                            <circle cx="12" cy="12" r="2.5" />
                                        </svg>

                                        <svg
                                            id="loginEyeOffIcon"
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="hidden h-5 w-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.7 10.7a2 2 0 002.6 2.6" />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M9.9 4.3A9.6 9.6 0 0112 4c6 0 9.75 8 9.75 8a17.3 17.3 0 01-3.1 4.1M6.2 6.2C3.7 8 2.25 12 2.25 12S6 20 12 20c1.7 0 3.2-.5 4.5-1.2"
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
                                    class="h-4 w-4 rounded border-gray-300 text-green-700 focus:ring-green-600"
                                >
                                <label for="remember_me" class="ms-3 text-sm text-gray-600">
                                    Remember me
                                </label>
                            </div>

                            <button
                                type="submit"
                                class="mt-7 w-full rounded-xl bg-green-700 px-5 py-4 text-lg font-bold text-white shadow-lg shadow-green-700/20 transition hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2"
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

                        <p class="mt-5 text-center text-xs text-gray-400">
                            © {{ date('Y') }} Personnel Unit | PDMS | Jovelyn C. Gayo
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

<script>
    function toggleLoginPassword() {
        const password = document.getElementById('password');
        const eye = document.getElementById('loginEyeIcon');
        const eyeOff = document.getElementById('loginEyeOffIcon');

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
