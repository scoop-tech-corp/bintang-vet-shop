<?php

namespace App\Imports;

use App\Models\MedicineGroup;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class KelompokObatImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    public function model(array $row)
    {
        
        return new MedicineGroup([
            'group_name' => $row['nama_kelompok'],
            'branch_id' => $row['kode_cabang'],
            'user_id' => 1,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nama_kelompok' => 'required|string',
            '*.kode_cabang' => 'required|integer',
        ];
    }
}
