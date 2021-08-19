<?php

namespace App\Exports;

use App\Exports\DataRecapWarehouse;
use Maatwebsite\Excel\Concerns\Exportable;

class RecapWarehouse implements FromCollection
{
    use Exportable;

    protected $sheets;
    protected $orderby;
    protected $column;
    protected $date;
    protected $branch_id;
    protected $category;

    public function __construct($orderby, $column, $date, $branch_id, $category)
    {
        $this->orderby = $orderby;
        $this->column = $column;
        $this->date = $date;
        $this->branch_id = $branch_id;
        $this->category = $category;
    }

    function array(): array
    {
        return $this->sheets;
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets = [
            new DataRecapWarehouse($this->orderby, $this->column, $this->date, $this->branch_id, $this->category),
        ];

        return $sheets;
    }
}
