<x-app-layout>

    <div class="py-6">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- ============================================================
                PAGE HEADER
            ============================================================ --}}

            <div class="mb-6 flex items-center justify-between">

                <div>

                    <h1 class="text-2xl font-bold text-gray-900">
                        Personnel Request Approval
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        Review personnel requests or directly add a new personnel.
                    </p>

                </div>


                {{-- ADD PERSONNEL --}}

                <a
                    href="{{ route('admin.personnel.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg
                        bg-green-700 px-5 py-2.5
                        text-sm font-semibold text-white
                        shadow-sm transition
                        hover:bg-green-800
                        focus:outline-none focus:ring-2
                        focus:ring-green-600 focus:ring-offset-2"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-5 w-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3
                            M6.75 6.75a3 3 0 1 1 6 0
                            3 3 0 0 1-6 0ZM3 20.25
                            a6.75 6.75 0 0 1 13.5 0v.75H3v-.75Z"
                        />
                    </svg>

                    Add Personnel

                </a>

            </div>


            {{-- ============================================================
                ALERTS
            ============================================================ --}}

            @if (session('success'))

                <div
                    class="mb-6 rounded-xl border border-green-200
                           bg-green-50 p-4 text-sm font-medium text-green-800"
                >
                    {{ session('success') }}
                </div>

            @endif


            @if (session('error'))

                <div
                    class="mb-6 rounded-xl border border-red-200
                           bg-red-50 p-4 text-sm font-medium text-red-700"
                >
                    {{ session('error') }}
                </div>

            @endif


            {{-- ============================================================
                STATUS CARDS
            ============================================================ --}}

            <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">

                {{-- PENDING --}}

                <a
                    href="{{ route(
                        'admin.personnel-requests.index',
                        ['status' => 'pending']
                    ) }}"
                    class="rounded-xl border border-yellow-200
                           bg-white p-5 shadow-sm transition
                           hover:border-yellow-400"
                >

                    <p
                        class="text-xs font-semibold uppercase
                               tracking-wide text-gray-500"
                    >
                        Pending Requests
                    </p>

                    <p class="mt-2 text-3xl font-bold text-yellow-600">
                        {{ number_format($pendingCount) }}
                    </p>

                </a>


                {{-- APPROVED --}}

                <a
                    href="{{ route(
                        'admin.personnel-requests.index',
                        ['status' => 'approved']
                    ) }}"
                    class="rounded-xl border border-green-200
                           bg-white p-5 shadow-sm transition
                           hover:border-green-400"
                >

                    <p
                        class="text-xs font-semibold uppercase
                               tracking-wide text-gray-500"
                    >
                        Approved
                    </p>

                    <p class="mt-2 text-3xl font-bold text-green-700">
                        {{ number_format($approvedCount) }}
                    </p>

                </a>


                {{-- DISAPPROVED --}}

                <a
                    href="{{ route(
                        'admin.personnel-requests.index',
                        ['status' => 'disapproved']
                    ) }}"
                    class="rounded-xl border border-red-200
                           bg-white p-5 shadow-sm transition
                           hover:border-red-400"
                >

                    <p
                        class="text-xs font-semibold uppercase
                               tracking-wide text-gray-500"
                    >
                        Disapproved
                    </p>

                    <p class="mt-2 text-3xl font-bold text-red-600">
                        {{ number_format($disapprovedCount) }}
                    </p>

                </a>

            </div>


            {{-- ============================================================
                MAIN CARD
            ============================================================ --}}

            <div
                class="overflow-hidden rounded-2xl border
                       border-gray-200 bg-white shadow-sm"
            >

                <div class="bg-green-800 px-6 py-5 text-white">

                    <h2 class="text-lg font-bold">
                        Personnel Requests
                    </h2>

                    <p class="mt-1 text-sm text-green-100">
                        Pending requests are displayed first for review.
                    </p>

                </div>


                {{-- ========================================================
                    SEARCH / FILTER
                ======================================================== --}}

                <form
                    method="GET"
                    action="{{ route('admin.personnel-requests.index') }}"
                    class="border-b border-gray-200 p-5"
                >

                    <div class="flex items-center gap-3">

                        {{-- SEARCH --}}
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search personnel, email, school, item or position..."
                            class="min-w-0 flex-1 rounded-lg border-gray-300
                                focus:border-green-600
                                focus:ring-green-600"
                        >


                        {{-- STATUS --}}
                        <select
                            name="status"
                            class="w-48 shrink-0 rounded-lg border-gray-300
                                focus:border-green-600
                                focus:ring-green-600"
                        >

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="pending"
                                @selected($status === 'pending')
                            >
                                Pending
                            </option>

                            <option
                                value="approved"
                                @selected($status === 'approved')
                            >
                                Approved
                            </option>

                            <option
                                value="disapproved"
                                @selected($status === 'disapproved')
                            >
                                Disapproved
                            </option>

                        </select>


                        {{-- SEARCH BUTTON --}}
                        <button
                            type="submit"
                            class="shrink-0 rounded-lg bg-green-700
                                px-6 py-2.5
                                text-sm font-semibold text-white
                                transition hover:bg-green-800"
                        >
                            Search
                        </button>

                    </div>

                </form>


                {{-- ========================================================
                    TABLE
                ======================================================== --}}

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th
                                    class="px-5 py-3 text-left text-xs
                                           font-semibold uppercase
                                           text-gray-500"
                                >
                                    #
                                </th>

                                <th
                                    class="px-5 py-3 text-left text-xs
                                           font-semibold uppercase
                                           text-gray-500"
                                >
                                    Personnel
                                </th>

                                <th
                                    class="px-5 py-3 text-left text-xs
                                           font-semibold uppercase
                                           text-gray-500"
                                >
                                    School
                                </th>

                                <th
                                    class="px-5 py-3 text-left text-xs
                                           font-semibold uppercase
                                           text-gray-500"
                                >
                                    Position
                                </th>

                                <th
                                    class="px-5 py-3 text-left text-xs
                                           font-semibold uppercase
                                           text-gray-500"
                                >
                                    Submitted By
                                </th>

                                <th
                                    class="px-5 py-3 text-center text-xs
                                           font-semibold uppercase
                                           text-gray-500"
                                >
                                    Status
                                </th>

                                <th
                                    class="px-5 py-3 text-center text-xs
                                           font-semibold uppercase
                                           text-gray-500"
                                >
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @forelse ($requests as $requestItem)

                                <tr class="hover:bg-gray-50">

                                    <td class="px-5 py-4 text-sm text-gray-500">
                                        {{ $requests->firstItem() + $loop->index }}
                                    </td>


                                    {{-- PERSONNEL --}}

                                    <td class="px-5 py-4">

                                        <p class="font-semibold text-gray-900">

                                            {{ $requestItem->first_name }}

                                            @if ($requestItem->middle_name)
                                                {{ $requestItem->middle_name }}
                                            @endif

                                            {{ $requestItem->last_name }}

                                            @if ($requestItem->extension_name)
                                                {{ $requestItem->extension_name }}
                                            @endif

                                        </p>

                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ $requestItem->email }}
                                        </p>

                                    </td>


                                    {{-- SCHOOL --}}

                                    <td class="px-5 py-4">

                                        <p class="text-sm font-medium text-gray-800">
                                            {{ $requestItem->school?->school_name ?? '—' }}
                                        </p>

                                        @if ($requestItem->school?->school_district)

                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ $requestItem->school->school_district }}
                                            </p>

                                        @endif

                                    </td>


                                    {{-- POSITION --}}

                                    <td class="px-5 py-4">

                                        @if ($requestItem->plantilla)

                                            <p class="text-sm font-medium text-gray-800">
                                                {{ $requestItem->plantilla->position_title }}
                                            </p>

                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ $requestItem->plantilla->item_number }}
                                            </p>

                                        @else

                                            <span class="text-sm text-gray-400">
                                                No plantilla
                                            </span>

                                        @endif

                                    </td>


                                    {{-- SUBMITTED BY --}}

                                    <td class="px-5 py-4">

                                        <p class="text-sm text-gray-800">
                                            {{ $requestItem->requester?->name ?? '—' }}
                                        </p>

                                        <p class="mt-1 text-xs text-gray-400">
                                            {{ $requestItem->created_at->format('M d, Y h:i A') }}
                                        </p>

                                    </td>


                                    {{-- STATUS --}}

                                    <td class="px-5 py-4 text-center">

                                        @if ($requestItem->status === 'pending')

                                            <span
                                                class="inline-flex rounded-full
                                                       bg-yellow-100 px-3 py-1
                                                       text-xs font-semibold
                                                       text-yellow-800"
                                            >
                                                Pending
                                            </span>

                                        @elseif ($requestItem->status === 'approved')

                                            <span
                                                class="inline-flex rounded-full
                                                       bg-green-100 px-3 py-1
                                                       text-xs font-semibold
                                                       text-green-800"
                                            >
                                                Approved
                                            </span>

                                        @else

                                            <span
                                                class="inline-flex rounded-full
                                                       bg-red-100 px-3 py-1
                                                       text-xs font-semibold
                                                       text-red-700"
                                            >
                                                Disapproved
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ACTION --}}

                                    <td class="px-5 py-4 text-center">

                                        <a
                                            href="{{ route(
                                                'admin.personnel-requests.show',
                                                $requestItem
                                            ) }}"
                                            class="inline-flex rounded-lg
                                                   bg-green-700 px-4 py-2
                                                   text-xs font-semibold
                                                   text-white
                                                   hover:bg-green-800"
                                        >
                                            {{ $requestItem->status === 'pending'
                                                ? 'Review'
                                                : 'View'
                                            }}
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="7"
                                        class="px-6 py-12 text-center"
                                    >
                                        <p class="font-medium text-gray-600">
                                            No personnel requests found.
                                        </p>
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                @if ($requests->hasPages())

                    <div class="border-t border-gray-200 px-6 py-4">
                        {{ $requests->links() }}
                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>