<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMonthlyEstimatesIdToDeviceIssuancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_issuances', function (Blueprint $table) {
            if (!Schema::hasColumn('device_issuances', 'monthly_estimates_id'))
            {
                $table->bigInteger('monthly_estimates_id')->nullable();
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
        Schema::table('device_issuances', function (Blueprint $table) {
            if (Schema::hasColumn('device_issuances', 'monthly_estimates_id'))
            {
                $table->dropColumn('monthly_estimates_id');
            }
        });
    }
}
