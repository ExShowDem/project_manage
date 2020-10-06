<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStocktakingSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocktaking_supplies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('supply_id');
            $table->integer('stocktaking_id');
            $table->integer('price');
            $table->integer('quantity_system');
            $table->integer('quantity_actual');
            $table->string('reason')->nullable();
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
        Schema::dropIfExists('stocktaking_supplies');
    }
}
