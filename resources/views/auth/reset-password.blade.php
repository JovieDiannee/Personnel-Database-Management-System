<x-guest-layout>

    <div class="min-h-screen bg-gradient-to-br from-green-950 via-green-900 to-emerald-800 px-4 py-8">

        <div class="mx-auto flex min-h-screen max-w-6xl items-center justify-center">

            <div
                class="grid w-full overflow-hidden rounded-3xl
                       bg-white shadow-2xl
                       lg:grid-cols-[42%_58%]"
            >

                {{-- =====================================================
                LEFT SIDE
                ====================================================== --}}
                <div
                    class="relative hidden overflow-hidden
                           bg-gradient-to-br from-green-800 to-emerald-700
                           px-10 py-12 text-white
                           lg:flex lg:flex-col lg:justify-between"
                >

                    {{-- Decorative Circles --}}
                    <div
                        class="absolute -left-28 -top-28
                               h-80 w-80 rounded-full
                               border-[40px] border-white/10"
                    ></div>

                    <div
                        class="absolute -bottom-32 -right-28
                               h-96 w-96 rounded-full
                               border-[45px] border-white/10"
                    ></div>


                    {{-- Brand --}}
                    <div class="relative z-10">

                        <img
                            src="{{ asset('images/pdms-logo.png') }}"
                            alt="PDMS Logo"
                            class="h-28 w-28 object-contain"
                        >

                        <p
                            class="mt-8 text-xs font-bold uppercase
                                   tracking-[0.25em] text-green-200"
                        >
                            Department of Education
                        </p>

                        <h2 class="mt-3 text-3xl font-bold leading-tight">
                            Personnel Database
                            Management System
                        </h2>

                        <p class="mt-4 max-w-sm text-sm leading-6 text-green-100">
                            Create a new password for your account to continue
                            accessing the Personnel Database Management System.
                        </p>

                    </div>


                    {{-- Footer --}}
                    <p class="relative z-10 text-xs text-green-100/70">
                        © {{ date('Y') }} DepEd Leyte Division • Personnel Unit
                    </p>

                </div>


                {{-- =====================================================
                RIGHT SIDE
                ====================================================== --}}
                <div class="flex items-center justify-center px-6 py-10 sm:px-10 lg:px-14">

                    <div class="w-full max-w-lg">


                        {{-- ICON --}}
                        <div
                            class="mb-6 flex h-14 w-14 items-center justify-center
                                   rounded-2xl bg-green-100 text-green-700"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-7 w-7"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15.75 5.25a3.75 3.75 0 11-7.5 0
                                       3.75 3.75 0 017.5 0z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 9v3m0 0l-2 2m2-2l2 2M6 21h12
                                       a2 2 0 002-2v-5a2 2 0 00-2-2H6
                                       a2 2 0 00-2 2v5a2 2 0 002 2z"
                                />
                            </svg>

                        </div>


                        {{-- TITLE --}}
                        <div class="mb-8">

                            <p
                                class="text-xs font-bold uppercase
                                       tracking-[0.2em] text-green-700"
                            >
                                Account Security
                            </p>

                            <h1 class="mt-2 text-3xl font-bold text-gray-900">
                                Reset Your Password
                            </h1>

                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Enter and confirm your new password below.
                            </p>

                        </div>


                        {{-- VALIDATION ERRORS --}}
                        @if ($errors->any())

                            <div
                                class="mb-6 rounded-xl border border-red-200
                                       bg-red-50 px-4 py-3"
                            >
                                <ul class="list-disc pl-5 text-sm text-red-700">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>

                        @endif


                        {{-- RESET FORM --}}
                        <form method="POST" action="{{ route('password.store') }}">

                            @csrf

                            {{-- Password Reset Token --}}
                            <input
                                type="hidden"
                                name="token"
                                value="{{ $request->route('token') }}"
                            >


                            {{-- EMAIL --}}
                            <div>

                                <label
                                    for="email"
                                    class="mb-2 block text-sm font-semibold text-gray-700"
                                >
                                    Email Address
                                </label>

                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email', $request->email) }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    readonly
                                    class="block w-full rounded-xl
                                           border border-gray-300
                                           bg-gray-50 px-4 py-3.5
                                           text-sm text-gray-700
                                           shadow-sm
                                           focus:border-green-600
                                           focus:ring-green-600"
                                >

                            </div>


                            {{-- PASSWORD --}}
                            <div class="mt-5">

                                <label
                                    for="password"
                                    class="mb-2 block text-sm font-semibold text-gray-700"
                                >
                                    New Password
                                </label>

                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Enter new password"
                                    class="block w-full rounded-xl
                                           border border-gray-300
                                           px-4 py-3.5
                                           text-sm
                                           shadow-sm
                                           focus:border-green-600
                                           focus:ring-green-600"
                                >

                            </div>


                            {{-- CONFIRM PASSWORD --}}
                            <div class="mt-5">

                                <label
                                    for="password_confirmation"
                                    class="mb-2 block text-sm font-semibold text-gray-700"
                                >
                                    Confirm Password
                                </label>

                                <input
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Re-enter new password"
                                    class="block w-full rounded-xl
                                           border border-gray-300
                                           px-4 py-3.5
                                           text-sm
                                           shadow-sm
                                           focus:border-green-600
                                           focus:ring-green-600"
                                >

                            </div>


                            {{-- PASSWORD NOTE --}}
                            <div
                                class="mt-5 rounded-xl border border-green-100
                                       bg-green-50 px-4 py-3"
                            >

                                <p class="text-xs leading-5 text-green-800">
                                    Use a strong password that you do not use on other accounts.
                                </p>

                            </div>


                            {{-- SUBMIT --}}
                            <button
                                type="submit"
                                class="mt-7 flex w-full items-center justify-center gap-2
                                       rounded-xl bg-green-700
                                       px-5 py-3.5
                                       text-base font-bold text-white
                                       shadow-lg shadow-green-700/20
                                       transition
                                       hover:bg-green-800
                                       focus:outline-none
                                       focus:ring-2 focus:ring-green-600
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
                                        d="M16.5 10.5V6.75a4.5 4.5 0 00-9 0v3.75
                                           M6.75 10.5h10.5A1.75 1.75 0 0119
                                           12.25v7A1.75 1.75 0 0117.25 21H6.75
                                           A1.75 1.75 0 015 19.25v-7
                                           A1.75 1.75 0 016.75 10.5z"
                                    />
                                </svg>

                                Reset Password

                            </button>

                        </form>


                        {{-- BACK TO LOGIN --}}
                        <div class="mt-6 text-center">

                            <a
                                href="{{ route('login') }}"
                                class="text-sm font-semibold text-green-700
                                       hover:text-green-900"
                            >
                                ← Back to Sign In
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-guest-layout>