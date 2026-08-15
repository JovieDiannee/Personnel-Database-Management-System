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

                    {{-- Profile Picture --}}
                    <div
                        class="relative flex h-20 w-20 shrink-0 items-center
                               justify-center overflow-hidden rounded-2xl
                               bg-white/10 ring-2 ring-green-300/40"
                    >

                        @if(Auth::user()->profile_picture)

                            <img
                                src="{{ asset('storage/' . Auth::user()->profile_picture) }}"
                                alt="{{ Auth::user()->name }}"
                                class="h-full w-full object-cover"
                            >

                        @else

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
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0
                                       3.75 3.75 0 017.5 0z
                                       M4.5 20.25a8.25 8.25 0 0115 0"
                                />
                            </svg>

                        @endif

                    </div>


                    {{-- Profile Information --}}
                    <div>

                        <p
                            class="text-sm font-semibold uppercase
                                   tracking-widest text-green-300"
                        >
                            Account Settings
                        </p>

                        <h1 class="mt-1 text-2xl font-bold sm:text-3xl">
                            {{ Auth::user()->name }}
                        </h1>

                        <p class="mt-1 text-sm text-green-100">
                            Manage your profile picture and account password.
                        </p>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                PROFILE PICTURE
            ====================================================== --}}
            <div
                class="mb-6 overflow-hidden rounded-2xl bg-white
                       shadow-sm ring-1 ring-gray-100"
            >

                <div
                    class="border-b border-green-100 bg-green-50
                           px-6 py-4"
                >

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
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4.5 7.5A2.25 2.25 0 016.75
                                       5.25h10.5A2.25 2.25 0 0119.5
                                       7.5v9a2.25 2.25 0 01-2.25
                                       2.25H6.75A2.25 2.25 0 014.5
                                       16.5v-9z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M8.25 10.5a1.5 1.5 0 113
                                       0 1.5 1.5 0 01-3 0z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4.5 15l3.75-3.75a1.5 1.5
                                       0 012.121 0L15 15.75l1.5-1.5a1.5
                                       1.5 0 012.121 0L19.5 15"
                                />
                            </svg>

                        </div>


                        <div>

                            <h2 class="font-bold text-gray-800">
                                Profile Picture
                            </h2>

                            <p class="text-sm text-gray-500">
                                Update your account profile picture.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="p-6 sm:p-8">

                    <form
                        method="POST"
                        action="{{ route('profile.picture.update') }}"
                        enctype="multipart/form-data"
                    >

                        @csrf

                        <div class="flex flex-col gap-8 sm:flex-row
                                    sm:items-center">

                            {{-- Current Picture --}}
                            <div class="shrink-0">

                                <div
                                    class="flex h-32 w-32 items-center
                                           justify-center overflow-hidden
                                           rounded-2xl border-4 border-green-100
                                           bg-green-50 shadow-sm"
                                >

                                    @if(Auth::user()->profile_picture)

                                        <img
                                            id="profile-preview"
                                            src="{{ asset('storage/' . Auth::user()->profile_picture) }}"
                                            alt="{{ Auth::user()->name }}"
                                            class="h-full w-full object-cover"
                                        >

                                    @else

                                        <div
                                            id="profile-placeholder"
                                            class="flex h-full w-full items-center
                                                   justify-center"
                                        >

                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-14 w-14 text-green-700"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="1.5"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M15.75 6a3.75 3.75
                                                       0 11-7.5 0 3.75 3.75
                                                       0 017.5 0z
                                                       M4.5 20.25a8.25 8.25
                                                       0 0115 0"
                                                />
                                            </svg>

                                        </div>

                                    @endif

                                </div>

                            </div>


                            {{-- Upload --}}
                            <div class="flex flex-1 flex-col items-center text-center">

                                {{-- Label --}}
                                <label
                                    for="profile_picture"
                                    class="mb-3 block text-sm font-semibold text-gray-700"
                                >
                                    Choose New Profile Picture
                                </label>


                                {{-- CUSTOM FILE INPUT --}}
                                <div class="relative flex h-10 w-full max-w-xl">

                                    {{-- REAL FILE INPUT --}}
                                    <input
                                        type="file"
                                        id="profile_picture"
                                        name="profile_picture"
                                        accept=".jpg,.jpeg,.png,.webp"
                                        required
                                        class="absolute inset-0 z-10 h-full w-full
                                            cursor-pointer opacity-0"
                                        onchange="
                                            document.getElementById('profile-picture-name').textContent =
                                                this.files.length
                                                    ? this.files[0].name
                                                    : 'No file selected'
                                        "
                                    >


                                    {{-- CUSTOM FILE DISPLAY --}}
                                    <div
                                        class="flex h-full w-full items-center overflow-hidden
                                            rounded-lg border border-gray-300
                                            bg-white text-left shadow-sm"
                                    >

                                        {{-- BROWSE BUTTON --}}
                                        <span
                                            class="flex h-full shrink-0 items-center
                                                border-r border-green-200
                                                bg-green-50 px-5
                                                text-sm font-semibold
                                                text-green-700"
                                        >
                                            Browse...
                                        </span>


                                        {{-- FILE NAME --}}
                                        <span
                                            id="profile-picture-name"
                                            class="truncate px-4 text-sm text-gray-500"
                                        >
                                            No file selected
                                        </span>

                                    </div>

                                </div>


                                {{-- File Information --}}
                                <p class="mt-2 text-center text-xs text-gray-500">
                                    JPG, JPEG, PNG, or WEBP. Maximum file size: 2 MB.
                                </p>


                                {{-- Validation Error --}}
                                @error('profile_picture')

                                    <p class="mt-2 text-center text-sm text-red-600">
                                        {{ $message }}
                                    </p>

                                @enderror


                                {{-- Success Message --}}
                                @if(session('status') === 'profile-picture-updated')

                                    <p class="mt-3 text-center text-sm font-medium text-green-700">
                                        Profile picture updated successfully.
                                    </p>

                                @endif


                                {{-- Update Button --}}
                                <button
                                    type="submit"
                                    class="mt-5 inline-flex items-center justify-center gap-2
                                        rounded-lg bg-green-700 px-6 py-2.5
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
                                            d="M3 16.5V21h4.5L18.81
                                            9.69a1.5 1.5 0 000-2.12l-2.38-2.38
                                            a1.5 1.5 0 00-2.12 0L3
                                            16.5z"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M13.5 6.75l3.75 3.75"
                                        />
                                    </svg>

                                    Update Profile Picture

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>


            {{-- =====================================================
                UPDATE PASSWORD
            ====================================================== --}}
            <div
                class="overflow-hidden rounded-2xl bg-white
                       shadow-sm ring-1 ring-gray-100"
            >

                <div
                    class="border-b border-green-100 bg-green-50
                           px-6 py-4"
                >

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
                                    d="M16.5 10.5V6.75a4.5 4.5
                                       0 00-9 0v3.75m-.75 0h10.5A2.25
                                       2.25 0 0119.5 12.75v6A2.25 2.25
                                       0 0117.25 21h-10.5a2.25 2.25
                                       0 01-2.25-2.25v-6A2.25 2.25
                                       0 014.5 10.5z"
                                />
                            </svg>

                        </div>


                        <div>

                            <h2 class="font-bold text-gray-800">
                                Update Password
                            </h2>

                            <p class="text-sm text-gray-500">
                                Change your account password to keep your
                                account secure.
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

        </div>

    </div>

</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const input = document.getElementById('profile_picture');

        if (!input) return;

        input.addEventListener('change', function (event) {

            const file = event.target.files[0];

            if (!file) return;

            const reader = new FileReader();

            reader.onload = function (e) {

                let preview = document.getElementById('profile-preview');

                const placeholder = document.getElementById('profile-placeholder');

                if (!preview) {

                    preview = document.createElement('img');

                    preview.id = 'profile-preview';

                    preview.className =
                        'h-full w-full object-cover';

                    const container = placeholder.parentElement;

                    placeholder.remove();

                    container.appendChild(preview);
                }

                preview.src = e.target.result;
            };

            reader.readAsDataURL(file);
        });

    });
</script>