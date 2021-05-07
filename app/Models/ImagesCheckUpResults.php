<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagesCheckUpResults extends Model
{
  protected $table = "images_check_up_results";

  protected $dates = ['deleted_at'];

  protected $guarded = ['id'];

  protected $fillable = ['check_up_result_id', 'image', 'user_id'];
}
