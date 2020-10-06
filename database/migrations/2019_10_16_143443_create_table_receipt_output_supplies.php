<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableReceiptOutputSupplies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_output_supplies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('output_id');
            $table->bigInteger('supplies_id');
            $table->bigInteger('original_quantity');
            $table->bigInteger('quantity');
            $table->bigInteger('unit_price');
            $table->string('difference_reason')->nullable();
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
        Schema::dropIfExists('receipt_output_supplies');
    }
}
