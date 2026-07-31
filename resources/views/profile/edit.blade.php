<x-app-layout>

    <div class="min-h-screen bg-gray-50 py-10">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


            {{-- =====================================================
                PROFILE HEADER
            ====================================================== --}}
            <div
                class="relative mb-8 overflow-hidden rounded-2xl
                       bg-gradient-to-br from-green-950 via-green-900 to-green-800
                       px-6 py-8 text-white shadow-lg"
            >

                {{-- Decorative Background --}}
                <div
                    class="absolute -right-16 -top-16 h-48 w-48 rounded-full
                           bg-green-700/30"
                ></div>

                <div
                    class="absolute -bottom-20 -left-16 h-48 w-48 rounded-full
                           bg-green-600/20"
                ></div>


                <div class="relative flex items-center gap-5">

                    {{-- Profile Icon --}}
                    <div
                        class="flex h-20 w-20 items-center justify-center
                               rounded-2xl bg-white/10
                               ring-2 ring-green-300/40"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-10 w-10 text-green-200"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.5"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 0115 0"
                            />

                        </svg>

                    </div>


                    {{-- Profile Information --}}
                    <div>

                        <p class="text-sm font-semibold uppercase tracking-widest text-green-300">
                            Account Settings
                        </p>

                        <h1 class="mt-1 text-2xl font-bold sm:text-3xl">
                            {{ Auth::user()->name }}
                        </h1>

                        <p class="mt-1 text-sm text-green-100">
                            Manage your personal information, password, and account settings.
                        </p>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                PROFILE INFORMATION
            ====================================================== --}}
            <div class="mb-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">

                <div class="border-b border-green-100 bg-green-50 px-6 py-4">

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 items-center justify-center
                                   rounded-lg bg-green-700 text-white"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 0115 0"
                                />

                            </svg>

                        </div>


                        <div>

                            <h2 class="font-bold text-gray-800">
                                Profile Information
                            </h2>

                            <p class="text-sm text-gray-500">
                                Update your name and email address.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="p-6 sm:p-8">

                    <div class="max-w-2xl">

                        @include('profile.partials.update-profile-information-form')

                    </div>

                </div>

            </div>


            {{-- =====================================================
                UPDATE PASSWORD
            ====================================================== --}}
            <div class="mb-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">

                <div class="border-b border-green-100 bg-green-50 px-6 py-4">

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 items-center justify-center
                                   rounded-lg bg-green-700 text-white"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M16.5 10.5V6.75a4.5 4.5 0 00-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0119.5 12.75v6A2.25 2.25 0 0117.25 21h-10.5a2.25 2.25 0 01-2.25-2.25v-6A2.25 2.25 0 014.5 10.5z"
                                />

                            </svg>

                        </div>


                        <div>

                            <h2 class="font-bold text-gray-800">
                                Update Password
                            </h2>

                            <p class="text-sm text-gray-500">
                                Ensure your account is using a secure password.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="p-6 sm:p-8">

                    <div class="max-w-2xl">

                        @include('profile.partials.update-password-form')

                    </div>

                </div>

            </div>


            {{-- =====================================================
                DELETE ACCOUNT
            ====================================================== --}}
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-red-100">

                <div class="border-b border-red-100 bg-red-50 px-6 py-4">

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 items-center justify-center
                                   rounded-lg bg-red-600 text-white"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M6 7h12m-9 4v6m6-6v6M9 7V4h6v3m-8 0l1 13h8l1-13"
                                />

                            </svg>

                        </div>


                        <div>

                            <h2 class="font-bold text-gray-800">
                                Delete Account
                            </h2>

                            <p class="text-sm text-gray-500">
                                Permanently delete your account and all associated data.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="p-6 sm:p-8">

                    <div class="max-w-2xl">

                        @include('profile.partials.delete-user-form')

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>