<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_supplies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('supplies_id');
            $table->bigInteger('item_id');
            $table->bigInteger('quantity');
            $table->bigInteger('unit_price_budget');
            $table->softDeletes();
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
        Schema::dropIfExists('item_supplies');
    }
}
