<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Master_Payments extends Model
{
    protected $table = "master_payments";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['payment_number', 'user_id','branch_id', 'user_update_id'];
}
