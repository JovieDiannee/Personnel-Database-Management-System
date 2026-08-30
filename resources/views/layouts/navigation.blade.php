<nav
    class="sticky top-0 z-40
           h-16
           border-green-700/50
           bg-gradient-to-br from-green-950 via-green-900 to-green-800
           text-white shadow-md"
>

    <div
        class="flex h-full items-center
               justify-end px-6"
    >

        <div class="flex items-center gap-5">


            {{-- =================================================
                NOTIFICATION
            ================================================== --}}

            <button
                type="button"
                class="relative flex h-10 w-10
                       items-center justify-center
                       rounded-lg text-white
                       transition
                       hover:bg-green-50
                       hover:text-green-700"
                >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 17h5l-1.405-1.405A2.032
                           2.032 0 0118 14.158V11a6.002
                           6.002 0 00-4-5.659V5a2 2
                           0 10-4 0v.341C7.67 6.165
                           6 8.388 6 11v3.159c0
                           .538-.214 1.055-.595
                           1.436L4 17h5"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M13.73 21a2 2 0 01-3.46 0"
                    />

                </svg>


                <span
                    class="absolute right-1 top-1
                           h-2.5 w-2.5 rounded-full
                           bg-green-600 ring-2 ring-white"
                ></span>

            </button>


            {{-- =================================================
                PROFILE DROPDOWN
            ================================================== --}}

            <div
                class="relative"
                x-data="{ open: false }"
                @click.outside="open = false"
                >


                {{-- =================================================
                    PROFILE BUTTON
                ================================================== --}}

                <button
                    type="button"
                    @click="open = !open"
                    class="group flex items-center gap-3 rounded-xl px-2 py-1.5
                        transition hover:bg-green-50
                        focus:outline-none focus:ring-2 focus:ring-green-500
                        focus:ring-offset-2"
                >
                    {{-- PROFILE PICTURE --}}
                    @if (Auth::user()->profile_picture)
                        <img
                            src="{{ asset('storage/' . Auth::user()->profile_picture) }}"
                            alt="{{ Auth::user()->name }}"
                            class="h-10 w-10 rounded-full object-cover ring-2 ring-green-200"
                        >
                    @else
                        <div
                            class="flex h-10 w-10 items-center justify-center
                                rounded-full bg-green-100 text-green-700
                                ring-2 ring-green-200"
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
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0
                                    3.75 3.75 0 017.5 0z
                                    M4.5 20.25a8.25 8.25 0 0115 0"
                                />
                            </svg>
                        </div>
                    @endif

                    {{-- USER DETAILS --}}
                    <div class="hidden text-right sm:block">
                        <p
                            class="max-w-48 truncate text-sm font-semibold text-white
                                transition-colors group-hover:text-green-700"
                        >
                            {{ Auth::user()->name }}
                        </p>

                        <p
                            class="max-w-48 truncate text-xs text-white
                                transition-colors group-hover:text-green-600"
                        >
                            {{ Auth::user()->email }}
                        </p>
                    </div>

                    {{-- ARROW --}}
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 text-gray-300 transition duration-200
                            group-hover:text-green-700"
                        :class="{ 'rotate-180': open }"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 9l6 6 6-6"
                        />
                    </svg>
                </button>


                {{-- =================================================
                    DROPDOWN MENU
                ================================================== --}}

                <div
                    x-show="open"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"

                    class="absolute right-0 z-50 mt-3 w-64
                        origin-top-right overflow-hidden
                        rounded-xl bg-white
                        shadow-xl ring-1 ring-gray-200"
                    style="display: none;"
                >


                    {{-- =================================================
                        DROPDOWN HEADER
                    ================================================== --}}

                    <div
                        class="border-b border-green-100
                            bg-green-50 px-4 py-4"
                    >

                        <div class="flex items-center gap-3">

                            {{-- PROFILE IMAGE --}}
                            @if(Auth::user()->profile_picture)

                                <img
                                    src="{{ asset('storage/' . Auth::user()->profile_picture) }}"
                                    alt="{{ Auth::user()->name }}"
                                    class="h-11 w-11 rounded-full
                                        object-cover
                                        ring-2 ring-green-200"
                                >

                            @else

                                <div
                                    class="flex h-11 w-11
                                        items-center justify-center
                                        rounded-full bg-white
                                        text-green-700
                                        ring-2 ring-green-200"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15.75 6a3.75 3.75
                                            0 11-7.5 0
                                            3.75 3.75 0 017.5 0z
                                            M4.5 20.25a8.25 8.25
                                            0 0115 0"
                                        />

                                    </svg>

                                </div>

                            @endif


                            <div class="min-w-0">

                                <p
                                    class="truncate text-sm
                                        font-bold text-gray-800"
                                >
                                    {{ Auth::user()->name }}
                                </p>

                                <p
                                    class="truncate text-xs
                                        text-gray-500"
                                >
                                    {{ Auth::user()->email }}
                                </p>

                                <p
                                    class="mt-1 text-[10px]
                                        font-bold uppercase
                                        tracking-wider text-green-700"
                                >
                                    {{ str_replace('_', ' ', Auth::user()->role) }}
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                        MENU ITEMS
                    ================================================== --}}

                    <div class="p-2">


                        {{-- PROFILE --}}
                        <a
                            href="{{ route('profile.edit') }}"
                            class="flex items-center gap-3
                                rounded-lg px-3 py-2.5
                                text-sm font-medium
                                text-gray-700
                                transition
                                hover:bg-green-50
                                hover:text-green-700"
                        >

                            <span
                                class="flex h-9 w-9
                                    items-center justify-center
                                    rounded-lg bg-green-50
                                    text-green-700"
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
                                        d="M15.75 6a3.75 3.75
                                        0 11-7.5 0
                                        3.75 3.75 0 017.5 0z
                                        M4.5 20.25a8.25 8.25
                                        0 0115 0"
                                    />

                                </svg>

                            </span>

                            <span>
                                Profile
                            </span>

                        </a>


                        {{-- CHANGE PASSWORD --}}
                        <a
                            href="{{ route('profile.edit') }}#password"
                            class="flex items-center gap-3
                                rounded-lg px-3 py-2.5
                                text-sm font-medium
                                text-gray-700
                                transition
                                hover:bg-green-50
                                hover:text-green-700"
                        >

                            <span
                                class="flex h-9 w-9
                                    items-center justify-center
                                    rounded-lg bg-green-50
                                    text-green-700"
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
                                        d="M16.5 10.5V6.75
                                        a4.5 4.5 0 00-9 0v3.75
                                        m-.75 0h10.5A2.25
                                        2.25 0 0119.5 12.75v6
                                        A2.25 2.25 0 0117.25 21
                                        h-10.5a2.25 2.25 0
                                        01-2.25-2.25v-6A2.25
                                        2.25 0 014.5 10.5z"
                                    />

                                </svg>

                            </span>

                            <span>
                                Change Password
                            </span>

                        </a>


                        {{-- DIVIDER --}}
                        <div class="my-2 border-t border-gray-100"></div>


                        {{-- LOGOUT --}}
                        <form
                            method="POST"
                            action="{{ route('logout') }}"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="flex w-full items-center gap-3
                                    rounded-lg px-3 py-2.5
                                    text-sm font-medium
                                    text-red-600
                                    transition
                                    hover:bg-red-50"
                            >

                                <span
                                    class="flex h-9 w-9
                                        items-center justify-center
                                        rounded-lg bg-red-50
                                        text-red-600"
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
                                            d="M15.75 9V5.25A2.25
                                            2.25 0 0013.5 3h-6A2.25
                                            2.25 0 005.25 5.25v13.5
                                            A2.25 2.25 0 007.5 21h6
                                            a2.25 2.25 0 002.25-2.25V15"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 12h9m0 0l-3-3m3 3l-3 3"
                                        />

                                    </svg>

                                </span>

                                <span>
                                    Logout
                                </span>

                            </button>

                        </form>


                    </div>

                </div>

            </div>

        </div>

    </div>

</nav>