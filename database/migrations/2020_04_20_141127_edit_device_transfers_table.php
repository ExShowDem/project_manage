<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditDeviceTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_transfers', function (Blueprint $table) {
            if (Schema::hasColumn('device_transfers', 'code'))
            {
                $table->dropColumn('code');
            }

            if (!Schema::hasColumn('device_transfers', 'device_issuance_id'))
            {
                $table->bigInteger('device_issuance_id');
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
        Schema::table('device_transfers', function (Blueprint $table) {
            if (!Schema::hasColumn('device_transfers', 'code'))
            {
                $table->string('code');
            }
            
            if (Schema::hasColumn('device_transfers', 'device_issuance_id'))
            {
                $table->dropColumn('device_issuance_id');
            }
        });
    }
}
