<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InPatient extends Model
{
    protected $table = "in_patients";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['id_number','patient_id','complaint',
    'registrant','user_id','doctor_user_id','estimate_day','reality_day','acceptance_status'];
}
