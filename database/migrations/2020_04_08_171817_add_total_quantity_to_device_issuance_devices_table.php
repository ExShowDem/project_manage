<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalQuantityToDeviceIssuanceDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_issuance_devices', function (Blueprint $table) {
            if (!Schema::hasColumn('device_issuance_devices', 'total_quantity'))
            {
                $table->bigInteger('total_quantity');
            }
            if (!Schema::hasColumn('device_issuance_devices', 'accumulated_quantity'))
            {
                $table->bigInteger('accumulated_quantity');
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
        Schema::table('device_issuance_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_issuance_devices', 'total_quantity'))
            {
                $table->dropColumn('total_quantity');
            }
            if (Schema::hasColumn('device_issuance_devices', 'accumulated_quantity'))
            {
                $table->dropColumn('accumulated_quantity');
            }
        });
    }
}
