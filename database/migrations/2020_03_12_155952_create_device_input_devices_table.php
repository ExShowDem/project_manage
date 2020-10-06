<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceInputDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('device_input_devices')) {
            Schema::create('device_input_devices', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('device_input_id');
                $table->bigInteger('devices_id');
                $table->bigInteger('quantity');
                $table->bigInteger('existing_quantity');
                $table->bigInteger('unit_price');
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
        Schema::dropIfExists('device_input_devices');
    }
}
