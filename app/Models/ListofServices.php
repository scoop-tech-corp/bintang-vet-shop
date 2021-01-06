<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListofServices extends Model
{
    protected $table = "list_of_services";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['service_name',
    'service_category_id',
    'branch_id',
    'user_id'];
}
