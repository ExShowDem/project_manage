<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceMonthlyEstimateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('device_monthly_estimate_devices'))
        {
            Schema::create('device_monthly_estimate_devices', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('device_monthly_estimate_id');
                $table->bigInteger('devices_id');
                $table->tinyInteger('type');
                $table->bigInteger('total_quantity');
                $table->bigInteger('quantity');
                $table->date('batch1')->nullable();
                $table->date('batch2')->nullable();
                $table->date('batch3')->nullable();
                $table->date('batch4')->nullable();
                $table->date('batch5')->nullable();
                $table->date('batch6')->nullable();
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
        Schema::dropIfExists('device_monthly_estimate_devices');
    }
}
