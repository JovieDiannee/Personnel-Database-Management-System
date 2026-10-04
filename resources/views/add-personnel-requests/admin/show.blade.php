<x-app-layout>

    <div class="py-6">

        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

            {{-- BACK --}}

            <div class="mb-5">

                <a
                    href="{{ route('admin.personnel-requests.index') }}"
                    class="text-sm font-medium text-green-700
                           hover:text-green-900"
                >
                    ← Back to Personnel Requests
                </a>

            </div>


            {{-- ALERTS --}}

            @if (session('error'))

                <div
                    class="mb-6 rounded-xl border border-red-200
                           bg-red-50 p-4 text-sm text-red-700"
                >
                    {{ session('error') }}
                </div>

            @endif


            @if ($errors->any())

                <div
                    class="mb-6 rounded-xl border border-red-200
                           bg-red-50 p-4"
                >

                    @foreach ($errors->all() as $error)

                        <p class="text-sm text-red-700">
                            {{ $error }}
                        </p>

                    @endforeach

                </div>

            @endif


            {{-- MAIN CARD --}}

            <div
                class="overflow-hidden rounded-2xl border
                       border-gray-200 bg-white shadow-sm"
            >

                {{-- HEADER --}}

                <div
                    class="flex flex-col gap-4 bg-green-800
                           px-6 py-5 text-white
                           sm:flex-row sm:items-center
                           sm:justify-between"
                >

                    <div>

                        <h1 class="text-xl font-bold">
                            Personnel Request Review
                        </h1>

                        <p class="mt-1 text-sm text-green-100">
                            Request #{{ $personnelRequest->id }}
                        </p>

                    </div>


                    @if ($personnelRequest->status === 'pending')

                        <span
                            class="w-fit rounded-full bg-yellow-100
                                   px-4 py-1.5 text-xs font-bold
                                   text-gray-800"
                        >
                            Pending Review
                        </span>

                    @elseif ($personnelRequest->status === 'approved')

                        <span
                            class="w-fit rounded-full bg-green-100
                                   px-4 py-1.5 text-xs font-bold
                                   text-green-800"
                        >
                            Approved
                        </span>

                    @else

                        <span
                            class="w-fit rounded-full bg-red-100
                                   px-4 py-1.5 text-xs font-bold
                                   text-red-700"
                        >
                            Disapproved
                        </span>

                    @endif

                </div>


                <div class="p-6">


                    {{-- ====================================================
                        PERSONAL INFORMATION
                    ==================================================== --}}

                    <section class="mb-8">

                        <h2
                            class="mb-4 border-b border-gray-200
                                   pb-3 text-lg font-bold text-gray-900"
                        >
                            Personal Information
                        </h2>


                        <div
                            class="grid grid-cols-1 gap-x-8 gap-y-5
                                   sm:grid-cols-2 lg:grid-cols-3"
                        >

                            <div>

                                <p class="text-xs font-semibold uppercase text-gray-400">
                                    Complete Name
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $personnelRequest->first_name }}
                                    {{ $personnelRequest->middle_name }}
                                    {{ $personnelRequest->last_name }}
                                    {{ $personnelRequest->extension_name }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs font-semibold uppercase text-gray-400">
                                    Email
                                </p>

                                <p class="mt-1 text-gray-800">
                                    {{ $personnelRequest->email }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs font-semibold uppercase text-gray-400">
                                    Sex
                                </p>

                                <p class="mt-1 text-gray-800">
                                    {{ $personnelRequest->sex ?: '—' }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs font-semibold uppercase text-gray-400">
                                    Birth Date
                                </p>

                                <p class="mt-1 text-gray-800">
                                    {{ $personnelRequest->birth_date
                                        ? $personnelRequest->birth_date->format('F d, Y')
                                        : '—'
                                    }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs font-semibold uppercase text-gray-400">
                                    Birth Place
                                </p>

                                <p class="mt-1 text-gray-800">
                                    {{ $personnelRequest->birth_place ?: '—' }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs font-semibold uppercase text-gray-400">
                                    Mobile Number
                                </p>

                                <p class="mt-1 text-gray-800">
                                    {{ $personnelRequest->mobile_number ?: '—' }}
                                </p>

                            </div>

                        </div>

                    </section>


                    {{-- ====================================================
                        EMPLOYMENT
                    ==================================================== --}}

                    <section class="mb-8">

                        <h2
                            class="mb-4 border-b border-gray-200
                                   pb-3 text-lg font-bold text-gray-900"
                        >
                            Employment Information
                        </h2>


                        <div
                            class="grid grid-cols-1 gap-x-8 gap-y-5
                                   sm:grid-cols-2 lg:grid-cols-3"
                        >

                            <div>

                                <p class="text-xs font-semibold uppercase text-gray-400">
                                    School
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $personnelRequest->school?->school_name ?? '—' }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    {{ $personnelRequest->school?->school_district }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs font-semibold uppercase text-gray-400">
                                    Plantilla Item
                                </p>

                                <p class="mt-1 text-gray-800">
                                    {{ $personnelRequest->plantilla?->item_number ?? '—' }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs font-semibold uppercase text-gray-400">
                                    Position
                                </p>

                                <p class="mt-1 text-gray-800">
                                    {{ $personnelRequest->plantilla?->position_title ?? '—' }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs font-semibold uppercase text-gray-400">
                                    Employment Status
                                </p>

                                <p class="mt-1 text-gray-800">
                                    {{ $personnelRequest->employment_status ?: '—' }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs font-semibold uppercase text-gray-400">
                                    Nature of Work
                                </p>

                                <p class="mt-1 text-gray-800">
                                    {{ $personnelRequest->nature_of_work ?: '—' }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs font-semibold uppercase text-gray-400">
                                    Monthly Salary
                                </p>

                                <p class="mt-1 text-gray-800">

                                    @if ($personnelRequest->monthly_salary)

                                        ₱{{ number_format(
                                            $personnelRequest->monthly_salary,
                                            2
                                        ) }}

                                    @else

                                        —

                                    @endif

                                </p>

                            </div>

                        </div>

                    </section>


                    {{-- ====================================================
                        SUBMISSION INFORMATION
                    ==================================================== --}}

                    <section class="mb-8">

                        <h2
                            class="mb-4 border-b border-gray-200
                                   pb-3 text-lg font-bold text-gray-900"
                        >
                            Request Information
                        </h2>


                        <div class="grid gap-5 sm:grid-cols-2">

                            <div>

                                <p class="text-xs font-semibold uppercase text-gray-400">
                                    Submitted By
                                </p>

                                <p class="mt-1 font-medium text-gray-900">
                                    {{ $personnelRequest->requester?->name ?? '—' }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs font-semibold uppercase text-gray-400">
                                    Date Submitted
                                </p>

                                <p class="mt-1 text-gray-800">
                                    {{ $personnelRequest->created_at
                                    ->copy()
                                    ->timezone('Asia/Manila')
                                    ->format('F d, Y h:i A') }}
                                </p>

                            </div>

                        </div>


                        @if ($personnelRequest->request_remarks)

                            <div class="mt-5 rounded-lg bg-gray-50 p-4">

                                <p class="text-xs font-semibold uppercase text-gray-400">
                                    Request Remarks
                                </p>

                                <p class="mt-2 text-sm text-gray-700">
                                    {{ $personnelRequest->request_remarks }}
                                </p>

                            </div>

                        @endif

                    </section>


                    {{-- ====================================================
                        PENDING - APPROVAL FORM
                    ==================================================== --}}

                    @if ($personnelRequest->status === 'pending')

                        <section
                            class="rounded-xl border border-gray-200
                                   bg-gray-50 p-5"
                        >

                            <label
                                for="review_remarks"
                                class="mb-2 block text-sm font-semibold
                                       text-gray-800"
                            >
                                Personnel Unit Review Remarks
                            </label>

                            <textarea
                                id="review_remarks"
                                name="review_remarks_display"
                                rows="4"
                                placeholder="Enter remarks. Remarks are required when disapproving..."
                                class="w-full rounded-lg border-gray-300
                                       focus:border-green-600
                                       focus:ring-green-600"
                            >{{ old('review_remarks') }}</textarea>


                            <div
                                class="mt-5 flex flex-col-reverse gap-3
                                       sm:flex-row sm:justify-end"
                            >

                                {{-- DISAPPROVE --}}

                                <form
                                    id="disapproveForm"
                                    method="POST"
                                    action="{{ route(
                                        'admin.personnel-requests.disapprove',
                                        $personnelRequest
                                    ) }}"
                                >

                                    @csrf

                                    <input
                                        type="hidden"
                                        name="review_remarks"
                                        id="disapproveRemarks"
                                    >

                                    <button
                                        type="submit"
                                        class="w-full rounded-lg bg-red-600
                                               px-6 py-2.5 text-sm font-semibold
                                               text-white hover:bg-red-700"
                                    >
                                        Disapprove
                                    </button>

                                </form>


                                {{-- APPROVE --}}

                                <form
                                    id="approveForm"
                                    method="POST"
                                    action="{{ route(
                                        'admin.personnel-requests.approve',
                                        $personnelRequest
                                    ) }}"
                                >

                                    @csrf

                                    <input
                                        type="hidden"
                                        name="review_remarks"
                                        id="approveRemarks"
                                    >

                                    <button
                                        type="submit"
                                        class="w-full rounded-lg bg-green-700
                                               px-6 py-2.5 text-sm font-semibold
                                               text-white hover:bg-green-800"
                                    >
                                        Approve & Create Personnel
                                    </button>

                                </form>

                            </div>

                        </section>


                        {{-- COPY REMARKS INTO CORRECT FORM --}}

                        <script>

                            const remarks =
                                document.getElementById('review_remarks');

                            const approveForm =
                                document.getElementById('approveForm');

                            const disapproveForm =
                                document.getElementById('disapproveForm');


                            approveForm.addEventListener(
                                'submit',
                                function () {

                                    document.getElementById(
                                        'approveRemarks'
                                    ).value = remarks.value;

                                    return confirm(
                                        'Approve this personnel request? ' +
                                        'This will create the official PDMS personnel account.'
                                    );
                                }
                            );


                            disapproveForm.addEventListener(
                                'submit',
                                function (event) {

                                    if (!remarks.value.trim()) {

                                        event.preventDefault();

                                        alert(
                                            'Please provide a reason for disapproval.'
                                        );

                                        remarks.focus();

                                        return;
                                    }


                                    document.getElementById(
                                        'disapproveRemarks'
                                    ).value = remarks.value;


                                    if (
                                        !confirm(
                                            'Disapprove this personnel request?'
                                        )
                                    ) {
                                        event.preventDefault();
                                    }
                                }
                            );

                        </script>


                    @else

                        {{-- =================================================
                            ALREADY REVIEWED
                        ================================================= --}}

                        <section
                            class="rounded-xl border border-gray-200
                                   bg-gray-50 p-5"
                        >

                            <h3 class="font-bold text-gray-900">
                                Review Result
                            </h3>


                            <div
                                class="mt-4 grid gap-5
                                       sm:grid-cols-2"
                            >

                                <div>

                                    <p
                                        class="text-xs font-semibold
                                               uppercase text-gray-400"
                                    >
                                        Reviewed By
                                    </p>

                                    <p class="mt-1 text-gray-800">
                                        {{ $personnelRequest->reviewer?->name ?? '—' }}
                                    </p>

                                </div>


                                <div>

                                    <p
                                        class="text-xs font-semibold
                                               uppercase text-gray-400"
                                    >
                                        Reviewed At
                                    </p>

                                    <p class="mt-1 text-gray-800">

                                        {{ $personnelRequest->reviewed_at
                                            ? $personnelRequest->reviewed_at
                                                ->format('F d, Y h:i A')
                                            : '—'
                                        }}

                                    </p>

                                </div>

                            </div>


                            @if ($personnelRequest->review_remarks)

                                <div class="mt-5">

                                    <p
                                        class="text-xs font-semibold
                                               uppercase text-gray-400"
                                    >
                                        Remarks
                                    </p>

                                    <p class="mt-2 text-sm text-gray-700">
                                        {{ $personnelRequest->review_remarks }}
                                    </p>

                                </div>

                            @endif

                        </section>

                    @endif

                </div>

            </div>

        </div>

    </div>

</x-app-layout>