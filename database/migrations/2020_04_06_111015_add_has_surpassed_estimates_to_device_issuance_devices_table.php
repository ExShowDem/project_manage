<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHasSurpassedEstimatesToDeviceIssuanceDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_issuance_devices', function (Blueprint $table) {
            if (!Schema::hasColumn('device_issuance_devices', 'has_surpassed_estimates'))
            {
                $table->tinyInteger('has_surpassed_estimates')->default(0);
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
            if (Schema::hasColumn('device_issuance_devices', 'has_surpassed_estimates'))
            {
                $table->dropColumn('has_surpassed_estimates');
            }
        });
    }
}
