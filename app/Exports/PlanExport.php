<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PlanExport implements FromArray, WithHeadings
{
    protected $plans;

    /**
     * PlanExport constructor.
     * @param array $plans
     */
    public function __construct(array $plans)
    {
        $this->plans = $plans;
    }

    public function headings(): array
    {
        return array_keys($this->plans[0]);
    }

    public function array(): array
    {
        return $this->plans;
    }
}
