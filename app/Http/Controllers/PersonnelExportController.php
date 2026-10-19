<?php

namespace App\Http\Controllers;

use App\Exports\PersonnelDataExport;
use App\Models\BasicInformation;
use App\Models\EmploymentStatus;
use App\Models\MedicalAllowance;
use App\Models\PlantillaDb;
use App\Models\SchoolDb;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class PersonnelExportController extends Controller
{
    private function columnGroups(): array
    {
        return [
            'User Account' => [
                'user_id' => [
                    'label' => 'User ID',
                    'path' => 'id',
                ],
                'user_name' => [
                    'label' => 'Account Name',
                    'path' => 'name',
                ],
                'email' => [
                    'label' => 'Email Address',
                    'path' => 'email',
                ],
                'role' => [
                    'label' => 'User Role',
                    'path' => 'role',
                ],
                'account_status' => [
                    'label' => 'Account Status',
                    'path' => 'status',
                ],
            ],
            'Basic Information' => [
                'first_name' => [
                    'label' => 'First Name',
                    'path' => 'basicInformation.first_name',
                ],
                'middle_name' => [
                    'label' => 'Middle Name',
                    'path' => 'basicInformation.middle_name',
                ],
                'last_name' => [
                    'label' => 'Last Name',
                    'path' => 'basicInformation.last_name',
                ],
                'extension_name' => [
                    'label' => 'Extension Name',
                    'path' => 'basicInformation.extension_name',
                ],
                'sex' => [
                    'label' => 'Sex',
                    'path' => 'basicInformation.sex',
                ],
                'birth_place' => [
                    'label' => 'Birth Place',
                    'path' => 'basicInformation.birth_place',
                ],
                'birth_date' => [
                    'label' => 'Birth Date',
                    'path' => 'basicInformation.birth_date',
                ],
                'civil_status' => [
                    'label' => 'Civil Status',
                    'path' => 'basicInformation.civil_status',
                ],
                'religion' => [
                    'label' => 'Religion',
                    'path' => 'basicInformation.religion',
                ],
                'citizenship' => [
                    'label' => 'Citizenship',
                    'path' => 'basicInformation.citizenship',
                ],
                'mode_of_citizenship' => [
                    'label' => 'Mode of Citizenship',
                    'path' => 'basicInformation.mode_of_citizenship',
                ],
                'height_m' => [
                    'label' => 'Height (m)',
                    'path' => 'basicInformation.height_m',
                ],
                'weight_kg' => [
                    'label' => 'Weight (kg)',
                    'path' => 'basicInformation.weight_kg',
                ],
                'blood_type' => [
                    'label' => 'Blood Type',
                    'path' => 'basicInformation.blood_type',
                ],
                'mobile_number' => [
                    'label' => 'Mobile Number',
                    'path' => 'basicInformation.mobile_number',
                ],
                'telephone_number' => [
                    'label' => 'Telephone Number',
                    'path' => 'basicInformation.telephone_number',
                ],
                'specialization' => [
                    'label' => 'Specialization',
                    'path' => 'basicInformation.specialization',
                ],
            ],
            'Issued IDs' => [
                'employee_id' => [
                    'label' => 'Employee ID',
                    'path' => 'basicInformation.issuedId.employee_id',
                ],
                'umid_no' => [
                    'label' => 'UMID Number',
                    'path' => 'basicInformation.issuedId.umid_no',
                ],
                'gsis_no' => [
                    'label' => 'GSIS Number',
                    'path' => 'basicInformation.issuedId.gsis_no',
                ],
                'philsys_no' => [
                    'label' => 'PhilSys Number',
                    'path' => 'basicInformation.issuedId.philsys_no',
                ],
                'pagibig_no' => [
                    'label' => 'Pag-IBIG Number',
                    'path' => 'basicInformation.issuedId.pagibig_no',
                ],
                'tin_no' => [
                    'label' => 'TIN Number',
                    'path' => 'basicInformation.issuedId.tin_no',
                ],
                'philhealth_no' => [
                    'label' => 'PhilHealth Number',
                    'path' => 'basicInformation.issuedId.philhealth_no',
                ],
            ],
            'Employment Information' => [
                'plantilla_db_id' => [
                    'label' => 'Plantilla DB ID',
                    'path' => 'employmentStatus.plantilla_db_id',
                ],
                'school_db_id' => [
                    'label' => 'School DB ID',
                    'path' => 'employmentStatus.school_db_id',
                ],
                'office_unit_id' => [
                    'label' => 'Office Unit ID',
                    'path' => 'employmentStatus.office_unit_id',
                ],
                'date_of_original_appointment' => [
                    'label' => 'Original Appointment Date',
                    'path' => 'employmentStatus.date_of_original_appointment',
                ],
                'date_of_last_promotion' => [
                    'label' => 'Last Promotion Date',
                    'path' => 'employmentStatus.date_of_last_promotion',
                ],
                'employment_status' => [
                    'label' => 'Employment Status',
                    'path' => 'employmentStatus.employment_status',
                ],
                'warm_body_status' => [
                    'label' => 'Warm Body Status',
                    'path' => 'employmentStatus.warm_body_status',
                ],
                'nature_of_work' => [
                    'label' => 'Nature of Work',
                    'path' => 'employmentStatus.nature_of_work',
                ],
                'source_of_fund' => [
                    'label' => 'Source of Fund',
                    'path' => 'employmentStatus.source_of_fund',
                ],
                'monthly_salary' => [
                    'label' => 'Monthly Salary',
                    'path' => 'employmentStatus.monthly_salary',
                ],
                'contract_duration' => [
                    'label' => 'Contract Duration',
                    'path' => 'employmentStatus.contract_duration',
                ],
            ],
            'Medical Allowance' => [
                'medical_year' => [
                    'label' => 'Medical Allowance Year',
                    'path' => 'medicalAllowance.year',
                ],
                'mode_of_availment' => [
                    'label' => 'Mode of Availment',
                    'path' => 'medicalAllowance.mode_of_availment',
                ],
                'disbursement_status' => [
                    'label' => 'Disbursement Status',
                    'path' => 'medicalAllowance.disbursement_status',
                ],
                'validation_status' => [
                    'label' => 'Validation Status',
                    'path' => 'medicalAllowance.validation_status',
                ],
            ],
            'School Information' => [
                'school_id' => [
                    'label' => 'School ID',
                    'path' => 'employmentStatus.school.school_id',
                ],
                'school_name' => [
                    'label' => 'School Name',
                    'path' => 'employmentStatus.school.school_name',
                ],
                'school_area' => [
                    'label' => 'School Area',
                    'path' => 'employmentStatus.school.school_area',
                ],
                'legislative_district' => [
                    'label' => 'Legislative District',
                    'path' => 'employmentStatus.school.legislative_district',
                ],
                'school_district' => [
                    'label' => 'School District',
                    'path' => 'employmentStatus.school.school_district',
                ],
                'school_municipality' => [
                    'label' => 'School Municipality',
                    'path' => 'employmentStatus.school.school_municipality',
                ],
                'school_sector' => [
                    'label' => 'School Sector',
                    'path' => 'employmentStatus.school.school_sector',
                ],
                'school_curricular_offering' => [
                    'label' => 'School Curricular Offering',
                    'path' => 'employmentStatus.school.school_curricular_offering',
                ],
            ],
            'Plantilla Information' => [
                'item_number' => [
                    'label' => 'Plantilla Item Number',
                    'path' => 'employmentStatus.plantilla.item_number',
                ],
                'item_from' => [
                    'label' => 'Item From',
                    'path' => 'employmentStatus.plantilla.item_from',
                ],
                'item_from_school_level' => [
                    'label' => 'Item From School Level',
                    'path' => 'employmentStatus.plantilla.item_from_school_level',
                ],
                'position_title' => [
                    'label' => 'Position Title',
                    'path' => 'employmentStatus.plantilla.position_title',
                ],
                'salary_grade' => [
                    'label' => 'Salary Grade',
                    'path' => 'employmentStatus.plantilla.salary_grade',
                ],
                'area_code' => [
                    'label' => 'Area Code',
                    'path' => 'employmentStatus.plantilla.area_code',
                ],
                'area_type' => [
                    'label' => 'Area Type',
                    'path' => 'employmentStatus.plantilla.area_type',
                ],
                'plantilla_level' => [
                    'label' => 'Plantilla Level',
                    'path' => 'employmentStatus.plantilla.plantilla_level',
                ],
                'pppa_attribution' => [
                    'label' => 'PPPA Attribution',
                    'path' => 'employmentStatus.plantilla.pppa_attribution',
                ],
            ],
        ];
    }

    private function columnDefinitions(): array
    {
        $definitions = [];

        foreach ($this->columnGroups() as $group) {
            foreach ($group as $key => $definition) {
                $definitions[$key] = $definition;
            }
        }

        return $definitions;
    }

    private function defaultColumns(): array
    {
        return [
            'employee_id',
            'first_name',
            'middle_name',
            'last_name',
            'extension_name',
            'sex',
            'school_id',
            'school_name',
            'school_district',
            'school_municipality',
            'item_number',
            'position_title',
            'salary_grade',
            'employment_status',
            'warm_body_status',
            'source_of_fund',
        ];
    }

    private function filterOptions(): array
    {
        $options = [];

        foreach ([
            'employment_status',
            'warm_body_status',
            'nature_of_work',
            'source_of_fund',
            'school_db_id',
            'office_unit_id',
            'plantilla_db_id',
        ] as $field) {
            $options[$field] = EmploymentStatus::query()
                ->whereNotNull($field)
                ->where($field, '<>', '')
                ->distinct()
                ->orderBy($field)
                ->pluck($field);
        }

        foreach ([
            'sex',
            'civil_status',
            'specialization',
        ] as $field) {
            $options[$field] = BasicInformation::query()
                ->whereNotNull($field)
                ->where($field, '<>', '')
                ->distinct()
                ->orderBy($field)
                ->pluck($field);
        }

        foreach ([
            'year',
            'mode_of_availment',
            'disbursement_status',
            'validation_status',
        ] as $field) {
            $options[$field] = MedicalAllowance::query()
                ->whereNotNull($field)
                ->distinct()
                ->orderBy($field)
                ->pluck($field);
        }

        foreach ([
            'school_id',
            'school_name',
            'school_district',
            'school_municipality',
            'school_area',
            'legislative_district',
            'school_sector',
        ] as $field) {
            $options[$field] = SchoolDb::query()
                ->whereNotNull($field)
                ->where($field, '<>', '')
                ->distinct()
                ->orderBy($field)
                ->pluck($field);
        }

        foreach ([
            'item_number',
            'position_title',
            'salary_grade',
            'plantilla_level',
            'area_type',
        ] as $field) {
            $options[$field] = PlantillaDb::query()
                ->whereNotNull($field)
                ->distinct()
                ->orderBy($field)
                ->pluck($field);
        }

        return $options;
    }

    private function validatedFilters(Request $request): array
    {
        return $request->validate([
            'search' => ['nullable', 'string', 'max:150'],
            'sex' => ['nullable', 'string', 'max:255'],
            'civil_status' => ['nullable', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'employment_status' => ['nullable', 'string', 'max:255'],
            'warm_body_status' => ['nullable', 'string', 'max:255'],
            'nature_of_work' => ['nullable', 'string', 'max:255'],
            'source_of_fund' => ['nullable', 'string', 'max:255'],
            'school_db_id' => ['nullable', 'integer'],
            'office_unit_id' => ['nullable', 'integer'],
            'plantilla_db_id' => ['nullable', 'integer'],
            'employment_filter' => [
                'nullable',
                Rule::in(['all', 'active', 'inactive']),
            ],
            'medical_year' => [
                'nullable',
                'integer',
                'between:2000,2100',
            ],
            'mode_of_availment' => ['nullable', 'string', 'max:255'],
            'disbursement_status' => ['nullable', 'string', 'max:255'],
            'validation_status' => ['nullable', 'string', 'max:255'],
            'school_id' => ['nullable', 'string', 'max:255'],
            'school_name' => ['nullable', 'string', 'max:255'],
            'school_district' => ['nullable', 'string', 'max:255'],
            'school_municipality' => ['nullable', 'string', 'max:255'],
            'school_area' => ['nullable', 'string', 'max:255'],
            'legislative_district' => ['nullable', 'string', 'max:255'],
            'school_sector' => ['nullable', 'string', 'max:255'],
            'item_number' => ['nullable', 'string', 'max:255'],
            'position_title' => ['nullable', 'string', 'max:255'],
            'salary_grade' => ['nullable', 'integer'],
            'plantilla_level' => ['nullable', 'string', 'max:255'],
            'area_type' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function buildQuery(array $filters): Builder
    {
        $query = User::query()
            ->whereNull('users.deleted_at')
            ->whereIn('users.role', ['user', 'admin'])
            ->where('users.id', '<>', 1)
            ->whereHas('basicInformation')
            ->whereDoesntHave('basicInformation.issuedId', function ($q) {
                $q->where('employee_id', '1000001');
            });

        // Search personnel.
        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%")
                    ->orWhereHas('basicInformation', function ($basic) use ($search) {
                        $basic->where('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('basicInformation.issuedId', function ($id) use ($search) {
                        $id->where('employee_id', 'like', "%{$search}%");
                    });
            });
        }

        // Basic information filters.
        foreach ([
            'sex',
            'civil_status',
            'specialization',
        ] as $field) {
            if (isset($filters[$field]) && $filters[$field] !== '') {
                $value = $filters[$field];

                $query->whereHas(
                    'basicInformation',
                    fn ($q) => $q->where($field, $value)
                );
            }
        }

        // Employment filters.
        foreach ([
            'employment_status',
            'warm_body_status',
            'nature_of_work',
            'source_of_fund',
            'school_db_id',
            'office_unit_id',
            'plantilla_db_id',
        ] as $field) {
            if (isset($filters[$field]) && $filters[$field] !== '') {
                $value = $filters[$field];

                $query->whereHas(
                    'employmentStatus',
                    fn ($q) => $q->where($field, $value)
                );
            }
        }

        // Active / inactive classification.
        $inactiveStatuses = [
            'Vacant (Retired)',
            'Vacant (Resigned)',
            'Vacant (Others)',
        ];

        $classification = $filters['employment_filter'] ?? 'all';

        if ($classification === 'active') {
            $query->whereHas('employmentStatus', function ($q) use ($inactiveStatuses) {
                $q->where(function ($inner) use ($inactiveStatuses) {
                    $inner->whereNull('warm_body_status')
                        ->orWhereNotIn('warm_body_status', $inactiveStatuses);
                });
            });
        }

        if ($classification === 'inactive') {
            $query->whereHas(
                'employmentStatus',
                fn ($q) => $q->whereIn('warm_body_status', $inactiveStatuses)
            );
        }

        // Build the medical conditions once.
        $medicalMap = [
            'medical_year' => 'year',
            'mode_of_availment' => 'mode_of_availment',
            'disbursement_status' => 'disbursement_status',
            'validation_status' => 'validation_status',
        ];

        $medicalFilters = [];

        foreach ($medicalMap as $input => $column) {
            if (isset($filters[$input]) && $filters[$input] !== '') {
                $medicalFilters[$column] = $filters[$input];
            }
        }

        $applyMedicalFilters = function ($q) use ($medicalFilters) {
            foreach ($medicalFilters as $column => $value) {
                $q->where($column, $value);
            }
        };

        // All selected medical conditions must match the same record.
        if (!empty($medicalFilters)) {
            $query->whereHas('medicalAllowance', $applyMedicalFilters);
        }

        // Load the same matching medical record for preview and export.
        $query->with([
            'basicInformation.issuedId',
            'employmentStatus.school',
            'employmentStatus.plantilla',
            'employmentStatus.officeUnit',
            'medicalAllowance' => $applyMedicalFilters,
        ]);

        // School database filters.
        foreach ([
            'school_id',
            'school_name',
            'school_district',
            'school_municipality',
            'school_area',
            'legislative_district',
            'school_sector',
        ] as $field) {
            if (isset($filters[$field]) && $filters[$field] !== '') {
                $value = $filters[$field];

                $query->whereHas(
                    'employmentStatus.school',
                    fn ($q) => $q->where($field, $value)
                );
            }
        }

        // Plantilla database filters.
        foreach ([
            'item_number',
            'position_title',
            'salary_grade',
            'plantilla_level',
            'area_type',
        ] as $field) {
            if (isset($filters[$field]) && $filters[$field] !== '') {
                $value = $filters[$field];

                $query->whereHas(
                    'employmentStatus.plantilla',
                    fn ($q) => $q->where($field, $value)
                );
            }
        }

        return $query;
    }

    public function index(Request $request)
    {
        abort_unless(
            $request->user()?->role === 'super_admin',
            403
        );

        $filters = $this->validatedFilters($request);

        // Relationships and medical constraints are loaded in buildQuery().
        $records = $this->buildQuery($filters)
            ->orderBy('users.id')
            ->paginate(15)
            ->withQueryString();

        return view('reports.export-data', [
            'records' => $records,
            'filters' => $filters,
            'options' => $this->filterOptions(),
            'columnGroups' => $this->columnGroups(),
            'columnDefinitions' => $this->columnDefinitions(),
            'defaultColumns' => $this->defaultColumns(),
        ]);
    }

    public function download(Request $request)
    {
        abort_unless(
            $request->user()?->role === 'super_admin',
            403
        );

        $filters = $this->validatedFilters($request);
        $definitions = $this->columnDefinitions();

        $validated = $request->validate([
            'columns' => ['required', 'array', 'min:1'],
            'columns.*' => [
                'required',
                'string',
                'distinct',
                Rule::in(array_keys($definitions)),
            ],
        ]);

        $selectedColumns = $validated['columns'];
        $query = $this->buildQuery($filters);

        if (!(clone $query)->exists()) {
            return back()->withErrors([
                'export' => 'No personnel records match the selected filters.',
            ]);
        }

        $filename = 'PDMS_Personnel_Export_'
            . now('Asia/Manila')->format('Ymd_His')
            . '.xlsx';

        return Excel::download(
            new PersonnelDataExport(
                $query,
                $selectedColumns,
                $definitions
            ),
            $filename
        );
    }
}