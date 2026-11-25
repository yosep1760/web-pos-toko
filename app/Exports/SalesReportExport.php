<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SalesReportExport implements WithMultipleSheets
{
    public function __construct(
        protected $start,
        protected $end,
        protected $cashierId = null
    ) {}

    public function sheets(): array
    {
        return [
            new SalesExport($this->start, $this->end, $this->cashierId),
            new TopProductsExport($this->start, $this->end, $this->cashierId),
        ];
    }
}