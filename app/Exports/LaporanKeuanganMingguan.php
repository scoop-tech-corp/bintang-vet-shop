<?php

namespace App\Exports;

use App\Exports\DataLaporanKeuanganMingguan;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanKeuanganMingguan implements WithMultipleSheets
{
    use Exportable;

    protected $orderby;
    protected $column;
    protected $date;
    protected $branch_id;

    public function __construct($orderby, $column, $date_from, $date_to, $branch_id)
    {
        $this->orderby = $orderby;
        $this->column = $column;
        $this->date_from = $date_from;
        $this->date_to = $date_to;
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
            new DataLaporanKeuanganMingguan($this->orderby, $this->column, $this->date_from, $this->date_to, $this->branch_id),
        ];

        return $sheets;
    }
}
