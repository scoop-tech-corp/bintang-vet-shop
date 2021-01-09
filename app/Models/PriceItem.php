<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceItem extends Model
{
    protected $table = "price_items";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['list_of_items_id','selling_price','capital_price',
    'doctor_fee','petshop_fee','user_id'];
}
