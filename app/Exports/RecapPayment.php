<?php

namespace App\Exports;

use App\Exports\DataRecapPayment;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RecapPayment implements WithMultipleSheets
{
    use Exportable;

    protected $sheets;
    protected $orderby;
    protected $column;
    protected $keyword;
    protected $branch_id;

    public function __construct($orderby, $column, $keyword, $branch_id)
    {
        $this->orderby = $orderby;
        $this->column = $column;
        $this->keyword = $keyword;
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
            new DataRecapPayment($this->orderby, $this->column, $this->keyword, $this->branch_id),
        ];

        return $sheets;
    }
}
