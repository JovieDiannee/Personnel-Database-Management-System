<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class EmploymentStatusImport implements ToCollection, WithHeadingRow
{
    protected Collection $rows;

    public function collection(Collection $rows)
    {
        $this->rows = $rows;
    }

    public function getRows(): Collection
    {
        return $this->rows ?? collect();
    }

    /**
     * Excel Row 4 contains the column headers.
     * Data starts at Row 5.
     */
    public function headingRow(): int
    {
        return 4;
    }
}