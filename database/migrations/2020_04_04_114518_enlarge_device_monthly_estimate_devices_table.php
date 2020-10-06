<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnlargeDeviceMonthlyEstimateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_monthly_estimate_devices', function (Blueprint $table) {
            if (!Schema::hasColumn('device_monthly_estimate_devices', 'accumulated_quantity'))
            {
                $table->float('accumulated_quantity')->default(0);
            }

            if (!Schema::hasColumn('device_monthly_estimate_devices', 'quantity1'))
            {
                $table->float('quantity1')->default(0);
            }

            if (!Schema::hasColumn('device_monthly_estimate_devices', 'quantity2'))
            {
                $table->float('quantity2')->default(0);
            }

            if (!Schema::hasColumn('device_monthly_estimate_devices', 'quantity3'))
            {
                $table->float('quantity3')->default(0);
            }

            if (!Schema::hasColumn('device_monthly_estimate_devices', 'quantity4'))
            {
                $table->float('quantity4')->default(0);
            }

            if (!Schema::hasColumn('device_monthly_estimate_devices', 'quantity5'))
            {
                $table->float('quantity5')->default(0);
            }

            if (!Schema::hasColumn('device_monthly_estimate_devices', 'quantity6'))
            {
                $table->float('quantity6')->default(0);
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
        Schema::table('device_monthly_estimate_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_monthly_estimate_devices', 'accumulated_quantity'))
            {
                $table->dropColumn('accumulated_quantity');
            }

            if (Schema::hasColumn('device_monthly_estimate_devices', 'quantity1'))
            {
                $table->dropColumn('quantity1');
            }
            
            if (Schema::hasColumn('device_monthly_estimate_devices', 'quantity2'))
            {
                $table->dropColumn('quantity2');
            }
            
            if (Schema::hasColumn('device_monthly_estimate_devices', 'quantity3'))
            {
                $table->dropColumn('quantity3');
            }

            if (Schema::hasColumn('device_monthly_estimate_devices', 'quantity4'))
            {
                $table->dropColumn('quantity4');
            }
            
            if (Schema::hasColumn('device_monthly_estimate_devices', 'quantity5'))
            {
                $table->dropColumn('quantity5');
            }
            
            if (Schema::hasColumn('device_monthly_estimate_devices', 'quantity6'))
            {
                $table->dropColumn('quantity6');
            }
        });
    }
}
