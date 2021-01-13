<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('in_patients', function (Blueprint $table) {
            $table->id();
            $table->string('id_number');
            $table->integer('patient_id');
            $table->string('complaint');
            $table->string('registrant');
            $table->integer('estimate_day');
            $table->integer('reality_day');
            $table->integer('doctor_user_id');
            $table->integer('user_id');
            $table->integer('acceptance_status');            
            $table->boolean('isDeleted')->nullable()->default(false);            
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
        Schema::dropIfExists('in_patients');
    }
}
