<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameDeviceInventorySuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('device_inventory_supplies')) 
        {
            Schema::rename('device_inventory_supplies', 'device_inventory_devices');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('device_inventory_devices')) 
        {
            Schema::rename('device_inventory_devices', 'device_inventory_supplies');
        }
    }
}
