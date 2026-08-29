<x-app-layout>

    <div class="mx-auto max-w-7xl px-6 py-8">
        {{-- BREADCRUMB TRAIL --}}
        <div class="mb-4">

            <nav
                class="flex items-center text-sm"
                aria-label="Breadcrumb"
            >

                {{-- Home --}}
                <a
                    href="{{ route('dashboard') }}"
                    class="flex items-center font-medium text-gray-500
                        transition hover:text-green-700"
                >
                    <svg
                        class="mr-1.5 h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 12l9-9 9 9M5 10v10h14V10M9 20v-6h6v6"
                        />
                    </svg>

                    Dashboard
                </a>

                {{-- Separator --}}
                <svg
                    class="mx-2 h-4 w-4 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"
                    />
                </svg>

                {{-- Data Management --}}
                <a
                    href="{{ route('data-management') }}"
                    class="font-medium text-gray-500
                        transition hover:text-green-700"
                >
                    Data Management
                </a>

                {{-- Separator --}}
                <svg
                    class="mx-2 h-4 w-4 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"
                    />
                </svg>

                {{-- Medical Allowance --}}
                <a
                    href="{{ route('data-management.medical-allowance') }}"
                    class="font-medium text-gray-500
                        transition hover:text-green-700"
                >
                    Medical Allowance
                </a>

                {{-- Separator --}}
                <svg
                    class="mx-2 h-4 w-4 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"
                    />
                </svg>

                {{-- Current Page --}}
                <span class="font-semibold text-green-800">
                    Upload Preview
                </span>

            </nav>

        </div>


        {{-- ERRORS --}}
        @if(count($errors) > 0)

            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-5">

                <h3 class="font-bold text-red-800">
                    Validation Errors
                </h3>

                <ul class="mt-3 list-disc pl-5 text-sm text-red-700">

                    @foreach($errors as $error)

                        <li>
                            Row {{ $error['row'] ?? 'N/A' }}:
                            {{ $error['message'] ?? 'Unknown error' }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- SUMMARY --}}
        <div class="mb-6 grid gap-4 md:grid-cols-3">

            {{-- TOTAL --}}
            <div class="rounded-xl border bg-white p-5 shadow-sm">

                <p class="text-sm text-gray-500">
                    Total Records
                </p>

                <p class="mt-1 text-3xl font-bold text-green-700">
                    {{ count($rows) }}
                </p>

            </div>


            {{-- NEW --}}
            <div class="rounded-xl border bg-white p-5 shadow-sm">

                <p class="text-sm text-gray-500">
                    New Records
                </p>

                <p class="mt-1 text-3xl font-bold text-blue-700">
                    {{ collect($rows)->where('action', 'New')->count() }}
                </p>

            </div>


            {{-- UPDATE --}}
            <div class="rounded-xl border bg-white p-5 shadow-sm">

                <p class="text-sm text-gray-500">
                    Records to Update
                </p>

                <p class="mt-1 text-3xl font-bold text-yellow-600">
                    {{ collect($rows)->where('action', 'Update')->count() }}
                </p>

            </div>

        </div>


        {{-- PREVIEW TABLE --}}
        <div class="overflow-hidden rounded-xl border bg-white shadow-sm">

            <div class="border-b p-5">

                <h2 class="text-lg font-bold text-gray-800">
                    Medical Allowance Records Preview
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Review the records before proceeding.
                </p>

            </div>


            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase">
                                #
                            </th>

                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase">
                                Name
                            </th>

                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase">
                                Email
                            </th>

                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase">
                                Mode of Availment
                            </th>

                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase">
                                Disbursement Status
                            </th>

                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-200">

                        @forelse($rows as $row)

                            <tr class="hover:bg-gray-50">

                                {{-- ROW --}}
                                <td class="px-4 py-3 text-sm">
                                    {{ $row['excel_row'] }}
                                </td>


                                {{-- NAME --}}
                                <td class="px-4 py-3 text-sm font-medium">

                                    {{ $row['name'] ?? 'Personnel not found' }}

                                </td>


                                {{-- EMAIL --}}
                                <td class="w-48 max-w-48 px-4 py-3 text-sm text-gray-600">

                                    @php
                                        $email = $row['email'] ?? '';
                                        $parts = explode('@', $email, 2);
                                    @endphp

                                    @if(count($parts) === 2)

                                        {{ $parts[0] }}@<wbr>{{ $parts[1] }}

                                    @else

                                        {{ $email }}

                                    @endif

                                </td>


                                {{-- MODE OF AVAILMENT --}}
                                <td class="px-4 py-3 text-sm">

                                    {{ $row['mode_of_availment'] ?: '—' }}

                                </td>


                                {{-- DISBURSEMENT STATUS --}}
                                <td class="px-4 py-3 text-sm">

                                    {{ $row['disbursement_status'] ?: '—' }}

                                </td>


                                {{-- ACTION --}}
                                <td class="px-4 py-3 text-sm">

                                    @if(($row['action'] ?? '') === 'New')

                                        <span
                                            class="inline-flex rounded-full
                                                bg-green-100 px-3 py-1
                                                text-xs font-semibold
                                                text-green-700"
                                        >
                                            New
                                        </span>

                                    @elseif(($row['action'] ?? '') === 'Update')

                                        <span
                                            class="inline-flex rounded-full
                                                bg-blue-100 px-3 py-1
                                                text-xs font-semibold
                                                text-blue-700"
                                        >
                                            Update
                                        </span>

                                    @else

                                        <span
                                            class="inline-flex rounded-full
                                                bg-gray-100 px-3 py-1
                                                text-xs font-semibold
                                                text-gray-600"
                                        >
                                            {{ $row['action'] ?? 'Unknown' }}
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="px-6 py-10 text-center text-sm text-gray-500"
                                >
                                    No valid records found.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- ACTIONS --}}
            <div class="flex items-center justify-end gap-3 border-t p-5">

                {{-- CANCEL --}}
                <a
                    href="{{ route('data-management.medical-allowance') }}"
                    class="rounded-lg border border-gray-300
                        px-5 py-2.5
                        text-sm font-semibold text-gray-700
                        hover:bg-gray-50"
                >
                    Cancel
                </a>


                {{-- CONFIRM --}}
                @if(count($rows) > 0)

                    <form
                        action="{{ route('data-management.medical-allowance.import.confirm') }}"
                        method="POST"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="rounded-lg bg-green-700
                                px-5 py-2.5
                                text-sm font-semibold text-white
                                hover:bg-green-800"
                        >
                            Confirm Import
                        </button>

                    </form>

                @endif

            </div>

        </div>
    </div>

</x-app-layout>