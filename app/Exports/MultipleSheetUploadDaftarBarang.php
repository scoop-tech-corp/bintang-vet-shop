<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use App\Exports\DaftarBarang;
use App\Exports\Cabang;
use App\Exports\SatuanBarang;
use App\Exports\KategoriBarang;

class MultipleSheetUploadDaftarBarang implements WithMultipleSheets
{
    use Exportable;

    protected $sheets;

    public function __construct()
    {
        
    }

    public function array(): array
    {
        return $this->sheets;
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets = [
            new DaftarBarang(),
            new SatuanBarang(),
            new KategoriBarang(),
            new Cabang()
        ];

        return $sheets;
    }
}
