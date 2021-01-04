<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryItem extends Model
{
    protected $table = "category_item";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['category_name','user_id'];
}
