<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListofItems extends Model
{
    protected $table = "list_of_items";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['item_name',
        'total_item', 'selling_price', 'capital_price', 'profit', 'image', 'category',
        'branch_id', 'user_id', 'user_update_id', 'limit_item,', 'diff_item', 'diff_expired_days', 'expired_date'];
}
