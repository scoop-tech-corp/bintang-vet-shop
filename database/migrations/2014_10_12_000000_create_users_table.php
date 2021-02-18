<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('staffing_number')->nullable();
            $table->string('username')->unique();
            $table->string('fullname');
            $table->string('gender')->nullable();
            $table->string('religion')->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('id_card_number')->nullable();
            $table->string('email')->unique();
            $table->string('password');            
            $table->string('home_number')->nullable();
            $table->string('phone_number')->unique();
            $table->string('address')->nullable();
            $table->string('image_profile')->nullable();
            $table->string('role')->nullable();
            $table->integer('branch_id');
            $table->boolean('status');                   //aktif atau tidak jika tidak aktif maka tidak dapat login
            $table->string('created_by');
            $table->string('update_by')->nullable();    //siapa yang akan mengubah status user
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
        Schema::dropIfExists('users');
    }
}
