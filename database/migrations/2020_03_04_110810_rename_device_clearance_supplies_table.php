<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameDeviceClearanceSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('device_clearance_supplies')) 
        {
            Schema::rename('device_clearance_supplies', 'device_clearance_devices');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('device_clearance_devices')) 
        {
            Schema::rename('device_clearance_devices', 'device_clearance_supplies');
        }
    }
}
