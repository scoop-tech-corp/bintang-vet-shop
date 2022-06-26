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
          ['Nama Barang', 'Jumlah Barang','Limit Barang','Tanggal Kedaluwarsa Barang (dd/mm/yyyy)', 'Harga Jual', 'Harga Modal','Kode Cabang']
      ];
  }

  public function title(): string
  {
      return 'Daftar Barang';
  }
}
