<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListofPaymentItem extends Model
{
    protected $table = "list_of_payment_items";

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $fillable = ['detail_item_patient_id', 'check_up_result_id',
        'user_id'];
}
