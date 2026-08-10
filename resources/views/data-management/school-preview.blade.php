<x-app-layout>

<div class="mx-auto max-w-7xl px-6 py-8">

    {{-- PAGE HEADER --}}
    <div class="rounded-2xl bg-gradient-to-br
                from-green-950 via-green-900 to-green-800
                px-8 py-8 text-white shadow-lg">

        <div class="flex items-center justify-between">

            <div>

                <h1 class="text-2xl font-bold">
                    School Database Import Preview
                </h1>

                <p class="mt-2 text-sm text-green-100">
                    Review the uploaded school records before importing.
                </p>

            </div>


            <a
                href="{{ route('data-management.schools') }}"
                class="rounded-lg border border-white/40
                       px-4 py-2 text-sm font-semibold
                       text-white hover:bg-white/10"
            >
                ← Back
            </a>

        </div>

    </div>


    {{-- SUMMARY --}}
    <div class="mt-6 grid gap-5 md:grid-cols-2">

        <div class="rounded-xl border border-green-200
                    bg-green-50 p-5">

            <p class="text-sm text-gray-500">
                Total Records
            </p>

            <p class="mt-1 text-3xl font-bold text-green-700">
                {{ count($rows) }}
            </p>

        </div>


        <div class="rounded-xl border border-red-200
                    bg-red-50 p-5">

            <p class="text-sm text-gray-500">
                Validation Errors
            </p>

            <p class="mt-1 text-3xl font-bold text-red-600">
                {{ count($errors) }}
            </p>

        </div>

    </div>


    {{-- VALIDATION ERRORS --}}
    @if(count($errors) > 0)

        <div class="mt-6 rounded-xl border
                    border-red-200 bg-red-50 p-5">

            <h2 class="font-bold text-red-800">
                Validation Errors
            </h2>


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


    {{-- PREVIEW TABLE --}}
    <div class="mt-6 overflow-hidden rounded-xl
                border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 p-6">

            <h2 class="text-lg font-bold text-gray-900">
                School Records Preview
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Review all uploaded school information before proceeding.
            </p>

        </div>


        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-4 py-3 text-left text-xs
                                   font-semibold uppercase
                                   text-gray-600">
                            #
                        </th>

                        <th class="px-4 py-3 text-left text-xs
                                   font-semibold uppercase
                                   text-gray-600">
                            School ID
                        </th>

                        <th class="px-4 py-3 text-left text-xs
                                   font-semibold uppercase
                                   text-gray-600">
                            School Name
                        </th>

                        <th class="px-4 py-3 text-left text-xs
                                   font-semibold uppercase
                                   text-gray-600">
                            School Area
                        </th>

                        <th class="px-4 py-3 text-left text-xs
                                   font-semibold uppercase
                                   text-gray-600">
                            Legislative District
                        </th>

                        <th class="px-4 py-3 text-left text-xs
                                   font-semibold uppercase
                                   text-gray-600">
                            School District
                        </th>

                        <th class="px-4 py-3 text-left text-xs
                                   font-semibold uppercase
                                   text-gray-600">
                            Municipality
                        </th>

                        <th class="px-4 py-3 text-left text-xs
                                   font-semibold uppercase
                                   text-gray-600">
                            Sector
                        </th>

                        <th class="px-4 py-3 text-left text-xs
                                   font-semibold uppercase
                                   text-gray-600">
                            Curricular Offering
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-200 bg-white">

                    @foreach($rows as $row)

                        <tr class="hover:bg-gray-50">

                            <td class="px-4 py-3 text-sm text-gray-500">
                                {{ $row['excel_row'] }}
                            </td>

                            <td class="px-4 py-3 text-sm
                                       font-semibold text-gray-900">
                                {{ $row['school_id'] ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-sm
                                       font-semibold text-gray-900">
                                {{ $row['school_name'] ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $row['school_area'] ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $row['legislative_district'] ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $row['school_district'] ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $row['school_municipality'] ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $row['school_sector'] ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $row['school_curricular_offering'] ?? '—' }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>


    {{-- ACTION BUTTONS --}}
    <div class="mt-6 flex justify-end gap-3">

        <a
            href="{{ route('data-management.schools') }}"
            class="rounded-lg border border-gray-300
                   bg-white px-5 py-2.5
                   text-sm font-semibold text-gray-700
                   hover:bg-gray-50"
        >
            Cancel
        </a>


        @if(count($errors) === 0)

            <form
                action="{{ route('data-management.schools.import.confirm') }}"
                method="POST"
            >

                @csrf

                <button
                    type="submit"
                    class="rounded-lg bg-green-700
                           px-5 py-2.5 text-sm
                           font-semibold text-white
                           shadow-sm hover:bg-green-800"
                >
                    Confirm Import
                </button>

            </form>

        @else

            <button
                type="button"
                disabled
                class="cursor-not-allowed rounded-lg
                       bg-gray-300 px-5 py-2.5
                       text-sm font-semibold text-gray-500"
            >
                Fix Errors First
            </button>

        @endif

    </div>

</div>

</x-app-layout>