<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryItemMovement extends Model
{
    protected $table = "history_item_movements";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['price_item_id', 'quantity', 'user_id','status'];
}
