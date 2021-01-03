<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListofItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_of_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->integer('total_item');
            $table->integer('unit_goods_id');        
            $table->integer('category_goods_id');
            $table->integer('branch_id');            
            $table->boolean('isDeleted')->nullable()->default(false);
            $table->string('created_by');
            $table->string('update_by')->nullable();
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
        Schema::dropIfExists('list_of_items');
    }
}
