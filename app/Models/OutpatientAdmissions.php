<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutpatientAdmissions extends Model
{
    protected $table = "outpatient_admissions";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['outpatient_id','acceptance_status','reason','user_id'];
}
