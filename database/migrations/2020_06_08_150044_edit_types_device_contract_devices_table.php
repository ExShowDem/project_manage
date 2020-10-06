<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTypesDeviceContractDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_contract_devices', function (Blueprint $table) {

            $table->decimal('quantity')->change();
            $table->decimal('unit_price')->change();
            $table->decimal('needed_quantity')->change();
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
        Schema::table('device_contract_devices', function (Blueprint $table) {
            
            $table->bigInteger('quantity')->change();
            $table->bigInteger('unit_price')->change();
            $table->bigInteger('needed_quantity')->change();
            $table->bigInteger('total_price')->change();
        });
    }
}
