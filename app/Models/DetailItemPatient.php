<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailItemPatient extends Model
{
    protected $table = "detail_item_patients";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['check_up_result_id', 'item_id', 'quantity', 'price_overall', 'user_id'];
}