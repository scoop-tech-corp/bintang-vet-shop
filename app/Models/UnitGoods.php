<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitGoods extends Model
{
    //use SoftDeletes;
    
    protected $table = "unit_goods";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['unit_name','created_by'];
}
