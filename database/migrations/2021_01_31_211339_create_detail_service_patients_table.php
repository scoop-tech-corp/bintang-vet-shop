<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailServicePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_service_patients', function (Blueprint $table) {
            $table->id();
            $table->integer('check_up_result_id');
            $table->integer('price_service_id');
            $table->integer('quantity');
            $table->decimal('price_overall', $precision = 18, $scale = 2);
            $table->boolean('status_paid_off');
            $table->boolean('isDeleted')->nullable()->default(false);
            $table->integer('user_id');
            $table->integer('user_update_id')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamp('deleted_at', 0)->nullable();
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
        Schema::dropIfExists('detail_service_patients');
    }
}
