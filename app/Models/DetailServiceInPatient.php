<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailServiceInPatient extends Model
{
    protected $table = "detail_service_in_patients";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['check_up_result_id', 'service_id', 'user_id'];

    public function check_up_results()
    {
        return $this->belongsTo('App\Models\CheckUpResult','id');
    }
}
