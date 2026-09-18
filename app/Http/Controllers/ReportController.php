<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['auth', 'role:super_admin'];
    }

    public function index(): View
    {
        $reports = Report::query()
            ->withCount([
                'submissions',
                'submissions as pending_count' => fn ($query) => $query->where('status', 'Pending'),
                'submissions as done_count' => fn ($query) => $query->where('status', 'Done'),
                'submissions as verified_count' => fn ($query) => $query->where('status', 'Verified'),
            ])
            ->latest()
            ->paginate(15);

        return view('reports.index', compact('reports'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateReport($request);

        DB::transaction(function () use ($data) {
            $report = new Report($data);
            $report->status = 'Ongoing';
            $report->save();

            // Each existing school receives one pending submission record.
            // DISTINCT handles duplicate school identifiers in school_db.
            $schoolIds = DB::table('school_db')
                ->whereNotNull('school_id')
                ->where('school_id', '<>', '')
                ->distinct()
                ->pluck('school_id');

            $now = now();

            foreach ($schoolIds->chunk(500) as $chunk) {
                $rows = $chunk->map(fn ($schoolId) => [
                    'report_id' => $report->id,
                    'school_id' => $schoolId,
                    'user_id' => null,
                    'status' => 'Pending',
                    'validated_by' => null,
                    'validated_at' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all();

                ReportSubmission::insert($rows);
            }
        });

        return redirect()->route('data-management.reports')
            ->with('success', 'Report created with pending entries for existing schools.');
    }

    public function update(Request $request, Report $report): RedirectResponse
    {
        $data = $this->validateReport($request);

        DB::transaction(function () use ($report, $data) {
            $lockedReport = Report::query()->lockForUpdate()->findOrFail($report->id);

            abort_unless($lockedReport->status === 'Ongoing', 409, 'Only ongoing reports can be edited.');

            $lockedReport->fill($data);
            $lockedReport->save();
        });

        return redirect()->route('data-management.reports')
            ->with('success', 'Report updated successfully.');
    }

    public function submissions(Request $request): View
    {
        $filters = $request->validate([
            'report_id' => ['nullable', 'integer', 'exists:reports,id'],
            'school_id' => ['nullable', 'string', 'max:10', 'exists:school_db,school_id'],
            'status' => ['nullable', 'in:Pending,Done,Verified'],
        ]);

        $query = ReportSubmission::with(['report', 'submittedBy', 'validatedBy']);

        foreach (['report_id', 'school_id', 'status'] as $field) {
            if (isset($filters[$field]) && $filters[$field] !== '') {
                $query->where($field, $filters[$field]);
            }
        }

        $submissions = $query->latest()->paginate(15)->withQueryString();
        $reports = Report::orderByDesc('id')->get(['id', 'name_of_report']);
        $schools = DB::table('school_db')->orderBy('school_name')
            ->get(['school_id', 'school_name']);

        return view('reports.submissions', compact('submissions', 'reports', 'schools'));
    }

    public function submit(Request $request, ReportSubmission $submission): RedirectResponse
    {
        DB::transaction(function () use ($request, $submission) {
            // Lock the parent first, consistently with close() and verify().
            $report = Report::query()->lockForUpdate()->findOrFail($submission->report_id);
            $lockedSubmission = ReportSubmission::query()
                ->where('report_id', $report->id)
                ->lockForUpdate()->findOrFail($submission->id);

            abort_unless($report->status === 'Ongoing', 409, 'This report is closed.');
            abort_unless($lockedSubmission->status === 'Pending', 409, 'Only pending submissions can be submitted.');

            $lockedSubmission->status = 'Done';
            $lockedSubmission->user_id = $request->user()->id;
            $lockedSubmission->save();
        });

        return redirect()->route('data-management.reports.submissions', [
            'report_id' => $submission->report_id,
        ])->with('success', 'School report submitted successfully.');
    }

    public function verify(Request $request, ReportSubmission $submission): RedirectResponse
    {
        DB::transaction(function () use ($request, $submission) {
            $report = Report::query()->lockForUpdate()->findOrFail($submission->report_id);
            $lockedSubmission = ReportSubmission::query()
                ->where('report_id', $report->id)
                ->lockForUpdate()->findOrFail($submission->id);

            abort_unless($report->status === 'Ongoing', 409, 'This report is closed.');
            abort_unless($lockedSubmission->status === 'Done', 409, 'Only submitted reports can be verified.');

            $lockedSubmission->status = 'Verified';
            $lockedSubmission->validated_by = $request->user()->id;
            $lockedSubmission->validated_at = now();
            $lockedSubmission->save();
        });

        return redirect()->route('data-management.reports.submissions', [
            'report_id' => $submission->report_id,
        ])->with('success', 'School submission verified successfully.');
    }

    public function close(Report $report): RedirectResponse
    {
        DB::transaction(function () use ($report) {
            $lockedReport = Report::query()->lockForUpdate()->findOrFail($report->id);

            abort_unless($lockedReport->status === 'Ongoing', 409, 'This report is already closed.');

            // Closing is an explicit admin action; school statuses are preserved.
            $lockedReport->status = 'Done';
            $lockedReport->save();
        });

        return redirect()->route('data-management.reports')
            ->with('success', 'Report closed. Further submission and verification are disabled.');
    }

    private function validateReport(Request $request): array
    {
        return $request->validate([
            'name_of_report' => ['required', 'string', 'max:255'],
            'deadline' => ['required', 'date_format:Y-m-d\TH:i'],
            'remarks' => ['nullable', 'string', 'max:10000'],
        ]);
    }
}
