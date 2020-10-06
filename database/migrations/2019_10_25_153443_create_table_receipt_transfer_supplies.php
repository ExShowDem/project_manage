<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableReceiptTransferSupplies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_transfer_supplies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('input_id');
            $table->bigInteger('supplies_id');
            $table->bigInteger('quantity');
            $table->bigInteger('unit_price');
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
        Schema::dropIfExists('receipt_transfer_supplies');
    }
}
