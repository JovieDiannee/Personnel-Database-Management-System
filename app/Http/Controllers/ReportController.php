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

                'submissions as pending_count' => fn ($query) =>
                    $query->where('status', 'Pending'),

                'submissions as done_count' => fn ($query) =>
                    $query->where('status', 'Done'),

                'submissions as verified_count' => fn ($query) =>
                    $query->where('status', 'Verified'),
            ])
            ->latest()
            ->paginate(15);

        // Count assigned Public and Private schools for each report.
        $schoolCounts = DB::table('report_submissions as submissions')
            ->join(
                'school_db as schools',
                'schools.school_id',
                '=',
                'submissions.school_id'
            )
            ->whereIn(
                'submissions.report_id',
                $reports->getCollection()->modelKeys()
            )
            ->select('submissions.report_id')
            ->selectRaw("
                COUNT(DISTINCT CASE
                    WHEN LOWER(TRIM(schools.school_sector)) = 'public'
                    THEN submissions.school_id
                END) AS public_count
            ")
            ->selectRaw("
                COUNT(DISTINCT CASE
                    WHEN LOWER(TRIM(schools.school_sector)) = 'private'
                    THEN submissions.school_id
                END) AS private_count
            ")
            ->selectRaw("
                COUNT(DISTINCT CASE
                    WHEN LOWER(TRIM(schools.school_sector)) = 'sucslucs'
                    THEN submissions.school_id
                END) AS sucs_lucs_count
            ")
            ->groupBy('submissions.report_id')
            ->get()
            ->keyBy('report_id');

        foreach ($reports as $report) {
            $counts = $schoolCounts->get($report->id);

            $report->public_school_count = (int) (
                $counts?->public_count ?? 0
            );

            $report->private_school_count = (int) (
                $counts?->private_count ?? 0
            );

            $report->sucs_lucs_school_count = (int) (
                $counts?->sucs_lucs_count ?? 0
            );
        }

        return view('reports.index', compact('reports'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->role === 'super_admin', 403);

        $data = $this->validateReport($request);

        $sectorData = $request->validate([
            'school_sector' => [
                'required',
                'in:Public,Private,SUCsLUCs,All',
            ],
        ]);

        $sector = $sectorData['school_sector'];

        DB::transaction(function () use ($data, $sector) {
            $schoolQuery = DB::table('school_db')
                ->whereNotNull('school_id')
                ->where('school_id', '<>', '');

            if ($sector !== 'All') {
                $schoolQuery->where('school_sector', $sector);
            }

            $schoolIds = $schoolQuery
                ->distinct()
                ->pluck('school_id');

            if ($schoolIds->isEmpty()) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'school_sector' => 'No schools were found for the selected sector.',
                ]);
            }

            $report = new Report($data);
            $report->status = 'Ongoing';
            $report->save();

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

        return redirect()
            ->route('data-management.reports')
            ->with(
                'success',
                "Report created for schools in the selected sector: {$sector}."
            );
    }

    public function update(Request $request, Report $report): RedirectResponse 
    {
        abort_unless(
            $request->user()?->role === 'super_admin',
            403
        );

        $data = $this->validateReport($request);

        $sectorData = $request->validate([
            'school_sector' => [
                'nullable',
                'in:Public,Private,SUCsLUCs,All',
            ],
        ]);

        $sector = $sectorData['school_sector'] ?? null;

        // Sector controls assignments; it is not a reports table column.
        unset($data['school_sector']);

        DB::transaction(function () use ($report, $data, $sector) {
            $lockedReport = Report::query()
                ->lockForUpdate()
                ->findOrFail($report->id);

            abort_unless(
                $lockedReport->status === 'Ongoing',
                409,
                'Only ongoing reports can be edited.'
            );

            if ($sector !== null && $sector !== '') {
                $schoolQuery = DB::table('school_db')
                    ->whereNotNull('school_id')
                    ->where('school_id', '<>', '');

                if ($sector !== 'All') {
                    $schoolQuery->where('school_sector', $sector);
                }

                $schoolIds = $schoolQuery
                    ->distinct()
                    ->pluck('school_id')
                    ->map(fn ($id) => (string) $id);

                if ($schoolIds->isEmpty()) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'school_sector' =>
                            'No schools were found for the selected sector.',
                    ]);
                }

                $existing = ReportSubmission::query()
                    ->where('report_id', $lockedReport->id)
                    ->lockForUpdate()
                    ->get();

                $targetSchoolIds = array_fill_keys(
                    $schoolIds->all(),
                    true
                );

                $excluded = $existing->filter(
                    fn ($submission) =>
                        !isset($targetSchoolIds[(string) $submission->school_id])
                );

                // Do not erase submitted or validated information.
                $hasProtectedRecords = $excluded->contains(
                    fn ($submission) =>
                        $submission->status !== 'Pending'
                        || $submission->user_id !== null
                        || $submission->validated_by !== null
                        || $submission->validated_at !== null
                );

                if ($hasProtectedRecords) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'school_sector' =>
                            'This change would remove schools with submission or validation details. Their records must be retained.',
                    ]);
                }

                // Remove only excluded, untouched Pending entries.
                if ($excluded->isNotEmpty()) {
                    ReportSubmission::query()
                        ->where('report_id', $lockedReport->id)
                        ->whereIn('id', $excluded->modelKeys())
                        ->delete();
                }

                $existingSchoolIds = $existing
                    ->pluck('school_id')
                    ->map(fn ($id) => (string) $id);

                $newSchoolIds = $schoolIds->diff($existingSchoolIds);
                $now = now();

                foreach ($newSchoolIds->chunk(500) as $chunk) {
                    $rows = $chunk->map(fn ($schoolId) => [
                        'report_id' => $lockedReport->id,
                        'school_id' => $schoolId,
                        'user_id' => null,
                        'status' => 'Pending',
                        'validated_by' => null,
                        'validated_at' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])->values()->all();

                    ReportSubmission::insert($rows);
                }
            }

            $lockedReport->fill($data);
            $lockedReport->save();
        });

        return redirect()
            ->route('data-management.reports')
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

    public function revertValidation(Request $request,ReportSubmission $submission): RedirectResponse 
    {
        abort_unless(
            $request->user()?->role === 'super_admin',
            403
        );

        DB::transaction(function () use ($submission) {
            $report = Report::query()
                ->lockForUpdate()
                ->findOrFail($submission->report_id);

            $lockedSubmission = ReportSubmission::query()
                ->where('report_id', $report->id)
                ->lockForUpdate()
                ->findOrFail($submission->id);

            if ($report->status !== 'Ongoing') {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'submission' => 'Validation cannot be reverted while the report is closed.',
                ]);
            }

            if ($lockedSubmission->status !== 'Verified') {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'submission' => 'Only verified submissions can be reverted.',
                ]);
            }

            $lockedSubmission->status = 'Pending';
            $lockedSubmission->user_id = null;
            $lockedSubmission->validated_by = null;
            $lockedSubmission->validated_at = null;
            $lockedSubmission->save();
        });

        return redirect()
            ->route('data-management.reports.submissions', [
                'report_id' => $submission->report_id,
                'school_id' => $submission->school_id,
            ])
            ->with(
                'success',
                'Validation reverted. The school can correct its information and resubmit the report.'
            );
    }
}
