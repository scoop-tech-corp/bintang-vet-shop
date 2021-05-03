<?php

namespace App\Exports;

use App\Exports\DataLaporanKeuanganBulanan;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanKeuanganBulanan implements WithMultipleSheets
{
    use Exportable;

    protected $sheets;

    protected $orderby;
    protected $column;
    protected $month;
    protected $year;
    protected $branch_id;

    public function __construct($orderby, $column, $month, $year, $branch_id)
    {
        $this->orderby = $orderby;
        $this->column = $column;
        $this->month = $month;
        $this->year = $year;
        $this->branch_id = $branch_id;
    }

    function array(): array
    {
        return $this->sheets;
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets = [
            new DataLaporanKeuanganBulanan($this->orderby, $this->column, $this->month, $this->year, $this->branch_id),
        ];

        return $sheets;
    }
}
