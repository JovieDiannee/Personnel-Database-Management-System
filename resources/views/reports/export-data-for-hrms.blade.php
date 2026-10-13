<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-6">
        <nav class="mb-4 text-sm text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-green-700">Dashboard</a> /
            <a href="{{ route('data-management.reports') }}" class="hover:text-green-700">Report Management</a> /
            <span class="font-semibold text-green-800">Export Data for HRMS</span>
        </nav>
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-green-900">Export Data for HRMS</h1>
                <p class="mt-1 text-sm text-gray-500">Filter by school, district, or area. Excel follows the HRMS consolidated headers.</p>
            </div>
            <div class="rounded-lg border border-green-200 bg-green-50 px-5 py-3">
                <div class="text-xs font-semibold text-green-700">MATCHING PERSONNEL</div>
                <div class="text-2xl font-bold text-green-800">{{ number_format($records->total()) }}</div>
            </div>
        </div>
        @if($errors->any())
            <div class="mt-4 rounded-lg bg-red-50 p-4 text-sm text-red-700" role="alert">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <section class="mt-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('data-management.reports.export-data-for-hrms') }}">
                <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                    <div>
                        <label for="school_db_id" class="mb-1 block text-sm font-semibold text-gray-700">School</label>
                        <select id="school_db_id" name="school_db_id" class="w-full rounded-lg border-gray-300 text-sm">
                            <option value="">All Schools</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" @selected((string) ($filters['school_db_id'] ?? '') === (string) $school->id)>
                                    {{ $school->school_id }} – {{ $school->school_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="school_district" class="mb-1 block text-sm font-semibold text-gray-700">District</label>
                        <select id="school_district" name="school_district" class="w-full rounded-lg border-gray-300 text-sm">
                            <option value="">All Districts</option>
                            @foreach($districts as $district)
                                <option value="{{ $district }}" @selected(($filters['school_district'] ?? '') === $district)>{{ $district }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="school_area" class="mb-1 block text-sm font-semibold text-gray-700">Area</label>
                        <select id="school_area" name="school_area" class="w-full rounded-lg border-gray-300 text-sm">
                            <option value="">All Areas</option>
                            @foreach($areas as $area)
                                <option value="{{ $area }}" @selected(($filters['school_area'] ?? '') === $area)>{{ $area }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex flex-wrap justify-end gap-2">
                    <a href="{{ route('data-management.reports.export-data-for-hrms') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-600">Reset</a>
                    <button type="submit" class="rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold text-white hover:bg-green-800">Apply Filters</button>
                    <button type="submit" formaction="{{ route('data-management.reports.export-data-for-hrms.download') }}" class="rounded-lg bg-green-900 px-5 py-2 text-sm font-semibold text-white hover:bg-green-950">Download Excel</button>
                </div>
            </form>
            <p class="mt-3 text-xs text-gray-500">Combined filters must all match. Leave filters blank to export all eligible personnel. Medical allowance uses {{ now('Asia/Manila')->year }} records.</p>
        </section>
        <section class="mt-4 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 p-4 font-semibold text-green-900">Personnel Preview</div>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap text-left text-sm">
                    <thead class="bg-green-50 text-green-800">
                        <tr>
                            @foreach(['Employee No.', 'Name', 'Email Address', 'School', 'District', 'Area', 'Position Title'] as $heading)
                                <th class="px-4 py-3">{{ $heading }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($records as $record)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">{{ data_get($record, 'basicInformation.issuedId.employee_id') }}</td>
                                <td class="px-4 py-3">{{ data_get($record, 'basicInformation.last_name') }}, {{ data_get($record, 'basicInformation.first_name') }} {{ data_get($record, 'basicInformation.middle_name') }} {{ data_get($record, 'basicInformation.extension_name') }}</td>
                                <td class="px-4 py-3">{{ $record->email }}</td>
                                <td class="px-4 py-3">{{ data_get($record, 'employmentStatus.school.school_name') }}</td>
                                <td class="px-4 py-3">{{ data_get($record, 'employmentStatus.school.school_district') }}</td>
                                <td class="px-4 py-3">{{ data_get($record, 'employmentStatus.school.school_area') }}</td>
                                <td class="px-4 py-3">{{ data_get($record, 'employmentStatus.plantilla.position_title') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">No personnel records match the selected filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-gray-200 p-4">{{ $records->links() }}</div>
        </section>
    </div>
</x-app-layout>
