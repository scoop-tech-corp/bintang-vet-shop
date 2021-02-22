<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListofPayments extends Model
{
    protected $table = "list_of_payments";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['check_up_result_id',
        'user_id'];
}
