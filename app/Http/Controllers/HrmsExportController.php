<?php
namespace App\Http\Controllers;

use App\Exports\HrmsPersonnelExport;
use App\Models\SchoolDb;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class HrmsExportController extends Controller
{
    private function filters(Request $request): array
    {
        abort_unless($request->user()?->role === 'super_admin', 403);
        return $request->validate([
            'school_db_id' => ['nullable', 'integer', Rule::exists(SchoolDb::class, 'id')],
            'school_district' => ['nullable', 'string', 'max:255', Rule::exists(SchoolDb::class, 'school_district')],
            'school_area' => ['nullable', 'string', 'max:255', Rule::exists(SchoolDb::class, 'school_area')],
        ]);
    }

    private function query(array $filters): Builder
    {
        $query = User::query()
            ->whereNull('users.deleted_at')
            ->whereIn('users.role', ['user', 'admin'])
            ->where('users.id', '<>', 1)
            ->whereHas('basicInformation')
            ->whereDoesntHave('basicInformation.issuedId', fn ($q) => $q->where('employee_id', '1000001'));

        // All chosen conditions must match the same school assignment.
        if (collect($filters)->contains(fn ($value) => $value !== null && $value !== '')) {
            $query->whereHas('employmentStatus', function ($employment) use ($filters) {
                if (!empty($filters['school_db_id'])) {
                    $employment->where('school_db_id', $filters['school_db_id']);
                }
                if (!empty($filters['school_district']) || !empty($filters['school_area'])) {
                    $employment->whereHas('school', function ($school) use ($filters) {
                        foreach (['school_district', 'school_area'] as $field) {
                            if (isset($filters[$field]) && $filters[$field] !== '') {
                                $school->where($field, $filters[$field]);
                            }
                        }
                    });
                }
            });
        }
        return $query->with([
            'basicInformation.issuedId',
            'employmentStatus.school',
            'employmentStatus.plantilla',
            'medicalAllowance' => fn ($q) => $q->where('year', now('Asia/Manila')->year),
        ])->orderBy('users.id');
    }

    public function index(Request $request)
    {
        $filters = $this->filters($request);
        return view('reports.export-data-for-hrms', [
            'records' => $this->query($filters)->paginate(15)->withQueryString(),
            'filters' => $filters,
            'schools' => SchoolDb::query()->orderBy('school_name')->get(['id', 'school_id', 'school_name']),
            'districts' => SchoolDb::query()->whereNotNull('school_district')->where('school_district', '<>', '')
                ->distinct()->orderBy('school_district')->pluck('school_district'),
            'areas' => SchoolDb::query()->whereNotNull('school_area')->where('school_area', '<>', '')
                ->distinct()->orderBy('school_area')->pluck('school_area'),
        ]);
    }

    public function download(Request $request)
    {
        $filters = $this->filters($request);
        $query = $this->query($filters);
        if (!(clone $query)->exists()) {
            return redirect()->route('data-management.reports.export-data-for-hrms', $filters)
                ->withErrors(['export' => 'No personnel records match the selected filters.']);
        }

        $suffix = '';
        if (!empty($filters['school_db_id'])) {
            $school = SchoolDb::find($filters['school_db_id']);
            $suffix = $school?->school_name ?? '';
        } elseif (!empty($filters['school_district'])) {
            $suffix = $filters['school_district'];
        } elseif (!empty($filters['school_area'])) {
            $suffix = $filters['school_area'];
        }

        $filename = 'HRMS_Consolidated' . ($suffix ? '_' . $suffix : '') . '_' . now('Asia/Manila')->format('Ymd_His') . '.xlsx';

        return Excel::download(new HrmsPersonnelExport($query), $filename);
    }
}
