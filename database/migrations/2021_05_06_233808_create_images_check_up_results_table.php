<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesCheckUpResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images_check_up_results', function (Blueprint $table) {
            $table->id();
            $table->integer('check_up_result_id');
            $table->string('image');
            $table->boolean('isDeleted')->nullable()->default(false);
            $table->integer('user_id');
            $table->integer('user_update_id')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamp('deleted_at',0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images_check_up_results');
    }
}
