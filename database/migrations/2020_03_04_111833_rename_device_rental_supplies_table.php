<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameDeviceRentalSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('device_rental_supplies')) 
        {
            Schema::rename('device_rental_supplies', 'device_rental_devices');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('device_rental_devices')) 
        {
            Schema::rename('device_rental_devices', 'device_rental_supplies');
        }
    }
}
