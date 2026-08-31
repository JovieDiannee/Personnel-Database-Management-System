<section id="password">

    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Update Password
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Ensure your account is using a secure password.
        </p>
    </header>


    {{-- =====================================================
        SUCCESS NOTIFICATION
    ====================================================== --}}
    @if (session('status') === 'password-updated')

        <div
            class="mt-5 flex items-start gap-3 rounded-lg
                   border border-green-200 bg-green-50
                   px-4 py-3 text-sm text-green-800"
        >

            {{-- Success Icon --}}
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="mt-0.5 h-5 w-5 shrink-0 text-green-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9 12.75L11.25 15 15 9.75"
                />

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 3l7.5 3v5.25c0 4.75-3.2 8.65-7.5 9.75
                       -4.3-1.1-7.5-5-7.5-9.75V6L12 3z"
                />
            </svg>


            <div>
                <p class="font-semibold">
                    Password Updated Successfully
                </p>

                <p class="mt-0.5 text-green-700">
                    Your password has been changed successfully.
                </p>
            </div>

        </div>

    @endif


    {{-- =====================================================
        VALIDATION ERRORS
    ====================================================== --}}
    @if ($errors->updatePassword->any())

        <div
            class="mt-5 rounded-lg border border-red-200
                   bg-red-50 px-4 py-3 text-sm text-red-700"
        >

            <div class="flex items-start gap-3">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="mt-0.5 h-5 w-5 shrink-0 text-red-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 9v3.75"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 16.5h.01"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M10.29 3.86L1.82 18a2 2 0
                           001.71 3h16.94a2 2 0
                           001.71-3L13.71 3.86a2 2
                           0 00-3.42 0z"
                    />
                </svg>


                <div>

                    <p class="font-semibold">
                        Password Update Failed
                    </p>

                    <ul class="mt-1 list-disc space-y-1 pl-5">

                        @foreach ($errors->updatePassword->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- =====================================================
        PASSWORD FORM
    ====================================================== --}}
    <form
        method="post"
        action="{{ route('password.update') }}#password"
        class="mt-6 space-y-6"
    >

        @csrf

        @method('put')


        {{-- =====================================================
            CURRENT PASSWORD
        ====================================================== --}}
        <div>

            <x-input-label
                for="current_password"
                :value="__('Current Password')"
            />


            <div class="relative mt-1">

                <x-text-input
                    id="current_password"
                    name="current_password"
                    type="password"
                    class="block w-full pr-12"
                    autocomplete="current-password"
                />


                {{-- Eye Button --}}
                <button
                    type="button"
                    onclick="togglePassword(
                        'current_password',
                        'currentPasswordEye',
                        'currentPasswordEyeOff'
                    )"
                    class="absolute inset-y-0 right-0
                           flex w-12 items-center justify-center
                           text-gray-400
                           transition
                           hover:text-green-700
                           focus:outline-none"
                >

                    {{-- Eye --}}
                    <svg
                        id="currentPasswordEye"
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


                    {{-- Eye Off --}}
                    <svg
                        id="currentPasswordEyeOff"
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
                :messages="$errors->updatePassword->get('current_password')"
                class="mt-2"
            />

        </div>


        {{-- =====================================================
            NEW PASSWORD
        ====================================================== --}}
        <div>

            <x-input-label
                for="password"
                :value="__('New Password')"
            />


            <div class="relative mt-1">

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-full pr-12"
                    autocomplete="new-password"
                />


                {{-- Eye Button --}}
                <button
                    type="button"
                    onclick="togglePassword(
                        'password',
                        'passwordEye',
                        'passwordEyeOff'
                    )"
                    class="absolute inset-y-0 right-0
                           flex w-12 items-center justify-center
                           text-gray-400
                           transition
                           hover:text-green-700
                           focus:outline-none"
                >

                    {{-- Eye --}}
                    <svg
                        id="passwordEye"
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


                    {{-- Eye Off --}}
                    <svg
                        id="passwordEyeOff"
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
                :messages="$errors->updatePassword->get('password')"
                class="mt-2"
            />

        </div>


        {{-- =====================================================
            CONFIRM PASSWORD
        ====================================================== --}}
        <div>

            <x-input-label
                for="password_confirmation"
                :value="__('Confirm Password')"
            />


            <div class="relative mt-1">

                <x-text-input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    class="block w-full pr-12"
                    autocomplete="new-password"
                />


                {{-- Eye Button --}}
                <button
                    type="button"
                    onclick="togglePassword(
                        'password_confirmation',
                        'confirmPasswordEye',
                        'confirmPasswordEyeOff'
                    )"
                    class="absolute inset-y-0 right-0
                           flex w-12 items-center justify-center
                           text-gray-400
                           transition
                           hover:text-green-700
                           focus:outline-none"
                >

                    {{-- Eye --}}
                    <svg
                        id="confirmPasswordEye"
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


                    {{-- Eye Off --}}
                    <svg
                        id="confirmPasswordEyeOff"
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
                :messages="$errors->updatePassword->get('password_confirmation')"
                class="mt-2"
            />

        </div>


        {{-- =====================================================
            SUBMIT
        ====================================================== --}}
        <div class="flex items-center gap-4">

            <button
                type="submit"
                class="inline-flex items-center gap-2
                       rounded-lg bg-green-700 px-5 py-2.5
                       text-sm font-semibold text-white
                       shadow-sm transition
                       hover:bg-green-800
                       focus:outline-none
                       focus:ring-2
                       focus:ring-green-500
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
                        d="M16.5 10.5V6.75a4.5 4.5
                           0 00-9 0v3.75"
                    />

                    <rect
                        width="15"
                        height="10"
                        x="4.5"
                        y="10.5"
                        rx="2.25"
                    />
                </svg>

                Update Password

            </button>

        </div>

    </form>


    {{-- =====================================================
        SHOW / HIDE PASSWORD SCRIPT
    ====================================================== --}}
    <script>

        function togglePassword(
            inputId,
            eyeId,
            eyeOffId
        ) {

            const input =
                document.getElementById(inputId);

            const eye =
                document.getElementById(eyeId);

            const eyeOff =
                document.getElementById(eyeOffId);


            if (input.type === 'password') {

                input.type = 'text';

                eye.classList.add('hidden');

                eyeOff.classList.remove('hidden');

            } else {

                input.type = 'password';

                eye.classList.remove('hidden');

                eyeOff.classList.add('hidden');

            }

        }

    </script>

</section>