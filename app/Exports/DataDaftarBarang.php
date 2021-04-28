<?php

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class DataDaftarBarang implements FromCollection, ShouldAutoSize, WithHeadings, WithTitle, WithMapping
{
    public function collection()
    {
        $data = DB::table('list_of_items')
            ->join('users', 'list_of_items.user_id', '=', 'users.id')
            ->join('branches', 'list_of_items.branch_id', '=', 'branches.id')
            ->join('unit_item', 'list_of_items.unit_item_id', '=', 'unit_item.id')
            ->join('category_item', 'list_of_items.category_item_id', '=', 'category_item.id')
            ->select('list_of_items.id', 'list_of_items.item_name', 'list_of_items.total_item',
                'unit_item.id as unit_item_id', 'unit_item.unit_name', 'category_item.id as category_item_id', 'category_item.category_name'
                , 'branches.id as branch_id', 'branches.branch_name', 'users.id as user_id', 'users.fullname as created_by',
                DB::raw("DATE_FORMAT(list_of_items.created_at, '%d %b %Y') as created_at"))
            ->where('list_of_items.isDeleted', '=', 0)
            ->get();

        return $data;
    }

    public function headings(): array
    {
        return [
            ['Kode Daftar Barang', 'Nama Barang', 'Jumlah Barang', 'Kode Satuan Barang',
            'Nama Satuan Barang', 'Kode Kategori Barang', 'Kategori Barang', 'Kode Cabang', 'Nama Cabang'],
        ];
    }

    public function title(): string
    {
        return 'Data Barang';
    }

    public function map($list_of_items): array
    {
        return [
            $list_of_items->id,
            $list_of_items->item_name,
            $list_of_items->total_item,
            $list_of_items->unit_item_id,
            $list_of_items->unit_name,
            $list_of_items->category_item_id,
            $list_of_items->category_name,
            $list_of_items->branch_id,
            $list_of_items->branch_name
        ];
    }
}
