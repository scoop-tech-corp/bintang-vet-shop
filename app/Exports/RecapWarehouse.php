<?php

namespace App\Exports;

use App\Exports\DataRecapWarehouse;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RecapWarehouse implements WithMultipleSheets
{
    use Exportable;

    protected $sheets;
    protected $orderby;
    protected $column;
    protected $date;
    protected $branch_id;
    protected $category;

    public function __construct($orderby, $column, $keyword, $category, $branch_id)
    {
        $this->orderby = $orderby;
        $this->column = $column;
        $this->keyword = $keyword;
        $this->category = $category;
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
            new DataRecapWarehouse($this->orderby, $this->column, $this->keyword, $this->category, $this->branch_id),
        ];

        return $sheets;
    }
}
