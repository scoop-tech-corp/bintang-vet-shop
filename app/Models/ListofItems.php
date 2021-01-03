<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListofItems extends Model
{
    protected $table = "list_of_items";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['item_name',
    'total_item',
    'unit_goods_id',
    'category_goods_id',
    'branch_id',
    'created_by'];
}
