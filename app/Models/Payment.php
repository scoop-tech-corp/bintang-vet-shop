<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = "payments";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['list_of_item_id', 'master_payment_id',
        'total_item', 'user_id', 'user_update_id'];
}
