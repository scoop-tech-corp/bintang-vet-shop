<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceMedicineGroup extends Model
{
    protected $table = "price_medicine_groups";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['medicine_group_id', 'selling_price', 'capital_price',
        'doctor_fee', 'petshop_fee', 'user_id'];
}
