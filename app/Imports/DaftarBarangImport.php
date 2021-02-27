<?php

namespace App\Imports;

use App\Models\ListofItems;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class DaftarBarangImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    public function model(array $row)
    {
        
        return new ListofItems([
            'item_name' => $row['nama_barang'],
            'total_item' => $row['jumlah_barang'],
            'unit_item_id' => $row['kode_satuan_barang'],
            'category_item_id' => $row['kode_kategori_barang'],
            'branch_id' => $row['kode_cabang_barang'],
            'user_id' => 1,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nama_barang' => 'required|string',
            '*.jumlah_barang' => 'required|integer',
            '*.kode_satuan_barang' => 'required|integer',
            '*.kode_kategori_barang' => 'required|integer',
            '*.kode_cabang_barang' => 'required|integer',
        ];
    }
}
