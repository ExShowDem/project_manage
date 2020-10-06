<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceIssuanceDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('device_issuance_devices'))
        {
            Schema::create('device_issuance_devices', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('device_issuance_id');
                $table->bigInteger('devices_id');
                $table->bigInteger('monthly_estimated_quantity');
                $table->bigInteger('quantity');
                $table->date('supply_date')->nullable();
                $table->date('return_date')->nullable();
                $table->date('supply_date1')->nullable();
                $table->bigInteger('quantity1');
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
        Schema::dropIfExists('device_issuance_devices');
    }
}
