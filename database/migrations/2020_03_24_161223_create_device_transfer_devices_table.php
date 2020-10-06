<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceTransferDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('device_transfer_devices'))
        {
            Schema::create('device_transfer_devices', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('device_transfer_id');
                $table->bigInteger('devices_id');
                $table->bigInteger('issued_quantity');
                $table->bigInteger('quantity');
                $table->string('carrier_type');
                $table->string('carrier_number');
                $table->string('transfer_unit');
                $table->tinyInteger('transfer_direction');
                $table->bigInteger('project_id');
                $table->date('sent')->nullable();
                $table->date('arrived')->nullable();
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
        Schema::dropIfExists('device_transfer_devices');
    }
}
