<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $table = "service_categories";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['category_name','user_id'];
}
