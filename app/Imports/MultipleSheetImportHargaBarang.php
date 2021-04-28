<?php

namespace App\Imports;

use App\Imports\HargaBarangImport;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultipleSheetImportHargaBarang implements WithMultipleSheets
{
    /**
     * @param Collection $collection
     */
    public function sheets(): array
    {
        return [
            0 => new HargaBarangImport(),
        ];
    }
}
