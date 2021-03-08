<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineGroup extends Model
{
    protected $table = "medicine_groups";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['group_name',
        'branch_id',
        'user_id'];
}
