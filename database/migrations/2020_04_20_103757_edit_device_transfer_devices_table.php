<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditDeviceTransferDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_transfer_devices', function (Blueprint $table) {
            if (!Schema::hasColumn('device_transfer_devices', 'from_project'))
            {
                $table->bigInteger('from_project');
            }
            if (!Schema::hasColumn('device_transfer_devices', 'to_project'))
            {
                $table->bigInteger('to_project');
            }

            if (Schema::hasColumn('device_transfer_devices', 'transfer_direction'))
            {
                $table->dropColumn('transfer_direction');
            }
            if (Schema::hasColumn('device_transfer_devices', 'project_id'))
            {
                $table->dropColumn('project_id');
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
            if (Schema::hasColumn('device_transfer_devices', 'from_project'))
            {
                $table->dropColumn('from_project');
            }
            if (Schema::hasColumn('device_transfer_devices', 'to_project'))
            {
                $table->dropColumn('to_project');
            }
            
            if (!Schema::hasColumn('device_transfer_devices', 'transfer_direction'))
            {
                $table->tinyInteger('transfer_direction');
            }
            if (!Schema::hasColumn('device_transfer_devices', 'project_id'))
            {
                $table->bigInteger('project_id');
            }
        });
    }
}
