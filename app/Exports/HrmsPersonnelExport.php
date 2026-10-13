<?php
namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HrmsPersonnelExport extends DefaultValueBinder implements
    FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle, WithCustomValueBinder
{
    public function __construct(private Builder $personnelQuery) {}
    public function query() { return clone $this->personnelQuery; }
    public function title(): string { return 'Sheet1'; }

    public function headings(): array
    {
        return [
            'SCHOOL NAME', // A
            'DISTRICT', // B
            'EMAIL ADDRESS', // C
            'FIRST NAME', // D
            'MIDDLE NAME', // E
            'LAST NAME', // F
            'EXTENSION NAME', // G
            'GENDER', // H
            'birth_place', // I
            'DATE OF BIRTH
(DD/MM/YYYY)', // J
            'CIVIL STATUS', // K
            'religion', // L
            'citizenship', // M
            'mode_of_citizenship', // N
            'height_m', // O
            'weight_kg', // P
            'blood_type', // Q
            'CONTACT NO.', // R
            '', // S
            'SPECIALIZATION
ex. English, General Education, Cookery', // T
            'ADDRESS STATUS', // U
            'HOME ADDRESS', // V
            'brgy', // W
            'subd_village', // X
            'municipality_city', // Y
            'province', // Z
            'zip_postal', // AA
            '', // AB
            'GSIS NO.', // AC
            '', // AD
            'PAG-IBIG NO.', // AE
            'T.I.N.
ex. 000-111-222', // AF
            'PHILHEALTH NO.', // AG
            'EMPLOYEE NO.', // AH
            '', // AI
            'EMAIL ADDRESS', // AJ
            'ITEM NUMBER
(please refer to your PSIPOP)
ex. OSEC-DECSB-TCH1-000000-1998', // AK
            'SCHOOL ID', // AL
            'DATE OF 
ORIGINAL APPOINTMENT
(DD/MM/YYYY)', // AM
            'DATE OF
LAST PROMOTION
(DD/MM/YYYY)', // AN
            'EMPLOYMENT STATUS
ex. Pemanent, Provisional, Casual', // AO
            'WARM BODY STATUS
ex. Original, Borrowed, Detailed', // AP
            'NATURE OF WORK
ex. Teaching Services', // AQ
            'SOURCE OF FUND
ex. Plantilla/MOOE/GMS', // AR
            'MONTHLY SALARY 
(Format: ##,000.00)
Example: 30,000.00', // AS
            'CONTRACT DURATION
ex. 3 Months', // AT
            'Validation', // AU
            'NATURE OF APPOINTMENT', // AV
            '', // AW
            'EMAIL ADDRESS', // AX
            'MODES OF AVAILMENT', // AY
            'disbursement_status', // AZ
            '', // BA
            'POSITION TITLE
(please refer in your PSIPOP)
ex. Teacher I', // BB
            'ITEM FROM 
(ELEMENTARY, JUNIOR HIGH SCHOOL, SENIOR HIGH SCHOOL)
ex. Elementary', // BC
            'ELIGIBILITY
ex. LET, CSC', // BD
            'AGE', // BE
            '', // BF
        ];
    }

    private function dateValue($value): string
    {
        if (!$value) { return ''; }
        try {
            // Database dates are ISO; support existing day/month/year strings too.
            if (is_string($value) && preg_match('~^\d{2}/\d{2}/\d{4}$~', $value)) {
                return Carbon::createFromFormat('!d/m/Y', $value)->format('d/m/Y');
            }
            return Carbon::parse($value)->format('d/m/Y');
        } catch (\Throwable $e) { return ''; }
    }

    private function age($value): ?int
    {
        $date = $this->dateValue($value);
        if ($date === '') { return null; }
        $birth = Carbon::createFromFormat('!d/m/Y', $date, 'Asia/Manila');
        $today = Carbon::today('Asia/Manila');
        return $birth->greaterThan($today) ? null : (int) $birth->diffInYears($today);
    }

    public function map($person): array
    {
        // These fields are absent from the supplied controller's confirmed mappings.
        // Replace each null with data_get($person, 'your.actual.relationship.field').
        // Add any newly used relationship to the controller's with([...]) list.
        $extra = [
            'address_status' => null,
            'home_address' => null,
            'brgy' => null,
            'subd_village' => null,
            'municipality_city' => null,
            'province' => null,
            'zip_postal' => null,
            'validation' => null,
            'nature_of_appointment' => null,
            'eligibility' => null,
        ];
        return [
            data_get($person, 'employmentStatus.school.school_name'), // A
            data_get($person, 'employmentStatus.school.school_district'), // B
            data_get($person, 'email'), // C
            data_get($person, 'basicInformation.first_name'), // D
            data_get($person, 'basicInformation.middle_name'), // E
            data_get($person, 'basicInformation.last_name'), // F
            data_get($person, 'basicInformation.extension_name'), // G
            data_get($person, 'basicInformation.sex'), // H
            data_get($person, 'basicInformation.birth_place'), // I
            $this->dateValue(data_get($person, 'basicInformation.birth_date')), // J
            data_get($person, 'basicInformation.civil_status'), // K
            data_get($person, 'basicInformation.religion'), // L
            data_get($person, 'basicInformation.citizenship'), // M
            data_get($person, 'basicInformation.mode_of_citizenship'), // N
            data_get($person, 'basicInformation.height_m'), // O
            data_get($person, 'basicInformation.weight_kg'), // P
            data_get($person, 'basicInformation.blood_type'), // Q
            data_get($person, 'basicInformation.mobile_number'), // R
            null, // S
            data_get($person, 'basicInformation.specialization'), // T
            $extra['address_status'], // U
            $extra['home_address'], // V
            $extra['brgy'], // W
            $extra['subd_village'], // X
            $extra['municipality_city'], // Y
            $extra['province'], // Z
            $extra['zip_postal'], // AA
            null, // AB
            data_get($person, 'basicInformation.issuedId.gsis_no'), // AC
            null, // AD
            data_get($person, 'basicInformation.issuedId.pagibig_no'), // AE
            data_get($person, 'basicInformation.issuedId.tin_no'), // AF
            data_get($person, 'basicInformation.issuedId.philhealth_no'), // AG
            data_get($person, 'basicInformation.issuedId.employee_id'), // AH
            null, // AI
            data_get($person, 'email'), // AJ
            data_get($person, 'employmentStatus.plantilla.item_number'), // AK
            data_get($person, 'employmentStatus.school.school_id'), // AL
            $this->dateValue(data_get($person, 'employmentStatus.date_of_original_appointment')), // AM
            $this->dateValue(data_get($person, 'employmentStatus.date_of_last_promotion')), // AN
            data_get($person, 'employmentStatus.employment_status'), // AO
            data_get($person, 'employmentStatus.warm_body_status'), // AP
            data_get($person, 'employmentStatus.nature_of_work'), // AQ
            data_get($person, 'employmentStatus.source_of_fund'), // AR
            data_get($person, 'employmentStatus.monthly_salary'), // AS
            data_get($person, 'employmentStatus.contract_duration'), // AT
            $extra['validation'], // AU
            $extra['nature_of_appointment'], // AV
            null, // AW
            data_get($person, 'email'), // AX
            data_get($person, 'medicalAllowance.mode_of_availment'), // AY
            data_get($person, 'medicalAllowance.disbursement_status'), // AZ
            null, // BA
            data_get($person, 'employmentStatus.plantilla.position_title'), // BB
            data_get($person, 'employmentStatus.plantilla.item_from_school_level'), // BC
            $extra['eligibility'], // BD
            $this->age(data_get($person, 'basicInformation.birth_date')), // BE
            null, // BF
        ];
    }

    public function bindValue(Cell $cell, $value): bool
    {
        // Keep leading zeros and prevent personnel text being interpreted as formulas.
        if (is_string($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }
        return parent::bindValue($cell, $value);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->freezePane('A2');
        $sheet->setAutoFilter('A1:BF' . $sheet->getHighestRow());
        $sheet->getRowDimension(1)->setRowHeight(90);
        foreach (range(1, 58) as $index) {
            $letter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index);
            $sheet->getColumnDimension($letter)->setWidth(23);
        }
        foreach (['S', 'AB', 'AD', 'AI', 'AW', 'BA', 'BF'] as $separator) {
            $sheet->getColumnDimension($separator)->setWidth(3);
        }
        $sheet->getColumnDimension('A')->setWidth(38);
        $sheet->getColumnDimension('AK')->setWidth(42);
        $sheet->getStyle('AS2:AS' . max(2, $sheet->getHighestRow()))
            ->getNumberFormat()->setFormatCode('#,##0.00');
        return [1 => [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '166534']],
            'alignment' => ['wrapText' => true, 'vertical' => 'center'],
        ]];
    }
}
