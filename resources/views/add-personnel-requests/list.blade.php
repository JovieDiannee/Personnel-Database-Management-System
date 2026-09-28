<x-app-layout>

    <div class="py-6">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


            <div class="mb-6">

                <div class="flex items-center gap-2 text-sm text-gray-500">

                    <a
                        href="{{ route('dashboard') }}"
                        class="hover:text-green-700"
                    >
                        Dashboard
                    </a>

                    <span>/</span>

                    <a
                        href="{{ route('data-management') }}"
                        class="hover:text-green-700"
                    >
                        Data Management
                    </a>

                    <span>/</span>

                    <a
                        href="{{ route('data-management.employment-status') }}"
                        class="hover:text-green-700"
                    >
                        Employee Profile
                    </a>

                    <span>/</span>

                    <span class="font-medium text-green-700">
                        Pending Requests
                    </span>

                </div>

            </div>

            {{-- ============================================================
                HEADER
            ============================================================ --}}

            <div
                class="mb-6 flex flex-col gap-4
                       sm:flex-row sm:items-center sm:justify-between"
            >

                <div>

                    <h1 class="text-2xl font-bold text-gray-900">
                        Personnel Requests
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        Track personnel records submitted for Personnel Unit
                        review and approval.
                    </p>

                </div>


                <a
                    href="{{ route('add-personnel-requests.create') }}"
                    class="inline-flex items-center justify-center
                           rounded-lg bg-green-700 px-5 py-2.5
                           text-sm font-semibold text-white shadow-sm
                           transition hover:bg-green-800"
                >
                    + Add Personnel
                </a>

            </div>


            {{-- ============================================================
                SUCCESS
            ============================================================ --}}

            @if (session('success'))

                <div
                    class="mb-6 rounded-xl border border-green-200
                           bg-green-50 p-4 text-sm text-green-800"
                >
                    {{ session('success') }}
                </div>

            @endif


            @if (session('error'))

                <div
                    class="mb-6 rounded-xl border border-red-200
                           bg-red-50 p-4 text-sm text-red-700"
                >
                    {{ session('error') }}
                </div>

            @endif


            {{-- ============================================================
                CARD
            ============================================================ --}}

            <div
                class="overflow-hidden rounded-2xl border
                       border-gray-200 bg-white shadow-sm"
            >

                <div class="bg-green-800 px-6 py-5 text-white">

                    <h2 class="text-lg font-bold">
                        Submitted Personnel Requests
                    </h2>

                    <p class="mt-1 text-sm text-green-100">
                        View the status of personnel requests submitted
                        through your account.
                    </p>

                </div>


                {{-- ========================================================
                    SEARCH
                ======================================================== --}}

                <form
                    method="GET"
                    action="{{ route('add-personnel-requests.index') }}"
                    class="border-b border-gray-200 p-5"
                >

                    <div class="flex gap-3">

                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search name, email, item number or position..."
                            class="flex-1 rounded-lg border-gray-300
                                   focus:border-green-600
                                   focus:ring-green-600"
                        >

                        <button
                            type="submit"
                            class="rounded-lg bg-green-700 px-5 py-2
                                   text-sm font-semibold text-white
                                   hover:bg-green-800"
                        >
                            Search
                        </button>

                        @if ($search !== '')

                            <a
                                href="{{ route('add-personnel-requests.index') }}"
                                class="rounded-lg border border-gray-300
                                       px-5 py-2 text-sm font-semibold
                                       text-gray-600 hover:bg-gray-50"
                            >
                                Reset
                            </a>

                        @endif

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
                                    class="px-6 py-3 text-left text-xs
                                           font-semibold uppercase
                                           tracking-wider text-gray-500"
                                >
                                    #
                                </th>

                                <th
                                    class="px-6 py-3 text-left text-xs
                                           font-semibold uppercase
                                           tracking-wider text-gray-500"
                                >
                                    Personnel
                                </th>

                                <th
                                    class="px-6 py-3 text-left text-xs
                                           font-semibold uppercase
                                           tracking-wider text-gray-500"
                                >
                                    Plantilla / Position
                                </th>

                                <th
                                    class="px-6 py-3 text-left text-xs
                                           font-semibold uppercase
                                           tracking-wider text-gray-500"
                                >
                                    Submitted
                                </th>

                                <th
                                    class="px-6 py-3 text-center text-xs
                                           font-semibold uppercase
                                           tracking-wider text-gray-500"
                                >
                                    Status
                                </th>

                                <th
                                    class="px-6 py-3 text-left text-xs
                                           font-semibold uppercase
                                           tracking-wider text-gray-500"
                                >
                                    Review Remarks
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100 bg-white">

                            @forelse ($requests as $requestItem)

                                <tr class="hover:bg-gray-50">

                                    <td
                                        class="whitespace-nowrap
                                               px-6 py-4 text-sm text-gray-500"
                                    >
                                        {{ $requests->firstItem() + $loop->index }}
                                    </td>


                                    <td class="px-6 py-4">

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

                                        <p class="mt-1 text-sm text-gray-500">
                                            {{ $requestItem->email }}
                                        </p>

                                    </td>


                                    <td class="px-6 py-4">

                                        @if ($requestItem->plantilla)

                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $requestItem->plantilla->item_number }}
                                            </p>

                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ $requestItem->plantilla->position_title }}
                                            </p>

                                        @else

                                            <span class="text-sm text-gray-400">
                                                No plantilla
                                            </span>

                                        @endif

                                    </td>


                                    <td
                                        class="whitespace-nowrap
                                               px-6 py-4 text-sm text-gray-600"
                                    >
                                        {{ $requestItem->created_at->format('M d, Y') }}

                                        <div class="text-xs text-gray-400">
                                            {{ $requestItem->created_at->format('h:i A') }}
                                        </div>
                                    </td>


                                    <td class="px-6 py-4 text-center">

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

                                        @elseif ($requestItem->status === 'disapproved')

                                            <span
                                                class="inline-flex rounded-full
                                                       bg-red-100 px-3 py-1
                                                       text-xs font-semibold
                                                       text-red-700"
                                            >
                                                Disapproved
                                            </span>

                                        @else

                                            <span
                                                class="inline-flex rounded-full
                                                       bg-gray-100 px-3 py-1
                                                       text-xs font-semibold
                                                       text-gray-700"
                                            >
                                                {{ ucfirst($requestItem->status) }}
                                            </span>

                                        @endif

                                    </td>


                                    <td class="px-6 py-4 text-sm text-gray-600">

                                        @if ($requestItem->review_remarks)

                                            {{ $requestItem->review_remarks }}

                                        @elseif ($requestItem->status === 'pending')

                                            <span class="italic text-gray-400">
                                                Awaiting review
                                            </span>

                                        @else

                                            <span class="text-gray-400">
                                                —
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="6"
                                        class="px-6 py-12 text-center"
                                    >

                                        <p class="font-medium text-gray-600">
                                            No personnel requests found.
                                        </p>

                                        <p class="mt-1 text-sm text-gray-400">
                                            New personnel requests will appear here.
                                        </p>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- ========================================================
                    PAGINATION
                ======================================================== --}}

                @if ($requests->hasPages())

                    <div class="border-t border-gray-200 px-6 py-4">

                        {{ $requests->links() }}

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>