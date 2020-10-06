<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTypesDeviceTransferDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_transfer_devices', function (Blueprint $table) {

            $table->decimal('issued_quantity')->change();
            $table->decimal('quantity')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('device_transfer_devices', function (Blueprint $table) {
            
            $table->bigInteger('issued_quantity')->change();
            $table->bigInteger('quantity')->change();
        });
    }
}
