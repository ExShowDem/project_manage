<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceContractDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('device_contract_devices'))
        {
            Schema::create('device_contract_devices', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('device_contract_id');
                $table->bigInteger('devices_id');
                $table->bigInteger('needed_quantity');
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
        Schema::dropIfExists('device_contract_devices');
    }
}
