<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryGoods extends Model
{
    use SoftDeletes;
    
    protected $table = "category_goods";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['category_name','created_by'];
}
