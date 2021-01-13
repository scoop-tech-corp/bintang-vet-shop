<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InpatientAdmissions extends Model
{
    protected $table = "inpatient_admissions";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['inpatient_id','acceptance_status','reason','user_id'];
}
