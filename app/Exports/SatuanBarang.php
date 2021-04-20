<?php

namespace App\Exports;

use App\Models\UnitItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class SatuanBarang implements FromCollection, ShouldAutoSize, WithHeadings, WithTitle, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return UnitItem::where('isDeleted', '=', 0)->get();
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
            $unititem->unit_name,
        ];
    }
}
