<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfferBuySuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_buy_supplies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('offer_buy_id');
            $table->bigInteger('supplies_id');
            $table->bigInteger('quantity');
            $table->bigInteger('unit_price');
            $table->date('date_arrival');
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
        Schema::dropIfExists('offer_buy_supplies');
    }
}
