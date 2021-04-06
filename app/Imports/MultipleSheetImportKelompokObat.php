<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Imports\KelompokObatImport;

class MultipleSheetImportKelompokObat implements WithMultipleSheets
{
    /**
    * @param Collection $collection
    */
    public function sheets(): array
    {
        return [
            0 => new KelompokObatImport(),
        ];
    }
}
