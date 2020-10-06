<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTypesDeviceEstimateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_estimate_devices', function (Blueprint $table) {

            $table->decimal('mass')->change();
            $table->decimal('mass1')->change();
            $table->decimal('price')->change();
            $table->decimal('rent')->change();
            $table->decimal('rent_price')->change();
            $table->decimal('mass2')->change();
            $table->decimal('estimated_unit_price')->change();
            $table->decimal('total_price')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('device_estimate_devices', function (Blueprint $table) {
            
            $table->bigInteger('mass')->change();
            $table->bigInteger('mass1')->change();
            $table->bigInteger('price')->change();
            $table->bigInteger('rent')->change();
            $table->bigInteger('rent_price')->change();
            $table->bigInteger('mass2')->change();
            $table->bigInteger('estimated_unit_price')->change();
            $table->bigInteger('total_price')->change();
        });
    }
}
