<section>

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
        action="{{ route('password.update') }}"
        class="mt-6 space-y-6"
    >

        @csrf

        @method('put')


        {{-- Current Password --}}
        <div>

            <x-input-label
                for="current_password"
                :value="__('Current Password')"
            />

            <x-text-input
                id="current_password"
                name="current_password"
                type="password"
                class="mt-1 block w-full"
                autocomplete="current-password"
            />

            <x-input-error
                :messages="$errors->updatePassword->get('current_password')"
                class="mt-2"
            />

        </div>


        {{-- New Password --}}
        <div>

            <x-input-label
                for="password"
                :value="__('New Password')"
            />

            <x-text-input
                id="password"
                name="password"
                type="password"
                class="mt-1 block w-full"
                autocomplete="new-password"
            />

            <x-input-error
                :messages="$errors->updatePassword->get('password')"
                class="mt-2"
            />

        </div>


        {{-- Confirm Password --}}
        <div>

            <x-input-label
                for="password_confirmation"
                :value="__('Confirm Password')"
            />

            <x-text-input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                class="mt-1 block w-full"
                autocomplete="new-password"
            />

            <x-input-error
                :messages="$errors->updatePassword->get('password_confirmation')"
                class="mt-2"
            />

        </div>


        {{-- Submit --}}
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

</section>