<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InPatient extends Model
{
    protected $table = "in_patients";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['check_up_result_id', 'description', 'user_id'];
}
