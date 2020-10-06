<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OfferBuyExport implements FromArray, WithHeadings
{
    protected $offerBuys;

    /**
     * OfferBuyExport constructor.
     * @param array $offerBuys
     */
    public function __construct(array $offerBuys)
    {
        $this->offerBuys = $offerBuys;
    }

    public function headings(): array
    {
        return array_keys($this->offerBuys[0]);
    }

    public function array(): array
    {
        return $this->offerBuys;
    }
}
