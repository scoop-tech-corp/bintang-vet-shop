<?php

namespace App\Exports;

use App\Exports\Cabang;
use App\Exports\KelompokObat;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultipleSheetUploadKelompokObat implements WithMultipleSheets
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
            new KelompokObat(),
            new Cabang(),
        ];

        return $sheets;
    }
}
