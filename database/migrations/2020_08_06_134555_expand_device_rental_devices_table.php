<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExpandDeviceRentalDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_rental_devices', function (Blueprint $table) {
            if (!Schema::hasColumn('device_rental_devices', 'start_date'))
            {
                $table->date('start_date');
            }
            if (!Schema::hasColumn('device_rental_devices', 'end_date'))
            {
                $table->date('end_date');
            }
            if (!Schema::hasColumn('device_rental_devices', 'days_used'))
            {
                $table->bigInteger('days_used');
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
        Schema::table('device_rental_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_rental_devices', 'start_date'))
            {
                $table->dropColumn('start_date');
            }
            if (Schema::hasColumn('device_rental_devices', 'end_date'))
            {
                $table->dropColumn('end_date');
            }
            if (Schema::hasColumn('device_rental_devices', 'days_used'))
            {
                $table->dropColumn('days_used');
            }
        });
    }
}
