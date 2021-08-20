<?php

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class DataRecapWarehouse implements FromCollection, ShouldAutoSize, WithHeadings, WithTitle, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $orderby;
    protected $column;
    protected $date;
    protected $branch_id;
    protected $category;

    public function __construct($orderby, $column, $keyword, $category, $branch_id)
    {
        $this->orderby = $orderby;
        $this->column = $column;
        $this->keyword = $keyword;
        $this->category = $category;
        $this->branch_id = $branch_id;
    }

    public function collection()
    {
        $item = DB::table('list_of_items')
            ->join('users', 'list_of_items.user_id', '=', 'users.id')
            ->join('branches', 'list_of_items.branch_id', '=', 'branches.id')
            ->select(
                'list_of_items.item_name',
                'list_of_items.total_item',
                DB::raw("TRIM(list_of_items.selling_price)+0 as selling_price"),
                DB::raw("TRIM(list_of_items.capital_price)+0 as capital_price"),
                DB::raw("TRIM(list_of_items.profit)+0 as profit"),
                'branches.branch_name',
                'users.fullname as created_by',
                DB::raw("DATE_FORMAT(list_of_items.created_at, '%d %b %Y') as created_at"))
            ->where('list_of_items.isDeleted', '=', 0)
            ->where('list_of_items.category', '=', $this->category);

        if ($this->branch_id) {
            $item = $item->where('list_of_items.branch_id', '=', $this->branch_id);
        }

        if ($this->keyword) {

            $item = $item->where('list_of_items.item_name', 'like', '%' . $this->keyword . '%')
                ->orwhere('branches.branch_name', 'like', '%' . $this->keyword . '%')
                ->orwhere('users.fullname', 'like', '%' . $this->keyword . '%');
        }

        if ($this->orderby) {
            $item = $item->orderBy($this->column, $this->orderby);
        }

        $item = $item->orderBy('list_of_items.id', 'desc');

        $item = $item->get();

        $val = 1;
        foreach ($item as $key) {
            $key->number = $val;
            $val++;
        }

        return $item;
    }

    public function headings(): array
    {
        return [
            ['No.', 'Nama Barang', 'Jumlah Barang', 'Harga Jual', 'Harga Modal', 'Keuntungan', 'Cabang', 'Dibuat Oleh',
                'Tanggal Dibuat'],
        ];
    }

    public function title(): string
    {
        return 'Data Rekap';
    }

    public function map($item): array
    {
        $res = [
            [$item->number,
                $item->item_name,
                $item->total_item,
                number_format($item->selling_price, 2, ".", ","),
                number_format($item->capital_price, 2, ".", ","),
                number_format($item->profit, 2, ".", ","),
                $item->branch_name,
                $item->created_by,
                $item->created_at,
            ],
        ];
        return $res;
    }
}
