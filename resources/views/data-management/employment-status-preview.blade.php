<x-app-layout>

<div class="mx-auto max-w-7xl px-6 py-8">

    {{-- HEADER --}}
    <div class="mb-6 rounded-2xl bg-green-800 p-6 text-white">

        <h1 class="text-2xl font-bold">
            Employment Status Import Preview
        </h1>

        <p class="mt-1 text-green-100">
            Review the employment information before confirming the import.
        </p>

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
                        Row {{ $error['row'] }}:
                        {{ $error['message'] }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- SUMMARY --}}
    <div class="mb-6 grid gap-4 md:grid-cols-3">

        <div class="rounded-xl border bg-white p-5 shadow-sm">

            <p class="text-sm text-gray-500">
                Total Records
            </p>

            <p class="mt-1 text-3xl font-bold text-green-700">
                {{ count($rows) }}
            </p>

        </div>


        <div class="rounded-xl border bg-white p-5 shadow-sm">

            <p class="text-sm text-gray-500">
                New Records
            </p>

            <p class="mt-1 text-3xl font-bold text-blue-700">
                {{ collect($rows)->where('action', 'New')->count() }}
            </p>

        </div>


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
                Employment Records Preview
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
                            Item No.
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">
                            School ID
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">
                            Employment Status
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">
                            Date of Original Appointment
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">
                            Date of Last Promotion
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">
                            Warm Body Status
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">
                            Nature of Work
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">
                            Source of Fund
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">
                            Salary
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">
                            Contract Duration
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-200">

                    @forelse($rows as $row)

                        <tr class="hover:bg-gray-50">

                            <td class="px-4 py-3 text-sm">
                                {{ $row['excel_row'] }}
                            </td>

                            <td class="px-4 py-3 text-sm font-medium">
                                {{ $row['name'] }}
                            </td>

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

                            <td class="px-4 py-3 text-sm">
                                {{ $row['item_number'] ?: '—' }}
                            </td>

                            <td class="px-4 py-3 text-sm">
                                {{ $row['school_id'] ?: '—' }}
                            </td>

                            <td class="px-4 py-3 text-sm">
                                {{ $row['employment_status'] ?: '—' }}
                            </td>

                            <td class="px-4 py-3 text-sm">
                                {{ $row['date_of_original_appointment'] ?: '—' }}
                            </td>

                            <td class="px-4 py-3 text-sm">
                                {{ $row['date_of_last_promotion'] ?: '—' }}
                            </td>

                            <td class="px-4 py-3 text-sm">
                                {{ $row['warm_body_status'] ?: '—' }}
                            </td>

                            <td class="px-4 py-3 text-sm">
                                {{ $row['nature_of_work'] ?: '—' }}
                            </td>

                            <td class="px-4 py-3 text-sm">
                                {{ $row['source_of_fund'] ?: '—' }}
                            </td>

                            <td class="px-4 py-3 text-sm">
                                {{ $row['monthly_salary'] ?: '—' }}
                            </td>

                            <td class="px-4 py-3 text-sm">
                                {{ $row['contract_duration'] ?: '—' }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="9"
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

            <a
                href="{{ route('data-management.employment-status') }}"
                class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
            >
                Cancel
            </a>


            @if(count($rows) > 0)

                <form
                    action="{{ route('data-management.employment-status.import.confirm') }}"
                    method="POST"
                >

                    @csrf

                    <button
                        type="submit"
                        class="rounded-lg bg-green-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-800"
                    >
                        Confirm Import
                    </button>

                </form>

            @endif

        </div>

    </div>

</div>

</x-app-layout>