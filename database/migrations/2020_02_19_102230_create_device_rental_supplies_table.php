<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceRentalSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('device_rental_supplies')) {
            Schema::create('device_rental_supplies', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('device_rental_id');
                $table->bigInteger('supplies_id');
                $table->bigInteger('quantity');
                $table->bigInteger('unit_price_budget');
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_rental_supplies');
    }
}
