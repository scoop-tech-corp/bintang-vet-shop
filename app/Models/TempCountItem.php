<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempCountItem extends Model
{
    protected $table = "temp_count_items";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['check_up_result_id', 'price_item_id', 'quantity', 'user_id'];
}
