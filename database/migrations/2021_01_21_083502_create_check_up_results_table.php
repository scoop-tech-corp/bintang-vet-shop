<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckUpResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_up_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('patient_registration_id');
            $table->text('anamnesa');
            $table->text('sign');
            $table->text('diagnosa');
            $table->boolean('status_outpatient_inpatient');
            $table->boolean('status_finish');
            $table->boolean('status_paid_off');
            $table->boolean('isDeleted')->nullable()->default(false);
            $table->unsignedBigInteger('user_id');
            $table->integer('user_update_id')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamp('deleted_at', 0)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('patient_registration_id')->references('id')->on('registrations');
            //$table->foreign('gallery_id')->references('id')->on('gallery');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('check_up_results');
    }
}
