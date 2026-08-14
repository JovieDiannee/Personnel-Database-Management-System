<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MedicalAllowanceImport implements ToCollection, WithHeadingRow
{
    public function headingRow(): int
    {
        return 4;
    }

    public function collection(Collection $rows)
    {
        return $rows;
    }
}