<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DataHarga implements ShouldAutoSize, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return [
            ['Kode Daftar Barang', 'Harga Jual', 'Harga Modal', 'Fee Dokter', 'Fee Petshop'],
        ];
    }

    public function title(): string
    {
        return 'Data Harga';
    }
}
