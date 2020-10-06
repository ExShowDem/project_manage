<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameDeviceMaintainenceSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('device_maintainence_supplies')) 
        {
            Schema::rename('device_maintainence_supplies', 'device_maintainence_devices');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('device_maintainence_devices')) 
        {
            Schema::rename('device_maintainence_devices', 'device_maintainence_supplies');
        }
    }
}
