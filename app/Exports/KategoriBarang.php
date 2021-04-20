<?php

namespace App\Exports;

use App\Models\CategoryItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class KategoriBarang implements FromCollection, ShouldAutoSize, WithHeadings, WithTitle, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return CategoryItem::where('isDeleted', '=', 0)->get();
    }

    public function headings(): array
    {
        return [
            ['Kode Kategori Barang', 'Nama Kategori Barang'],
        ];
    }

    public function title(): string
    {
        return 'Daftar Kategori Barang';
    }

    public function map($categoryitem): array
    {
        return [
            $categoryitem->id,
            $categoryitem->category_name,
        ];
    }
}
