<x-app-layout>
    <style>
        .report-management { width: 100%; min-width: 0; max-width: 1440px; margin: 0 auto; padding: 24px; color: #18283c; }
        .report-management * { box-sizing: border-box; }
        .report-management h1 { margin: 0 0 6px; font-size: 26px; font-weight: 700; color: #14532d; }
        .report-management h2 { margin: 0 0 18px; font-size: 19px; font-weight: 600; }
        .report-management .muted { color: #526277; }
        .report-management .card { min-width: 0; background: white; border: 1px solid #dce8df; border-radius: 12px; padding: 22px; margin-top: 22px; box-shadow: 0 1px 3px rgb(0 0 0 / 4%); }
        .report-management .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
        .report-management label { display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px; }
        .report-management input,
        .report-management select,
        .report-management textarea { width: 100%; min-width: 0; padding: 10px 12px; border: 1px solid #aab7c8; border-radius: 8px; font: inherit; background: white; color: inherit; }
        .report-management textarea { resize: vertical; }
        .report-management button { border: 0; border-radius: 8px; padding: 10px 16px; background: #15803d; color: white; font: inherit; font-weight: 600; cursor: pointer; }
        .report-management button:hover { background: #166534; }
        .report-management :focus-visible { outline: 3px solid #4ade80; outline-offset: 3px; }
        .report-management .actions { display: flex; flex-wrap: wrap; gap: 12px; align-items: center; margin-top: 16px; }
        .report-management .table-wrap { width: 100%; overflow-x: auto; }
        .report-management table { border-collapse: collapse; width: 100%; min-width: 1050px; font-size: 14px; }
        .report-management th,
        .report-management td { text-align: left; padding: 12px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        .report-management th { background: #f0fdf4; color: #166534; font-size: 13px; font-weight: 600; }
        .report-management .badge { display: inline-block; border-radius: 20px; padding: 3px 10px; font-size: 13px; font-weight: 600; }
        .report-management .pending { background: #fff3cd; color: #715400; }
        .report-management .done { background: #e0edff; color: #174b96; }
        .report-management .verified { background: #dcfce7; color: #166534; }
        .report-management .notice { padding: 14px 18px; border-radius: 8px; margin-top: 18px; background: #dcfce7; color: #166534; }
        .report-management .errors { background: #fee2e2; color: #991b1b; }
        .report-management .errors ul { margin: 8px 0 0; padding-left: 20px; list-style: disc; }
        .report-management .remarks { white-space: pre-wrap; overflow-wrap: anywhere; max-width: 250px; }
        .report-management details { margin: 8px 0; }
        .report-management summary { cursor: pointer; color: #166534; font-weight: 600; }
        .report-management .edit-panel { padding: 18px; background: #f8faf9; border-radius: 8px; margin-top: 10px; }
        .report-management .empty { text-align: center; padding: 30px; }
        .report-management .pagination { margin-top: 20px; }
        @media (max-width: 650px) {
            .report-management { padding: 16px 12px; }
            .report-management .grid { grid-template-columns: 1fr; }
            .report-management .card { padding: 16px; }
        }
    </style>
    <style>
        .report-management .wide { grid-column: 1 / -1; }
        .report-management .heading { display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; }
        .report-management .link { color: #166534; font-weight: 600; text-decoration: underline; text-underline-offset: 3px; }
        .report-management .ongoing { background: #dcfce7; color: #166534; }
        .report-management .counts { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
        .report-management .row-actions { display: flex; flex-direction: column; align-items: flex-start; gap: 12px; }
        .report-management .close-button { background: #fff7ed; color: #9a3412; border: 1px solid #fed7aa; }
        .report-management .close-button:hover { background: #ffedd5; }
        .report-management .help { font-size: 13px; margin-top: 6px; }
    </style>

    <div class="report-management">
        <div class="heading">
            <div>
                <h1>Report Management</h1>
                <p class="muted">Create reports and monitor school submissions.</p>
            </div>
            <a class="link" href="{{ route('data-management.reports.submissions') }}">View all submissions</a>
        </div>
        <p class="muted help">Deadline timezone: {{ config('app.timezone') }}</p>

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

        {{-- Routes and controller enforce super_admin access. --}}
        @if (auth()->user()?->role === 'super_admin')
            <section class="card" aria-labelledby="create-heading">
                <h2 id="create-heading">New report</h2>
                @php($creating = old('_form') === 'create')
                <form method="POST" action="{{ route('reports.store') }}">
                    @csrf
                    <input type="hidden" name="_form" value="create">

                    <div class="grid">
                        <div>
                            <label for="name_of_report">Name of report</label>
                            <input id="name_of_report" name="name_of_report" maxlength="255"
                                   value="{{ $creating ? old('name_of_report') : '' }}" required>
                        </div>
                        <div>
                            <label for="deadline">Deadline date and time</label>
                            <input type="datetime-local" id="deadline" name="deadline" step="60"
                                   value="{{ $creating ? old('deadline') : '' }}" required>
                        </div>
                        <div class="wide">
                            <label for="remarks">Remarks <span class="muted">(optional)</span></label>
                            <textarea id="remarks" name="remarks" rows="3" maxlength="10000">{{ $creating ? old('remarks') : '' }}</textarea>
                        </div>
                    </div>

                    <p class="muted help">New reports start as Ongoing. A Pending submission entry is created for every existing school.</p>
                    <div class="actions">
                        <button type="submit">Create report</button>
                    </div>
                </form>
            </section>
        @endif

        <section class="card" aria-labelledby="list-heading">
            <div class="heading">
                <h2 id="list-heading">Report list</h2>
                <span class="muted">{{ $reports->total() }} total reports</span>
            </div>
            <div class="table-wrap" role="region" aria-label="Report list" tabindex="0">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">Report ID</th>
                            <th scope="col">Name of report</th>
                            <th scope="col">Deadline</th>
                            <th scope="col">Status</th>
                            <th scope="col">Remarks</th>
                            <th scope="col">School submissions</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $report)
                            <tr>
                                <td>{{ $report->id }}</td>
                                <td>{{ $report->name_of_report }}</td>
                                <td>{{ $report->deadline?->format('M d, Y h:i A') ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ $report->status === 'Ongoing' ? 'ongoing' : 'done' }}">
                                        {{ $report->status }}
                                    </span>
                                </td>
                                <td class="remarks">{{ $report->remarks ?? '—' }}</td>
                                <td>
                                    <strong>{{ $report->submissions_count }} schools</strong>
                                    <div class="counts">
                                        <span class="badge pending">Pending: {{ $report->pending_count }}</span>
                                        <span class="badge done">Done: {{ $report->done_count }}</span>
                                        <span class="badge verified">Verified: {{ $report->verified_count }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <a class="link"
                                           href="{{ route('data-management.reports.submissions', ['report_id' => $report->id]) }}"
                                           aria-label="View submissions for report {{ $report->id }}">
                                            View submissions
                                        </a>
                                        @if (auth()->user()?->role === 'super_admin' && $report->status === 'Ongoing')
                                            <form method="POST" action="{{ route('reports.close', $report) }}"
                                                  onsubmit="return confirm('Close this report? Schools may still have pending submissions. Further submission and verification will be disabled.');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="close-button"
                                                        aria-label="Close report {{ $report->id }}">Close report</button>
                                            </form>
                                        @elseif ($report->status === 'Done')
                                            <span class="muted">Closed</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            @if (auth()->user()?->role === 'super_admin' && $report->status === 'Ongoing')
                                @php($editing = old('_form') === 'edit-'.$report->id)
                                <tr>
                                    <td colspan="7">
                                        <details @if($editing) open @endif>
                                            <summary>Edit report #{{ $report->id }}</summary>
                                            <form class="edit-panel" method="POST" action="{{ route('reports.update', $report) }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="_form" value="edit-{{ $report->id }}">

                                                <div class="grid">
                                                    <div>
                                                        <label for="name-{{ $report->id }}">Name of report</label>
                                                        <input id="name-{{ $report->id }}" name="name_of_report" maxlength="255"
                                                               value="{{ $editing ? old('name_of_report') : $report->name_of_report }}" required>
                                                    </div>
                                                    <div>
                                                        <label for="deadline-{{ $report->id }}">Deadline date and time</label>
                                                        <input type="datetime-local" id="deadline-{{ $report->id }}" name="deadline" step="60"
                                                               value="{{ $editing ? old('deadline') : $report->deadline?->format('Y-m-d\TH:i') }}" required>
                                                    </div>
                                                    <div class="wide">
                                                        <label for="remarks-{{ $report->id }}">Remarks <span class="muted">(optional)</span></label>
                                                        <textarea id="remarks-{{ $report->id }}" name="remarks" rows="3" maxlength="10000">{{ $editing ? old('remarks') : $report->remarks }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="actions">
                                                    <button type="submit">Save changes</button>
                                                </div>
                                            </form>
                                        </details>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="7" class="empty muted">No reports yet. Create a report to start tracking school submissions.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($reports->hasPages())
                <div class="pagination">{{ $reports->links() }}</div>
            @endif
        </section>
    </div>
</x-app-layout>
