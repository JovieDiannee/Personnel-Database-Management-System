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

                    {{-- Decorative Circle --}}
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
                            Forgot Your Password?
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
                            Enter your registered email address below.
                            We will send you a secure link that allows you
                            to create a new password.
                        </p>

                    </div>


                    {{-- =====================================================
                    SUCCESS MESSAGE
                    ====================================================== --}}
                    @if(session('status'))

                        <div
                            class="mb-6 flex items-start gap-3
                                   rounded-xl border border-green-200
                                   bg-green-50 px-4 py-3"
                        >

                            <div
                                class="flex h-9 w-9 shrink-0
                                       items-center justify-center
                                       rounded-full bg-green-600
                                       text-white"
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2.5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>

                            </div>


                            <div>

                                <p class="text-sm font-bold text-green-900">
                                    Reset Link Sent
                                </p>

                                <p class="mt-1 text-xs text-green-700">
                                    {{ session('status') }}
                                </p>

                            </div>

                        </div>

                    @endif


                    {{-- =====================================================
                    FORM
                    ====================================================== --}}
                    <form
                        method="POST"
                        action="{{ route('password.email') }}"
                    >

                        @csrf


                        {{-- EMAIL --}}
                        <div>

                            <label
                                for="email"
                                class="mb-2 block
                                       text-sm font-semibold
                                       text-gray-700"
                            >
                                Email Address
                            </label>


                            <div class="flex w-full overflow-hidden rounded-xl border border-gray-300 bg-white shadow-sm
                                        focus-within:border-green-600 focus-within:ring-2 focus-within:ring-green-600/20">

                                {{-- EMAIL ICON --}}
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center bg-gray-50 text-gray-400">

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
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="email"
                                    placeholder="Enter your email address"
                                    class="h-12 min-w-0 flex-1 border-0 bg-white px-4
                                        text-sm text-gray-700
                                        focus:outline-none focus:ring-0"
                                >

                            </div>


                            @error('email')

                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- =====================================================
                        SUBMIT BUTTON
                        ====================================================== --}}
                        <button
                            type="submit"
                            class="mt-6 flex h-12 w-full
                                   items-center justify-center gap-2
                                   rounded-xl bg-green-700
                                   px-5
                                   text-sm font-bold text-white
                                   shadow-md
                                   transition
                                   hover:bg-green-800
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-green-600
                                   focus:ring-offset-2"
                        >

                            {{-- SEND ICON --}}
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
                                    d="M6 12L3.269 3.125A59.769
                                       59.769 0 0121.485 12
                                       59.768 59.768 0
                                       013.27 20.875L5.999
                                       12zm0 0h7.5"
                                />
                            </svg>

                            Email Password Reset Link

                        </button>

                    </form>


                    {{-- =====================================================
                    HELP
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
                                    Didn't receive the email?
                                </p>

                                <p
                                    class="mt-1 text-xs
                                           leading-5 text-green-800"
                                >
                                    Check your Spam or Junk folder.
                                    If you still cannot find the email,
                                    please contact the Personnel Unit.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- =====================================================
                    BACK
                    ====================================================== --}}
                    <div class="mt-6 text-center">

                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center gap-2
                                   text-sm font-semibold
                                   text-green-700
                                   transition
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

</x-guest-layout>