<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class PersonnelDataExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStrictNullComparison
{
    public function __construct(
        private Builder $personnelQuery,
        private array $selectedColumns,
        private array $columnDefinitions
    ) {}

    public function query()
    {
        // Preserve the controller's filtered medicalAllowance relationship.
        $query = clone $this->personnelQuery;

        return $query
            ->with([
                'basicInformation.issuedId',
                'employmentStatus.school',
                'employmentStatus.plantilla',
                'employmentStatus.officeUnit',
            ])
            ->orderBy('users.id');
    }

    public function headings(): array
    {
        return array_map(
            fn ($key) => $this->columnDefinitions[$key]['label'],
            $this->selectedColumns
        );
    }

    public function map($user): array
    {
        return array_map(function ($key) use ($user) {
            $definition = $this->columnDefinitions[$key];
            $value = data_get($user, $definition['path']);

            if ($value instanceof \DateTimeInterface) {
                return $value->format('Y-m-d');
            }

            if ($value === null) {
                return null;
            }

            if (
                is_string($value)
                && preg_match('/^[\s\x00-\x1F]*[=+\-@]/u', $value)
            ) {
                return "'" . $value;
            }

            return $value;
        }, $this->selectedColumns);
    }
}