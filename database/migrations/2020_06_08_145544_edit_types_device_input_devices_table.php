<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTypesDeviceInputDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_input_devices', function (Blueprint $table) {

            $table->decimal('quantity')->change();
            $table->decimal('unit_price')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('device_input_devices', function (Blueprint $table) {
            
            $table->bigInteger('quantity')->change();
            $table->bigInteger('unit_price')->change();
        });
    }
}
