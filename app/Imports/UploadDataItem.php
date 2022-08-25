<?php

namespace App\Imports;

use App\Models\ListofItems;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;

class UploadDataItem implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    protected $category;

    public function __construct($category, $id)
    {
        $this->category = $category;
        $this->id = $id;
    }

    public function model(array $row)
    {
        $exp_date = Carbon::parse(Carbon::createFromFormat('d/m/Y', $row['tanggal_kedaluwarsa_barang_ddmmyyyy'])->format('Y/m/d'));
        return new ListofItems([
            'item_name' => $row['nama_barang'],
            'total_item' => $row['jumlah_barang'],
            'limit_item' => $row['limit_barang'],
            'expired_date' => $exp_date,
            'selling_price' => $row['harga_jual'],
            'capital_price' => $row['harga_modal'],
            'profit' => $row['harga_jual'] - $row['harga_modal'],
            'image' => "",
            'category' => $this->category,
            'branch_id' => $row['kode_cabang'],
            'diff_item' => $row['jumlah_barang'] - $row['limit_barang'],
            'diff_expired_days' => \Carbon\Carbon::parse(now())->diffInDays($exp_date, false),
            'user_id' => $this->id,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nama_barang' => 'required|string',
            '*.jumlah_barang' => 'required|numeric',
            '*.limit_barang' => 'required|numeric',
            '*.tanggal_kedaluwarsa_barang_ddmmyyyy' => 'required|date_format:d/m/Y',
            '*.harga_jual' => 'required|numeric',
            '*.harga_modal' => 'required|numeric',
            '*.kode_cabang' => 'required|numeric',
        ];
    }
}
