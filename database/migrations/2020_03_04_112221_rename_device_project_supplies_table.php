<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameDeviceProjectSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('device_project_supplies')) 
        {
            Schema::rename('device_project_supplies', 'device_project_devices');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('device_project_devices')) 
        {
            Schema::rename('device_project_devices', 'device_project_supplies');
        }
    }
}
