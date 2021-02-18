<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorAcceptance extends Model
{
    protected $table = "doctor_acceptances";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['patient_registration_id','acceptance_status','reason','user_id'];
}
