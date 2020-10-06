<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTypesDevicePurchaseRequestDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_purchase_request_devices', function (Blueprint $table) {

            $table->decimal('quantity')->change();
            $table->decimal('estimated_quantity')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('device_purchase_request_devices', function (Blueprint $table) {
            
            $table->bigInteger('quantity')->change();
            $table->bigInteger('estimated_quantity')->change();
        });
    }
}
