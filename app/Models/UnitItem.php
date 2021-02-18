<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitItem extends Model
{
    protected $table = "unit_item";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['unit_name','user_id'];
}
