<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EnrollmentTemplateExport implements FromArray, WithStyles
{
    public function array(): array
    {
        return [

            // Row 1
            ['ENROLLMENT RECORDS TEMPLATE'],

            // Row 2
            ['Please enter enrollment records starting on Row 5.'],

            // Row 3
            [''],

            // Row 4 - COLUMN HEADERS
            [
                'school_id',
                'school_year',
                'grade_level',
                'enrollment_count',
            ],

            // Row 5 - SAMPLE / EMPTY DATA
            [
                '',
                '',
                '',
                '',
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge title
        $sheet->mergeCells('A1:D1');

        // Merge instruction
        $sheet->mergeCells('A2:D2');

        // Title
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(14);

        // Header
        $sheet->getStyle('A4:D4')->getFont()->setBold(true);

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(18);
        $sheet->getColumnDimension('B')->setWidth(18);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(22);

        return [];
    }
}