<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceEstimateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('device_estimate_devices')) {
            Schema::create('device_estimate_devices', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('device_estimate_id');
                $table->bigInteger('devices_id');
                $table->bigInteger('mass');
                $table->bigInteger('mass1');
                $table->bigInteger('price');
                $table->bigInteger('rent');
                $table->bigInteger('rent_price');
                $table->bigInteger('mass2');
                $table->bigInteger('estimated_unit_price');
                $table->date('input_time');                
                $table->date('return_time');
                $table->bigInteger('days_used');
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
        Schema::dropIfExists('device_estimate_devices');
    }
}
