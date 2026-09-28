<x-app-layout>
    <div class="py-6">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- PAGE HEADER --}}
            <div>
                <p class="mb-2 text-sm text-gray-500">
                    Payroll Services / Payroll Inclusion
                </p>

                <h1 class="text-2xl font-bold text-gray-900">
                    Payroll Inclusion
                </h1>

                <p class="mt-1 text-sm text-gray-600">
                    Access the online submission page for payroll inclusion requests.
                </p>
            </div>

            {{-- CONTENT --}}
            <section
                class="overflow-hidden rounded-2xl border
                       border-gray-200 bg-white shadow-sm"
            >
                <div
                    class="px-6 py-5"
                    style="background-color: #166534; color: #ffffff;"
                >
                    <h2 class="text-lg font-bold" style="color: #ffffff;">
                        Online Request for Payroll Inclusion
                    </h2>

                    <p class="mt-1 text-sm" style="color: #dcfce7;">
                        Review the referenced memorandum before submitting your request.
                    </p>
                </div>

                <div class="space-y-6 p-6">

                    {{-- REMARKS --}}
                    <div
                        class="rounded-xl border p-5"
                        style="background-color: #f0fdf4; border-color: #bbf7d0;"
                    >
                        <h3
                            class="text-sm font-bold"
                            style="color: #166534;"
                        >
                            For more information: 
                        </h3>

                        <p class="mt-2 text-sm leading-relaxed" style="color: #374151;">
                            Please refer to
                            <a
                                href="https://depedph-my.sharepoint.com/shared?listurl=https%3A%2F%2Fdepedph%2Dmy%2Esharepoint%2Ecom%2Fpersonal%2Fmemo%5Fleyte%5Fdeped%5Fgov%5Fph%2FDocuments&amp;id=%2Fpersonal%2Fmemo%5Fleyte%5Fdeped%5Fgov%5Fph%2FDocuments%2FDivision%20Memo%2F2026%2FDM%20No%2E%20792%2C%20s%2E%202026%20%2D%20Online%20Submission%20of%20Request%20for%20Payroll%20Inclusion%2Epdf&amp;parent=%2Fpersonal%2Fmemo%5Fleyte%5Fdeped%5Fgov%5Fph%2FDocuments%2FDivision%20Memo%2F2026&amp;shareLink=1&amp;ga=1"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="font-semibold underline"
                                style="color: #15803d;"
                            >
                                Division Memorandum No. 792, s. 2026 –
                                Online Submission of Request for Payroll Inclusion.pdf
                                <span class="sr-only">(opens in a new tab)</span>
                            </a>.
                        </p>
                    </div>

                    {{-- EXTERNAL SUBMISSION LINK --}}
                    <div>
                        <a
                            href="https://tinyurl.com/544f3ryn"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center justify-center gap-2
                                   rounded-lg px-5 py-3 text-sm font-semibold
                                   focus-visible:outline focus-visible:outline-2
                                   focus-visible:outline-offset-2"
                            style="background-color: #15803d; color: #ffffff; border: 1px solid #166534;"
                        >
                            Open Payroll Inclusion Submission

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 3h6v6M10 14L21 3M21 14v5a2 2 0
                                       01-2 2H5a2 2 0 01-2-2V5a2 2 0
                                       012-2h5"
                                />
                            </svg>

                            <span class="sr-only">(opens in a new tab)</span>
                        </a>

                        <p class="mt-2 text-xs text-gray-500">
                            Opens the external submission page in a new tab.
                        </p>
                    </div>

                </div>
            </section>

        </div>
    </div>
</x-app-layout>