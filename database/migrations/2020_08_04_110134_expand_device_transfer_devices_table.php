<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExpandDeviceTransferDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_transfer_devices', function (Blueprint $table) {
            if (!Schema::hasColumn('device_transfer_devices', 'existing_quantity'))
            {
                $table->double('existing_quantity')->default(0);
            }
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
            if (Schema::hasColumn('device_transfer_devices', 'existing_quantity'))
            {
                $table->dropColumn('existing_quantity');
            }
        });
    }
}
