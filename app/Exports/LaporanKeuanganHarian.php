<?php

namespace App\Exports;

use App\Exports\DataPasien;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanKeuanganHarian implements WithMultipleSheets
{
    use Exportable;

    protected $sheets;

    public function __construct()
    {

    }

    function array(): array
    {
        return $this->sheets;
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets = [
            new DataPasien()
        ];

        return $sheets;
    }
}
