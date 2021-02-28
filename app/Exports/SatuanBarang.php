<?php

namespace App\Exports;

use App\Models\UnitItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class SatuanBarang implements FromCollection, ShouldAutoSize, WithHeadings, WithTitle, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return UnitItem::all();
    }

    public function headings(): array
    {
        return [
            ['Kode Satuan Barang', 'Nama Satuan Barang'],
        ];
    }

    public function title(): string
    {
        return 'Daftar Satuan Barang';
    }

    public function map($unititem): array
    {
        return [
            $unititem->id,
            $unititem->unit_name
        ];
    }
}
