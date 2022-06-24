<?php

namespace App\Imports;

use App\Imports\UploadDataItem;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UploadItem implements WithMultipleSheets
{
    protected $category;
    protected $id;

    public function __construct($category, $id)
    {
        $this->category = $category;
        $this->id = $id;
    }
    public function sheets(): array
    {
        return [
            0 => new UploadDataItem($this->category, $this->id),
        ];
    }
}
