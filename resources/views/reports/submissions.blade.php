<x-app-layout>
    <style>
        .report-submissions { width: 100%; min-width: 0; max-width: 1500px; margin: auto; padding: 24px; color: #1f2937; }
        .report-submissions .heading { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; }
        .report-submissions h1 { margin: 0; font-size: 26px; font-weight: 700; color: #14532d; }
        .report-submissions h2 { margin: 0; font-size: 18px; font-weight: 600; }
        .report-submissions .muted { color: #64748b; }
        .report-submissions .card { margin-top: 22px; padding: 22px; background: #fff; border: 1px solid #dce8df; border-radius: 12px; }
        .report-submissions .filters { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; }
        .report-submissions label { display: block; margin-bottom: 6px; font-size: 14px; font-weight: 600; }
        .report-submissions select { width: 100%; min-width: 0; padding: 10px 12px; border: 1px solid #b8c5bd; border-radius: 8px; background: white; color: #1f2937; }
        .report-submissions .actions { display: flex; gap: 14px; flex-wrap: wrap; align-items: center; margin-top: 16px; }
        .report-submissions .button { display: inline-flex; align-items: center; justify-content: center; padding: 10px 16px; border: none; border-radius: 8px; background: #15803d; color: white; font-weight: 600; font-size: 14px; cursor: pointer; white-space: nowrap; }
        .report-submissions .button:hover { background: #166534; }
        .report-submissions .link { color: #166534; font-weight: 600; text-decoration: underline; text-underline-offset: 3px; }
        .report-submissions :focus-visible { outline: 3px solid #4ade80; outline-offset: 3px; }
        .report-submissions .notice { margin-top: 18px; border-radius: 8px; padding: 14px 18px; background: #dcfce7; color: #166534; }
        .report-submissions .errors { background: #fee2e2; color: #991b1b; }
        .report-submissions .errors ul { list-style: disc; padding-left: 20px; }
        .report-submissions .table-wrap { width: 100%; overflow-x: auto; margin-top: 18px; }
        .report-submissions table { width: 100%; min-width: 1150px; border-collapse: collapse; font-size: 14px; }
        .report-submissions th, .report-submissions td { padding: 14px 12px; text-align: left; vertical-align: top; border-bottom: 1px solid #e2e8f0; }
        .report-submissions th { background: #f0fdf4; color: #166534; font-weight: 600; }
        .report-submissions .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .report-submissions .pending { background: #fef3c7; color: #92400e; }
        .report-submissions .done { background: #dbeafe; color: #1e40af; }
        .report-submissions .verified { background: #dcfce7; color: #166534; }
        .report-submissions .empty { padding: 36px; text-align: center; color: #64748b; }
        .report-submissions .pagination { margin-top: 20px; }
        @media (max-width: 760px) {
            .report-submissions { padding: 16px 12px; }
            .report-submissions .card { padding: 16px; }
            .report-submissions .filters { grid-template-columns: 1fr; }
        }
    </style>

    @php
        $schoolNames = $schools->pluck('school_name', 'school_id');
    @endphp

    <div class="report-submissions">
        <div class="heading">
            <div>
                <h1>Report Submissions</h1>
                <p class="muted">Monitor school submissions and review validation details.</p>
            </div>
            <a class="link" href="{{ route('data-management.reports') }}">Back to report list</a>
        </div>

        @if (session('success'))
            <div class="notice" role="status">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="notice errors" role="alert">
                <strong>Please check the following:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="card" aria-label="Filter submissions">
            <form method="GET" action="{{ route('data-management.reports.submissions') }}">
                <div class="filters">
                    <div>
                        <label for="filter-report">Report</label>
                        <select id="filter-report" name="report_id">
                            <option value="">All reports</option>
                            @foreach ($reports as $report)
                                <option value="{{ $report->id }}" @selected((string) request('report_id') === (string) $report->id)>
                                    #{{ $report->id }} — {{ $report->name_of_report }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filter-school">School</label>
                        <select id="filter-school" name="school_id">
                            <option value="">All schools</option>
                            @foreach ($schools->unique('school_id') as $school)
                                <option value="{{ $school->school_id }}" @selected((string) request('school_id') === (string) $school->school_id)>
                                    {{ $school->school_id }} — {{ $school->school_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filter-status">Submission status</label>
                        <select id="filter-status" name="status">
                            <option value="">All statuses</option>
                            @foreach (['Pending', 'Done', 'Verified'] as $status)
                                <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="actions">
                    <button class="button" type="submit">Apply filters</button>
                    <a class="link" href="{{ route('data-management.reports.submissions') }}">Reset</a>
                </div>
            </form>
        </section>

        <section class="card" aria-labelledby="submissions-heading">
            <div class="heading">
                <h2 id="submissions-heading">School submissions</h2>
                <span class="muted">{{ number_format($submissions->total()) }} matching records</span>
            </div>
            <p class="muted" style="margin-top: 8px; font-size: 13px;">
                Pending: awaiting submission. Done: submitted for review. Verified: validated.
                Validation times are shown in Philippine time (Asia/Manila).
            </p>

            <div class="table-wrap" role="region" aria-label="School submissions table" tabindex="0">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Report</th>
                            <th scope="col">School</th>
                            <th scope="col">Deadline</th>
                            <th scope="col">Status</th>
                            <th scope="col">Submitted by</th>
                            <th scope="col">Validated by</th>
                            <th scope="col">Validated at (PH)</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($submissions as $submission)
                            <tr>
                                <td>{{ $submission->id }}</td>
                                <td>
                                    <strong>{{ $submission->report?->name_of_report ?? 'Report unavailable' }}</strong>
                                    <div class="muted">Report #{{ $submission->report_id }} · {{ $submission->report?->status ?? 'Unavailable' }}</div>
                                </td>
                                <td>
                                    {{ $schoolNames->get($submission->school_id, 'School unavailable') }}
                                    <div class="muted">{{ $submission->school_id }}</div>
                                </td>
                                <td>{{ $submission->report?->deadline?->format('M j, Y • g:i A') ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ ['Pending' => 'pending', 'Done' => 'done', 'Verified' => 'verified'][$submission->status] ?? '' }}">
                                        {{ $submission->status }}
                                    </span>
                                </td>
                                <td>{{ $submission->submittedBy?->name ?? '—' }}</td>
                                <td>{{ $submission->validatedBy?->name ?? '—' }}</td>
                                <td>{{ $submission->validated_at?->copy()->timezone('Asia/Manila')->format('M j, Y • g:i A') ?? '—' }}</td>
                                <td>
                                    @if ($submission->status === 'Verified')
                                        <span class="muted">Validated</span>
                                    @elseif ($submission->report?->status !== 'Ongoing')
                                        <span class="muted">Report closed or unavailable</span>
                                    @elseif (auth()->user()?->role === 'super_admin' && $submission->status === 'Pending')
                                        <form method="POST" action="{{ route('reports.submit', $submission) }}"
                                              onsubmit="return confirm('Submit this school’s report? Your account will be recorded as the submitter.');">
                                            @csrf
                                            @method('PATCH')
                                            <button class="button" type="submit" aria-label="Submit school submission {{ $submission->id }}">Submit</button>
                                        </form>
                                    @elseif (auth()->user()?->role === 'super_admin' && $submission->status === 'Done')
                                        <form method="POST" action="{{ route('reports.verify', $submission) }}"
                                              onsubmit="return confirm('Verify this school’s submission? Your account and the current time will be recorded.');">
                                            @csrf
                                            @method('PATCH')
                                            <button class="button" type="submit" aria-label="Verify school submission {{ $submission->id }}">Verify</button>
                                        </form>
                                    @else
                                        <span class="muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="empty">No submissions match these filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($submissions->hasPages())
                <div class="pagination">{{ $submissions->links() }}</div>
            @endif
        </section>
    </div>
</x-app-layout>
