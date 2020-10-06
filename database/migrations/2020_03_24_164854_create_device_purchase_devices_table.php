<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicePurchaseDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('device_purchase_devices')) {
            Schema::create('device_purchase_devices', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('device_purchase_id');
                $table->bigInteger('devices_id');
                $table->bigInteger('requested_quantity');
                $table->bigInteger('quantity');
                $table->bigInteger('unit_price');
                $table->bigInteger('total_price');
                $table->string('note');
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
        Schema::dropIfExists('device_purchase_devices');
    }
}
