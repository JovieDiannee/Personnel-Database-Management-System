<x-guest-layout>

    <div class="min-h-screen bg-green-900 px-4 py-8">

        <div class="mx-auto flex min-h-screen max-w-5xl items-center justify-center">

            <div
                class="w-full max-w-xl overflow-hidden
                       rounded-3xl bg-white shadow-2xl"
            >

                {{-- =====================================================
                HEADER
                ====================================================== --}}
                <div
                    class="relative bg-green-800
                           px-6 py-8 text-center"
                >

                    {{-- Decorative Circles --}}
                    <div
                        class="absolute -left-16 -top-16
                               h-40 w-40 rounded-full
                               border-[28px] border-white/10"
                    ></div>

                    <div
                        class="absolute -bottom-20 -right-16
                               h-44 w-44 rounded-full
                               border-[30px] border-white/10"
                    ></div>


                    <div class="relative z-10">

                        {{-- LOGO --}}
                        <div
                            class="mx-auto flex h-24 w-24
                                   items-center justify-center
                                   rounded-full bg-white
                                   p-2 shadow-lg"
                        >
                            <img
                                src="{{ asset('images/pdms-favicon.png') }}"
                                alt="PDMS Logo"
                                class="h-full w-full object-contain"
                            >
                        </div>


                        {{-- TITLE --}}
                        <h1
                            class="mt-5 text-2xl
                                   font-bold text-white
                                   sm:text-3xl"
                        >
                            Reset Your Password
                        </h1>


                        {{-- SYSTEM NAME --}}
                        <p
                            class="mt-2 text-sm
                                   font-medium text-green-100"
                        >
                            Personnel Database Management System
                        </p>

                    </div>

                </div>


                {{-- =====================================================
                BODY
                ====================================================== --}}
                <div class="px-6 py-8 sm:px-10">

                    {{-- INTRODUCTION --}}
                    <div class="mb-7 text-center">

                        <p
                            class="mx-auto max-w-md
                                   text-sm leading-6 text-gray-500"
                        >
                            Enter your new password below and confirm it
                            to complete your password reset.
                        </p>

                    </div>


                    {{-- =====================================================
                    VALIDATION ERRORS
                    ====================================================== --}}
                    @if ($errors->any())

                        <div
                            class="mb-6 rounded-xl
                                   border border-red-200
                                   bg-red-50 px-4 py-3"
                        >

                            <p class="text-sm font-bold text-red-800">
                                Please check the following:
                            </p>

                            <ul
                                class="mt-2 list-disc space-y-1
                                       pl-5 text-xs text-red-700"
                            >
                                @foreach ($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach
                            </ul>

                        </div>

                    @endif


                    {{-- =====================================================
                    FORM
                    ====================================================== --}}
                    <form
                        method="POST"
                        action="{{ route('password.store') }}"
                    >

                        @csrf


                        {{-- RESET TOKEN --}}
                        <input
                            type="hidden"
                            name="token"
                            value="{{ $request->route('token') }}"
                        >


                        {{-- =====================================================
                        EMAIL
                        ====================================================== --}}
                        <div>

                            <label
                                for="email"
                                class="mb-2 block
                                       text-sm font-semibold
                                       text-gray-700"
                            >
                                Email Address
                            </label>


                            <div
                                class="flex w-full overflow-hidden
                                       rounded-xl border border-gray-300
                                       bg-gray-50 shadow-sm"
                            >

                                {{-- EMAIL ICON --}}
                                <div
                                    class="flex h-12 w-12 shrink-0
                                           items-center justify-center
                                           border-r border-gray-200
                                           bg-gray-50 text-gray-400"
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
                                            d="M3 6.75A2.25 2.25 0 015.25 4.5h13.5
                                               A2.25 2.25 0 0121 6.75v10.5
                                               a2.25 2.25 0 01-2.25 2.25H5.25
                                               A2.25 2.25 0 013 17.25V6.75z"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3.75 6.75L12 12l8.25-5.25"
                                        />
                                    </svg>

                                </div>


                                {{-- EMAIL INPUT --}}
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email', $request->email) }}"
                                    required
                                    readonly
                                    autocomplete="username"
                                    class="h-12 min-w-0 flex-1
                                           border-0 bg-gray-50 px-4
                                           text-sm text-gray-600
                                           focus:outline-none
                                           focus:ring-0"
                                >

                            </div>

                        </div>


                        {{-- =====================================================
                        NEW PASSWORD
                        ====================================================== --}}
                        <div class="mt-5">

                            <label
                                for="password"
                                class="mb-2 block
                                       text-sm font-semibold
                                       text-gray-700"
                            >
                                New Password
                            </label>


                            <div
                                class="flex w-full overflow-hidden
                                       rounded-xl border border-gray-300
                                       bg-white shadow-sm
                                       focus-within:border-green-600
                                       focus-within:ring-2
                                       focus-within:ring-green-600/20"
                            >

                                {{-- PASSWORD ICON --}}
                                <div
                                    class="flex h-12 w-12 shrink-0
                                           items-center justify-center
                                           border-r border-gray-200
                                           bg-gray-50 text-gray-400"
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
                                            d="M8.25 10.5V7.5
                                               a3.75 3.75 0 117.5 0v3
                                               M6.75 10.5h10.5
                                               A1.75 1.75 0 0119 12.25v7
                                               A1.75 1.75 0 0117.25 21H6.75
                                               A1.75 1.75 0 015 19.25v-7
                                               A1.75 1.75 0 016.75 10.5z"
                                        />
                                    </svg>

                                </div>


                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Enter new password"
                                    class="h-12 min-w-0 flex-1
                                           border-0 bg-white px-4
                                           text-sm text-gray-700
                                           focus:outline-none focus:ring-0"
                                >


                                {{-- SHOW PASSWORD --}}
                                <button
                                    type="button"
                                    onclick="togglePassword('password')"
                                    class="flex h-12 w-12 shrink-0
                                           items-center justify-center
                                           text-gray-400 transition
                                           hover:text-green-700"
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
                                </button>

                            </div>

                        </div>


                        {{-- =====================================================
                        CONFIRM PASSWORD
                        ====================================================== --}}
                        <div class="mt-5">

                            <label
                                for="password_confirmation"
                                class="mb-2 block
                                       text-sm font-semibold
                                       text-gray-700"
                            >
                                Confirm New Password
                            </label>


                            <div
                                class="flex w-full overflow-hidden
                                       rounded-xl border border-gray-300
                                       bg-white shadow-sm
                                       focus-within:border-green-600
                                       focus-within:ring-2
                                       focus-within:ring-green-600/20"
                            >

                                {{-- PASSWORD ICON --}}
                                <div
                                    class="flex h-12 w-12 shrink-0
                                           items-center justify-center
                                           border-r border-gray-200
                                           bg-gray-50 text-gray-400"
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
                                            d="M8.25 10.5V7.5
                                               a3.75 3.75 0 117.5 0v3
                                               M6.75 10.5h10.5
                                               A1.75 1.75 0 0119 12.25v7
                                               A1.75 1.75 0 0117.25 21H6.75
                                               A1.75 1.75 0 015 19.25v-7
                                               A1.75 1.75 0 016.75 10.5z"
                                        />
                                    </svg>

                                </div>


                                <input
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Re-enter new password"
                                    class="h-12 min-w-0 flex-1
                                           border-0 bg-white px-4
                                           text-sm text-gray-700
                                           focus:outline-none focus:ring-0"
                                >


                                {{-- SHOW PASSWORD --}}
                                <button
                                    type="button"
                                    onclick="togglePassword('password_confirmation')"
                                    class="flex h-12 w-12 shrink-0
                                           items-center justify-center
                                           text-gray-400 transition
                                           hover:text-green-700"
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
                                </button>

                            </div>

                        </div>


                        {{-- =====================================================
                        SECURITY NOTE
                        ====================================================== --}}
                        <div
                            class="mt-6 rounded-xl
                                   border border-green-200
                                   bg-green-50 px-4 py-4"
                        >

                            <div class="flex items-start gap-3">

                                <div
                                    class="flex h-8 w-8 shrink-0
                                           items-center justify-center
                                           rounded-full bg-green-100
                                           text-green-700"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 3l7.5 3v5.25
                                               c0 4.67-3.178 8.924-7.5
                                               10.125C7.678 20.174
                                               4.5 15.92 4.5
                                               11.25V6L12 3z"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9.75 12.75l1.5 1.5 3-3"
                                        />
                                    </svg>

                                </div>


                                <div>

                                    <p
                                        class="text-sm font-semibold
                                               text-green-900"
                                    >
                                        Create a Strong Password
                                    </p>

                                    <p
                                        class="mt-1 text-xs
                                               leading-5 text-green-800"
                                    >
                                        Use a password that you do not use
                                        on other accounts and keep your
                                        credentials confidential.
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- =====================================================
                        RESET BUTTON
                        ====================================================== --}}
                        <button
                            type="submit"
                            class="mt-6 flex h-12 w-full
                                   items-center justify-center gap-2
                                   rounded-xl bg-green-700
                                   px-5
                                   text-sm font-bold text-white
                                   shadow-md transition
                                   hover:bg-green-800
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-green-600
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
                                    d="M8.25 10.5V7.5
                                       a3.75 3.75 0 117.5 0v3
                                       M6.75 10.5h10.5
                                       A1.75 1.75 0 0119 12.25v7
                                       A1.75 1.75 0 0117.25 21H6.75
                                       A1.75 1.75 0 015 19.25v-7
                                       A1.75 1.75 0 016.75 10.5z"
                                />
                            </svg>

                            Reset Password

                        </button>

                    </form>


                    {{-- =====================================================
                    BACK TO LOGIN
                    ====================================================== --}}
                    <div class="mt-6 text-center">

                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center gap-2
                                   text-sm font-semibold
                                   text-green-700 transition
                                   hover:text-green-900"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M10.5 19.5L3 12m0
                                       0l7.5-7.5M3 12h18"
                                />
                            </svg>

                            Back to Sign In

                        </a>

                    </div>

                </div>


                {{-- =====================================================
                FOOTER
                ====================================================== --}}
                <div
                    class="border-t border-gray-100
                           bg-gray-50 px-6 py-4
                           text-center"
                >

                    <p class="text-[11px] leading-5 text-gray-400">
                        © {{ date('Y') }}
                        Department of Education - Leyte Division
                        • Personnel Unit • @joviegayo
                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- SHOW/HIDE PASSWORD --}}
    <script>

        function togglePassword(id) {

            const input =
                document.getElementById(id);

            input.type =
                input.type === 'password'
                    ? 'text'
                    : 'password';

        }

    </script>

</x-guest-layout>