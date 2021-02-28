<?php

namespace App\Exports;

use App\Models\Branch;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class Cabang implements FromCollection, ShouldAutoSize, WithHeadings, WithTitle, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Branch::all();
    }

    public function headings(): array
    {
        return [
            ['Kode Cabang', 'Nama Cabang'],
        ];
    }

    public function title(): string
    {
        return 'Daftar Cabang';
    }

    public function map($branch): array
    {
        return [
            $branch->id,
            $branch->branch_name
        ];
    }
}
