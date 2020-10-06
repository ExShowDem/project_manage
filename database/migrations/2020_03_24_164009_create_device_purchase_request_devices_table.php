<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicePurchaseRequestDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('device_purchase_request_devices')) {
            Schema::create('device_purchase_request_devices', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('device_purchase_request_id');
                $table->bigInteger('devices_id');
                $table->bigInteger('estimated_quantity');
                $table->bigInteger('quantity');
                $table->date('required_return_date')->nullable();
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
        Schema::dropIfExists('device_purchase_request_devices');
    }
}
