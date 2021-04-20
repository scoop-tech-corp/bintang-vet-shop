<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class KelompokObat implements ShouldAutoSize, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return [
            ['Nama Kelompok', 'Kode Cabang'],
        ];
    }

    public function title(): string
    {
        return 'Kelompok Obat';
    }
}
