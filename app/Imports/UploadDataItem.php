<?php

namespace App\Imports;

use App\Models\ListofItems;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UploadDataItem implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    public function model(array $row)
    {

        return new ListofItems([
            'item_name' => $row['nama_barang'],
            'total_item' => $row['jumlah_barang'],
            'selling_price' => $row['harga_jual'],
            'capital_price' => $row['harga_modal'],
            'profit' => $row['keuntungan'],
            'branch_id' => $row['kode_cabang'],
            'user_id' => 1,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nama_barang' => 'required|string',
            '*.jumlah_barang' => 'required|numeric',
            '*.harga_jual' => 'required|numeric',
            '*.harga_modal' => 'required|numeric',
            '*.keuntungan' => 'required|numeric',
            '*.kode_cabang' => 'required|numeric',
        ];
    }
}
