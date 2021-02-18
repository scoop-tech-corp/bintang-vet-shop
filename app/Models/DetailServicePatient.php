<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailServicePatient extends Model
{
    protected $table = "detail_service_patients";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['check_up_result_id', 'price_service_id', 'quantity', 'price_overall', 'status_paid_off', 'user_id'];
}
