<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DataBarang implements ShouldAutoSize, WithHeadings, WithTitle
{
  public function headings(): array
  {
      return [
          ['Nama Barang', 'Jumlah Barang', 'Harga Jual', 'Harga Modal', 'Keuntungan','Kode Cabang']
      ];
  }

  public function title(): string
  {
      return 'Daftar Barang';
  }
}
