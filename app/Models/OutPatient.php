<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutPatient extends Model
{
    protected $table = "out_patients";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['id_number','patient_id','complaint',
    'registrant','user_id','doctor_user_id','acceptance_status'];
}
