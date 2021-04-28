<?php

namespace App\Exports;

use App\Exports\DataDaftarBarang;
use App\Exports\DataHarga;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultipleSheetUploadHargaBarang implements WithMultipleSheets
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
            new DataHarga(),
            new DataDaftarBarang(),
        ];

        return $sheets;
    }
}
