<?php

namespace App\Imports;

use App\Imports\UploadDataItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UploadItem implements WithMultipleSheets
{
  protected $category;

  public function __construct($category)
  {
      $this->category = $category;
  }
    public function sheets(): array
    {
        return [
            0 => new UploadDataItem($this->category),
        ];
    }
}
