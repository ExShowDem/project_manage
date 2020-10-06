<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceRoundRobinDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('device_round_robin_devices')) {
            Schema::create('device_round_robin_devices', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('device_round_robin_id');
                $table->bigInteger('devices_id');
                $table->bigInteger('existing_quantity');
                $table->bigInteger('quantity');
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
        Schema::dropIfExists('device_round_robin_devices');
    }
}
