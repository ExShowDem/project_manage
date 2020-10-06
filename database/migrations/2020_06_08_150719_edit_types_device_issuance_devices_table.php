<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTypesDeviceIssuanceDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_issuance_devices', function (Blueprint $table) {

            $table->decimal('monthly_estimated_quantity')->change();
            $table->decimal('quantity')->change();
            $table->decimal('quantity1')->change();
            $table->decimal('total_quantity')->change();
            $table->decimal('accumulated_quantity')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('device_issuance_devices', function (Blueprint $table) {
            
            $table->bigInteger('monthly_estimated_quantity')->change();
            $table->bigInteger('quantity')->change();
            $table->bigInteger('quantity1')->change();
            $table->bigInteger('total_quantity')->change();
            $table->bigInteger('accumulated_quantity')->change();
        });
    }
}
