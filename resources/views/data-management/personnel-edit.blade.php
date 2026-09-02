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

                {{-- Personnel Information --}}
                <a
                    href="{{ route('data-management.personnel') }}"
                    class="font-medium text-gray-500
                        transition hover:text-green-700"
                >
                    Personnel Information
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
                    Employee Profile
                </span>

            </nav>

        </div>

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))

            <div
                class="mb-6 flex items-center gap-3 rounded-xl
                    border border-green-300 bg-green-100
                    px-5 py-4
                    text-sm font-medium text-green-900"
            >

                {{-- SUCCESS ICON --}}
                <div
                    class="flex h-9 w-9 shrink-0 items-center justify-center
                        rounded-full bg-green-600 text-white"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2.5"
                        class="h-5 w-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>
                </div>

                {{-- MESSAGE --}}
                <div>
                    <p class="font-bold text-green-900">
                        Update Successful
                    </p>

                    <p class="mt-0.5 text-sm font-normal text-green-800">
                        {{ session('success') }}
                    </p>
                </div>

            </div>

        @endif


        {{-- =====================================================
        PERSONNEL SUMMARY
        ====================================================== --}}

        <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex items-center gap-4">

                <div
                    class="flex h-14 w-14 items-center justify-center
                           rounded-full bg-green-100
                           text-xl font-bold text-green-700"
                >
                    {{ strtoupper(substr($person->first_name, 0, 1)) }}
                    {{ strtoupper(substr($person->last_name, 0, 1)) }}
                </div>

                <div>

                    <h2 class="text-xl font-bold text-gray-900">
                        {{ $person->last_name }},
                        {{ $person->first_name }}
                        {{ $person->middle_name }}
                        {{ $person->extension_name }}
                    </h2>

                    <p class="text-sm text-gray-500">
                        {{ $person->user?->email ?? 'No email address' }}
                    </p>

                </div>

            </div>

        </div>


        {{-- VALIDATION ERRORS --}}
        @if($errors->any())

            <div
                class="mb-6 rounded-xl border border-red-200
                       bg-red-50 px-5 py-4"
            >

                <p class="font-semibold text-red-700">
                    Please correct the following:
                </p>

                <ul class="mt-2 list-disc pl-5 text-sm text-red-600">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =====================================================
        FORM
        ====================================================== --}}

        <form
            method="POST"
            action="{{ route('data-management.personnel.update', $person->id) }}"
        >

            @csrf
            @method('PUT')


            {{-- =================================================
            ACCOUNT INFORMATION
            ================================================== --}}

            <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">

                    <h3 class="font-bold text-gray-900">
                        Account Information
                    </h3>

                    <p class="mt-1 text-xs text-gray-500">
                        Personnel account and employee identification.
                    </p>

                </div>


                <div class="grid gap-5 p-6 md:grid-cols-2">

                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Email Address
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $person->user?->email) }}"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-green-600
                                   focus:ring-green-600"
                        >

                    </div>


                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Employee ID
                        </label>

                        <input
                            type="text"
                            name="employee_id"
                            value="{{ old('employee_id', $person->issuedId?->employee_id) }}"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-green-600
                                   focus:ring-green-600"
                        >

                    </div>

                </div>

            </div>


            {{-- =================================================
            BASIC INFORMATION
            ================================================== --}}

            <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">

                    <h3 class="font-bold text-gray-900">
                        Basic Information
                    </h3>

                    <p class="mt-1 text-xs text-gray-500">
                        Personal and demographic information.
                    </p>

                </div>


                <div class="grid gap-5 p-6 md:grid-cols-2 lg:grid-cols-4">

                    {{-- FIRST NAME --}}
                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            First Name
                        </label>

                        <input
                            type="text"
                            name="first_name"
                            value="{{ old('first_name', $person->first_name) }}"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-green-600
                                   focus:ring-green-600"
                        >

                    </div>


                    {{-- MIDDLE NAME --}}
                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Middle Name
                        </label>

                        <input
                            type="text"
                            name="middle_name"
                            value="{{ old('middle_name', $person->middle_name) }}"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-green-600
                                   focus:ring-green-600"
                        >

                    </div>


                    {{-- LAST NAME --}}
                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Last Name
                        </label>

                        <input
                            type="text"
                            name="last_name"
                            value="{{ old('last_name', $person->last_name) }}"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-green-600
                                   focus:ring-green-600"
                        >

                    </div>


                    {{-- EXTENSION --}}
                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Extension Name
                        </label>

                        <input
                            type="text"
                            name="extension_name"
                            value="{{ old('extension_name', $person->extension_name) }}"
                            placeholder="Jr., Sr., III"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-green-600
                                   focus:ring-green-600"
                        >

                    </div>


                    {{-- SEX --}}
                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Sex
                        </label>

                        <select
                            name="sex"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-green-600
                                   focus:ring-green-600"
                        >

                            <option
                                value="Male"
                                @selected(old('sex', $person->sex) === 'Male')
                            >
                                Male
                            </option>

                            <option
                                value="Female"
                                @selected(old('sex', $person->sex) === 'Female')
                            >
                                Female
                            </option>

                        </select>

                    </div>


                    {{-- BIRTH DATE --}}
                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Birth Date
                        </label>

                        <input
                            type="date"
                            name="birth_date"
                            value="{{ old(
                                'birth_date',
                                $person->birth_date?->format('Y-m-d')
                            ) }}"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-green-600
                                   focus:ring-green-600"
                        >

                    </div>


                    {{-- BIRTH PLACE --}}
                    <div class="lg:col-span-2">

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Birth Place
                        </label>

                        <input
                            type="text"
                            name="birth_place"
                            value="{{ old('birth_place', $person->birth_place) }}"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-green-600
                                   focus:ring-green-600"
                        >

                    </div>


                    {{-- CIVIL STATUS --}}
                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Civil Status
                        </label>

                        <select
                            name="civil_status"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-green-600
                                   focus:ring-green-600"
                        >

                            @foreach([
                                'Single',
                                'Married',
                                'Widowed',
                                'Separated',
                                'Annulled'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        old(
                                            'civil_status',
                                            $person->civil_status
                                        ) === $status
                                    )
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- RELIGION --}}
                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Religion
                        </label>

                        <input
                            type="text"
                            name="religion"
                            value="{{ old('religion', $person->religion) }}"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-green-600
                                   focus:ring-green-600"
                        >

                    </div>


                    {{-- CITIZENSHIP --}}
                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Citizenship
                        </label>

                        <input
                            type="text"
                            name="citizenship"
                            value="{{ old('citizenship', $person->citizenship) }}"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-green-600
                                   focus:ring-green-600"
                        >

                    </div>


                    {{-- MODE OF CITIZENSHIP --}}
                    <div>
                        <label for="mode_of_citizenship" class="mb-1 block text-sm font-medium text-gray-700">
                            Mode of Citizenship
                        </label>

                        <select
                            id="mode_of_citizenship"
                            name="mode_of_citizenship"
                            class="w-full rounded-lg border-gray-300
                                focus:border-green-600 focus:ring-green-600"
                        >

                            @foreach ([
                                'By Birth',
                                'By Naturalization',
                            ] as $mode)
                                <option
                                    value="{{ $mode }}"
                                    @selected(old('mode_of_citizenship', $person->mode_of_citizenship) === $mode)
                                >
                                    {{ $mode }}
                                </option>
                            @endforeach
                        </select>

                        @error('mode_of_citizenship')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- BLOOD TYPE --}}
                    <div>
                        <label for="blood_type" class="mb-1 block text-sm font-medium text-gray-700">
                            Blood Type
                        </label>

                        <select
                            id="blood_type"
                            name="blood_type"
                            class="w-full rounded-lg border-gray-300
                                focus:border-green-600 focus:ring-green-600"
                        >
                            
                            @foreach ([
                                'A+',
                                'A-',
                                'B+',
                                'B-',
                                'AB+',
                                'AB-',
                                'O+',
                                'O-',
                                'Unknown',
                            ] as $bloodType)
                                <option
                                    value="{{ $bloodType }}"
                                    @selected(old('blood_type', $person->blood_type) === $bloodType)
                                >
                                    {{ $bloodType }}
                                </option>
                            @endforeach
                        </select>

                        @error('blood_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- HEIGHT --}}
                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Height (m)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="height_m"
                            value="{{ old('height_m', $person->height_m) }}"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-green-600
                                   focus:ring-green-600"
                        >

                    </div>


                    {{-- WEIGHT --}}
                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Weight (kg)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="weight_kg"
                            value="{{ old('weight_kg', $person->weight_kg) }}"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-green-600
                                   focus:ring-green-600"
                        >

                    </div>                    


                    {{-- SPECIALIZATION --}}
                    <div>
                        <label for="specialization" class="mb-1 block text-sm font-medium text-gray-700">
                            Specialization
                        </label>

                        <select
                            id="specialization"
                            name="specialization"
                            class="w-full rounded-lg border-gray-300
                                focus:border-green-600 focus:ring-green-600"
                        >

                            @foreach ([
                                'Early Childhood Education',
                                'English',
                                'Filipino',
                                'General Education',
                                'Information and Communication Technology (ICT)',
                                'Mathematics',
                                'Music, Arts, PE, and Health (MAPEH)',
                                'Science - Biology',
                                'Science - Chemistry',
                                'Science - General Science',
                                'Science - Physical Science',
                                'Science - Physics',
                                'Social Studies / Social Sciences',
                                'Special Education (SPED)',
                                'Technology and Livelihood Education (TLE)',
                                'TVL - Agricultural Crops Production',
                                'TVL - Agroentrepreneurship',
                                'TVL - Animal Production',
                                'TVL - Bartending',
                                'TVL - Beauty Care Services',
                                'TVL - Bookkeeping',
                                'TVL - Bread and Pastry Production',
                                'TVL - Caregiving',
                                'TVL - Carpentry',
                                'TVL - Computer Systems Servicing',
                                'TVL - Contact Center Services',
                                'TVL - Cookery',
                                'TVL - Creative Web Design',
                                'TVL - Dressmaking',
                                'TVL - Driving',
                                'TVL - Electrical Installation and Maintenance',
                                'TVL - Electronic Products Assembly and Servicing',
                                'TVL - Events Management Services',
                                'TVL - Food and Beverage Services',
                                'TVL - Food Processing',
                                'TVL - Front Office Services',
                                'TVL - Hairdressing',
                                'TVL - Household Services',
                                'TVL - Housekeeping',
                                'TVL - Landscape Installation and Maintenance',
                                'TVL - Masonry',
                                'TVL - Organic Agriculture Production',
                                'TVL - Plumbing',
                                'TVL - RAC Servicing (DomRAC)',
                                'TVL - Rice Machinery Operations',
                                'TVL - Security Services',
                                'TVL - Shielded Metal Arc Welding',
                                'TVL - Technical Drafting',
                                'TVL - Technology and Livelihood Education (TLE)',
                                'TVL - Tour Guiding Services',
                                'TVL - Wellness Massage',
                                'Values Education',
                                'Guidance and Counseling',
                                'Accountancy',
                                'Architecture',
                                'Statistics',
                                'Civil Engineering',
                                'Computer Engineering',
                                'Electrical Engineering',
                                'Electronics Engineering',
                                'Mechanical Engineering',
                                'Agricultural Engineering',
                                'Human Resource Management',
                                'Legal Management',
                                'Management Accounting',
                                'Marketing Management',
                                'Nursing',
                                'Office Administration',
                                'Public Administration',
                                'Business Administration',
                                'Psychology',
                                'Criminology',
                                'Commerce',
                                'Other',
                                'Not Applicable',
                            ] as $specialization)
                                <option
                                    value="{{ $specialization }}"
                                    @selected(old('specialization', $person->specialization) === $specialization)
                                >
                                    {{ $specialization }}
                                </option>
                            @endforeach
                        </select>

                        @error('specialization')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- MOBILE --}}
                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Mobile Number
                        </label>

                        <input
                            type="text"
                            name="mobile_number"
                            value="{{ old(
                                'mobile_number',
                                $person->mobile_number
                            ) }}"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-green-600
                                   focus:ring-green-600"
                        >

                    </div>


                    {{-- TELEPHONE --}}
                    <div>

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Telephone Number
                        </label>

                        <input
                            type="text"
                            name="telephone_number"
                            value="{{ old(
                                'telephone_number',
                                $person->telephone_number
                            ) }}"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-green-600
                                   focus:ring-green-600"
                        >

                    </div>

                </div>

            </div>


            {{-- =================================================
            SAVE BUTTON
            ================================================== --}}

            <div
                class="sticky bottom-0
                       flex justify-end gap-3
                       border-t border-gray-200
                       bg-white/95 px-6 py-4
                       shadow-lg backdrop-blur"
            >

                <a
                    href="{{ route('data-management.personnel') }}"
                    class="rounded-lg border border-gray-300
                           bg-white px-5 py-2.5
                           text-sm font-semibold text-gray-700
                           hover:bg-gray-50"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="rounded-lg bg-green-700
                           px-6 py-2.5
                           text-sm font-semibold text-white
                           shadow-sm
                           transition hover:bg-green-800"
                >
                    Save Changes
                </button>

            </div>

        </form>

    </div>

</x-app-layout>