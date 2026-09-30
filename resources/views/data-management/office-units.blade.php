<x-app-layout>

    <div class="min-h-screen bg-gray-50">

        {{-- =====================================================
            HEADER
        ====================================================== --}}

        <div class="border-b border-gray-200 bg-white">

            <div class="mx-auto max-w-7xl px-6 py-5">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <div class="flex items-center gap-2 text-xs font-medium text-gray-500">

                            <span>Data Management</span>

                            <span>/</span>

                            <span class="text-green-700">
                                Office Unit Database
                            </span>

                        </div>

                        <h1 class="mt-1 text-2xl font-bold text-gray-900">
                            Office Unit Database
                        </h1>

                        <p class="mt-1 text-sm text-gray-500">
                            Manage Division Office groups, units, sections, and sub-units.
                        </p>

                    </div>


                    <button
                        type="button"
                        onclick="openAddModal()"
                        class="inline-flex items-center justify-center gap-2
                               rounded-lg bg-green-700 px-4 py-2.5
                               text-sm font-semibold text-white
                               shadow-sm transition hover:bg-green-800"
                    >

                        <svg
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 4.5v15m7.5-7.5h-15"
                            />
                        </svg>

                        Add Office Unit

                    </button>

                </div>

            </div>

        </div>


        {{-- =====================================================
            CONTENT
        ====================================================== --}}

        <div class="mx-auto max-w-7xl px-6 py-6">


            {{-- SUCCESS --}}

            @if(session('success'))

                <div class="mb-5 rounded-lg border border-green-200
                            bg-green-50 px-4 py-3 text-sm text-green-800">

                    {{ session('success') }}

                </div>

            @endif


            {{-- ERROR --}}

            @if(session('error'))

                <div class="mb-5 rounded-lg border border-red-200
                            bg-red-50 px-4 py-3 text-sm text-red-700">

                    {{ session('error') }}

                </div>

            @endif


            @if($errors->any())

                <div class="mb-5 rounded-lg border border-red-200
                            bg-red-50 px-4 py-3 text-sm text-red-700">

                    <ul class="list-disc space-y-1 pl-5">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- =====================================================
                FILTER
            ====================================================== --}}

            <div class="mb-5 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                <form
                    method="GET"
                    action="{{ route('data-management.office-units') }}"
                    class="flex w-full items-end gap-3"
                >

                    {{-- SEARCH --}}
                    <div class="flex min-w-0 flex-1 items-center gap-3">

                        <label
                            for="search"
                            class="shrink-0 text-sm font-semibold text-gray-600"
                        >
                            Search
                        </label>

                        <input
                            type="text"
                            id="search"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search code, office unit, or section..."
                            class="h-10 min-w-0 flex-1 rounded-lg border-gray-300
                                text-sm shadow-sm
                                focus:border-green-500
                                focus:ring-green-500"
                        >

                    </div>


                    {{-- OFFICE GROUP --}}
                    <div class="flex min-w-0 flex-1 items-center gap-3">

                        <label
                            for="group"
                            class="shrink-0 text-sm font-semibold text-gray-600"
                        >
                            Office Group
                        </label>

                        <select
                            id="group"
                            name="group"
                            class="h-10 min-w-0 flex-1 rounded-lg border-gray-300
                                text-sm shadow-sm
                                focus:border-green-500
                                focus:ring-green-500"
                        >

                            <option value="">
                                All Office Groups
                            </option>

                            @foreach($officeGroups as $officeGroup)

                                <option
                                    value="{{ $officeGroup->id }}"
                                    @selected((string) $group === (string) $officeGroup->id)
                                >
                                    {{ $officeGroup->code }}
                                    —
                                    {{ $officeGroup->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- SEARCH BUTTON --}}
                    <button
                        type="submit"
                        class="h-10 shrink-0 rounded-lg bg-green-700
                            px-5 text-sm font-semibold text-white
                            transition hover:bg-green-800"
                    >
                        Search
                    </button>


                    {{-- RESET BUTTON --}}
                    <a
                        href="{{ route('data-management.office-units') }}"
                        class="inline-flex h-10 shrink-0 items-center justify-center
                            rounded-lg border border-gray-300 bg-white
                            px-4 text-sm font-medium text-gray-600
                            transition hover:bg-gray-50"
                    >
                        Reset
                    </a>

                </form>

            </div>


            {{-- =====================================================
                TABLE
            ====================================================== --}}

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-200 px-5 py-4">

                    <h2 class="font-semibold text-gray-800">
                        Office Units
                    </h2>

                    <p class="mt-0.5 text-xs text-gray-500">
                        {{ $officeUnits->total() }}
                        {{ Str::plural('record', $officeUnits->total()) }}
                    </p>

                </div>


                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Office Group
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Code
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Office Unit
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Parent Unit
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Type
                                </th>

                                <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Status
                                </th>

                                <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100 bg-white">

                            @forelse($officeUnits as $unit)

                                <tr class="hover:bg-gray-50">

                                    {{-- GROUP --}}

                                    <td class="whitespace-nowrap px-5 py-3">

                                        <span class="inline-flex rounded-md
                                                     bg-green-50 px-2.5 py-1
                                                     text-xs font-bold text-green-700">

                                            {{ $unit->officeGroup?->code }}

                                        </span>

                                    </td>


                                    {{-- CODE --}}

                                    <td class="whitespace-nowrap px-5 py-3 text-sm font-medium text-gray-700">

                                        {{ $unit->code ?: '—' }}

                                    </td>


                                    {{-- UNIT --}}

                                    <td class="px-5 py-3">

                                        <div class="font-semibold text-gray-800">
                                            {{ $unit->name }}
                                        </div>

                                        @if($unit->short_name)

                                            <div class="mt-0.5 text-xs text-gray-500">
                                                {{ $unit->short_name }}
                                            </div>

                                        @endif

                                    </td>


                                    {{-- PARENT --}}

                                    <td class="px-5 py-3 text-sm text-gray-600">

                                        {{ $unit->parent?->name ?? '—' }}

                                    </td>


                                    {{-- TYPE --}}

                                    <td class="whitespace-nowrap px-5 py-3 text-sm text-gray-600">

                                        {{ $unit->unit_type }}

                                    </td>


                                    {{-- STATUS --}}

                                    <td class="whitespace-nowrap px-5 py-3 text-center">

                                        @if($unit->is_active)

                                            <span class="inline-flex rounded-full
                                                         bg-green-100 px-2.5 py-1
                                                         text-xs font-semibold text-green-700">
                                                Active
                                            </span>

                                        @else

                                            <span class="inline-flex rounded-full
                                                         bg-gray-100 px-2.5 py-1
                                                         text-xs font-semibold text-gray-600">
                                                Inactive
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ACTION --}}

                                    <td class="whitespace-nowrap px-5 py-3 text-center">

                                        <div class="inline-flex items-center gap-2">


                                            {{-- EDIT --}}

                                            <button
                                                type="button"
                                                onclick='openEditModal(@json($unit))'
                                                class="text-sm font-semibold text-blue-600 hover:text-blue-800"
                                            >
                                                Edit
                                            </button>


                                            {{-- STATUS --}}

                                            <form
                                                method="POST"
                                                action="{{ route('data-management.office-units.status', $unit) }}"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="text-sm font-semibold
                                                        {{ $unit->is_active
                                                            ? 'text-amber-600 hover:text-amber-800'
                                                            : 'text-green-600 hover:text-green-800' }}"
                                                >

                                                    {{ $unit->is_active
                                                        ? 'Deactivate'
                                                        : 'Activate' }}

                                                </button>

                                            </form>


                                            {{-- DELETE --}}

                                            <form
                                                method="POST"
                                                action="{{ route('data-management.office-units.destroy', $unit) }}"
                                                onsubmit="return confirm('Delete this office unit?');"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="text-sm font-semibold text-red-600 hover:text-red-800"
                                                >
                                                    Delete
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="7"
                                        class="px-5 py-12 text-center text-sm text-gray-500"
                                    >
                                        No office units found.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- PAGINATION --}}

                @if($officeUnits->hasPages())

                    <div class="border-t border-gray-200 px-5 py-4">

                        {{ $officeUnits->links() }}

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- =====================================================
        ADD / EDIT MODAL
    ====================================================== --}}

    <div
        id="officeUnitModal"
        class="fixed inset-0 z-50 hidden items-center justify-center
               bg-black/40 px-4"
    >

        <div class="w-full max-w-2xl rounded-2xl bg-white shadow-2xl">

            {{-- MODAL HEADER --}}

            <div class="flex items-center justify-between
                        border-b border-gray-200 px-6 py-4">

                <div>

                    <h3
                        id="modalTitle"
                        class="text-lg font-bold text-gray-900"
                    >
                        Add Office Unit
                    </h3>

                    <p class="text-xs text-gray-500">
                        Enter the office unit information.
                    </p>

                </div>

                <button
                    type="button"
                    onclick="closeOfficeUnitModal()"
                    class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600"
                >
                    ✕
                </button>

            </div>


            <form
                id="officeUnitForm"
                method="POST"
                action="{{ route('data-management.office-units.store') }}"
            >

                @csrf

                <div id="methodField"></div>


                <div class="grid gap-4 p-6 md:grid-cols-2">


                    {{-- OFFICE GROUP --}}

                    <div>

                        <label class="mb-1 block text-xs font-semibold text-gray-600">
                            Office Group
                        </label>

                        <select
                            id="modal_office_group_id"
                            name="office_group_id"
                            required
                            class="w-full rounded-lg border-gray-300 text-sm
                                   focus:border-green-500 focus:ring-green-500"
                        >

                            <option value="">
                                Select Office Group
                            </option>

                            @foreach($officeGroups as $officeGroup)

                                <option value="{{ $officeGroup->id }}">

                                    {{ $officeGroup->code }}
                                    —
                                    {{ $officeGroup->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- PARENT --}}

                    <div>

                        <label class="mb-1 block text-xs font-semibold text-gray-600">
                            Parent Unit
                        </label>

                        <select
                            id="modal_parent_id"
                            name="parent_id"
                            class="w-full rounded-lg border-gray-300 text-sm
                                   focus:border-green-500 focus:ring-green-500"
                        >

                            <option value="">
                                No Parent Unit
                            </option>

                            @foreach($parentUnits as $parent)

                                <option
                                    value="{{ $parent->id }}"
                                    data-group="{{ $parent->office_group_id }}"
                                >

                                    {{ $parent->officeGroup?->code }}
                                    —
                                    {{ $parent->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- CODE --}}

                    <div>

                        <label class="mb-1 block text-xs font-semibold text-gray-600">
                            Code
                        </label>

                        <input
                            type="text"
                            id="modal_code"
                            name="code"
                            maxlength="50"
                            placeholder="Example: PERSONNEL"
                            class="w-full rounded-lg border-gray-300 text-sm
                                   focus:border-green-500 focus:ring-green-500"
                        >

                    </div>


                    {{-- SHORT NAME --}}

                    <div>

                        <label class="mb-1 block text-xs font-semibold text-gray-600">
                            Short Name
                        </label>

                        <input
                            type="text"
                            id="modal_short_name"
                            name="short_name"
                            maxlength="100"
                            placeholder="Optional"
                            class="w-full rounded-lg border-gray-300 text-sm
                                   focus:border-green-500 focus:ring-green-500"
                        >

                    </div>


                    {{-- NAME --}}

                    <div class="md:col-span-2">

                        <label class="mb-1 block text-xs font-semibold text-gray-600">
                            Office Unit Name
                        </label>

                        <input
                            type="text"
                            id="modal_name"
                            name="name"
                            maxlength="150"
                            required
                            placeholder="Enter office unit name"
                            class="w-full rounded-lg border-gray-300 text-sm
                                   focus:border-green-500 focus:ring-green-500"
                        >

                    </div>


                    {{-- UNIT TYPE --}}

                    <div>

                        <label class="mb-1 block text-xs font-semibold text-gray-600">
                            Unit Type
                        </label>

                        <select
                            id="modal_unit_type"
                            name="unit_type"
                            required
                            class="w-full rounded-lg border-gray-300 text-sm
                                   focus:border-green-500 focus:ring-green-500"
                        >

                            <option value="Office">
                                Office
                            </option>

                            <option value="Division">
                                Division
                            </option>

                            <option value="Unit" selected>
                                Unit
                            </option>

                            <option value="Section">
                                Section
                            </option>

                        </select>

                    </div>


                    {{-- SORT ORDER --}}

                    <div>

                        <label class="mb-1 block text-xs font-semibold text-gray-600">
                            Sort Order
                        </label>

                        <input
                            type="number"
                            id="modal_sort_order"
                            name="sort_order"
                            value="0"
                            min="0"
                            class="w-full rounded-lg border-gray-300 text-sm
                                   focus:border-green-500 focus:ring-green-500"
                        >

                    </div>

                </div>


                {{-- FOOTER --}}

                <div class="flex justify-end gap-2
                            border-t border-gray-200 bg-gray-50
                            px-6 py-4">

                    <button
                        type="button"
                        onclick="closeOfficeUnitModal()"
                        class="rounded-lg border border-gray-300
                               bg-white px-4 py-2 text-sm font-semibold
                               text-gray-600 hover:bg-gray-50"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="rounded-lg bg-green-700 px-5 py-2
                               text-sm font-semibold text-white
                               hover:bg-green-800"
                    >
                        Save Office Unit
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================
        JAVASCRIPT
    ====================================================== --}}

    <script>

        const modal =
            document.getElementById('officeUnitModal');

        const form =
            document.getElementById('officeUnitForm');

        const methodField =
            document.getElementById('methodField');

        const groupSelect =
            document.getElementById('modal_office_group_id');

        const parentSelect =
            document.getElementById('modal_parent_id');


        /*
        |--------------------------------------------------------------------------
        | Filter Parent Units
        |--------------------------------------------------------------------------
        */

        function filterParentUnits() {

            const selectedGroup =
                groupSelect.value;

            Array.from(parentSelect.options).forEach(option => {

                if (!option.value) {
                    option.hidden = false;
                    return;
                }

                option.hidden =
                    option.dataset.group !== selectedGroup;

            });

            const selectedOption =
                parentSelect.options[
                    parentSelect.selectedIndex
                ];

            if (
                selectedOption &&
                selectedOption.hidden
            ) {
                parentSelect.value = '';
            }
        }


        groupSelect.addEventListener(
            'change',
            filterParentUnits
        );


        /*
        |--------------------------------------------------------------------------
        | Add Modal
        |--------------------------------------------------------------------------
        */

        function openAddModal() {

            document.getElementById('modalTitle')
                .textContent = 'Add Office Unit';

            form.action =
                "{{ route('data-management.office-units.store') }}";

            methodField.innerHTML = '';

            form.reset();

            document.getElementById('modal_sort_order')
                .value = 0;

            document.getElementById('modal_unit_type')
                .value = 'Unit';

            filterParentUnits();

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }


        /*
        |--------------------------------------------------------------------------
        | Edit Modal
        |--------------------------------------------------------------------------
        */

        function openEditModal(unit) {

            document.getElementById('modalTitle')
                .textContent = 'Edit Office Unit';

            form.action =
                "{{ url('/data-management/office-units') }}"
                + '/' + unit.id;

            methodField.innerHTML =
                '<input type="hidden" name="_method" value="PUT">';


            groupSelect.value =
                unit.office_group_id ?? '';

            document.getElementById('modal_code')
                .value = unit.code ?? '';

            document.getElementById('modal_name')
                .value = unit.name ?? '';

            document.getElementById('modal_short_name')
                .value = unit.short_name ?? '';

            document.getElementById('modal_unit_type')
                .value = unit.unit_type ?? 'Unit';

            document.getElementById('modal_sort_order')
                .value = unit.sort_order ?? 0;


            filterParentUnits();


            parentSelect.value =
                unit.parent_id ?? '';


            /*
            | Prevent selecting itself as parent
            */

            Array.from(parentSelect.options).forEach(option => {

                if (
                    option.value &&
                    Number(option.value) === Number(unit.id)
                ) {
                    option.hidden = true;
                }

            });


            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }


        /*
        |--------------------------------------------------------------------------
        | Close Modal
        |--------------------------------------------------------------------------
        */

        function closeOfficeUnitModal() {

            modal.classList.add('hidden');
            modal.classList.remove('flex');

        }


        /*
        |--------------------------------------------------------------------------
        | Close When Clicking Outside
        |--------------------------------------------------------------------------
        */

        modal.addEventListener('click', function(event) {

            if (event.target === modal) {
                closeOfficeUnitModal();
            }

        });


        /*
        |--------------------------------------------------------------------------
        | ESC Key
        |--------------------------------------------------------------------------
        */

        document.addEventListener('keydown', function(event) {

            if (event.key === 'Escape') {
                closeOfficeUnitModal();
            }

        });

    </script>

</x-app-layout>