<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameDeviceSuppliesColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_clearance_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_clearance_devices', 'supplies_id'))
            {
                $table->renameColumn('supplies_id', 'devices_id');
            }
        });

        Schema::table('device_maintainence_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_maintainence_devices', 'supplies_id'))
            {
                $table->renameColumn('supplies_id', 'devices_id');
            }
        });

        Schema::table('device_rental_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_rental_devices', 'supplies_id'))
            {
                $table->renameColumn('supplies_id', 'devices_id');
            }
        });

        Schema::table('device_inventory_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_inventory_devices', 'supplies_id'))
            {
                $table->renameColumn('supplies_id', 'devices_id');
            }
        });

        Schema::table('device_project_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_project_devices', 'supplies_id'))
            {
                $table->renameColumn('supplies_id', 'devices_id');
            }
        });

        Schema::table('device_company_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_company_devices', 'supplies_id'))
            {
                $table->renameColumn('supplies_id', 'devices_id');
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
        Schema::table('device_clearance_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_clearance_devices', 'devices_id'))
            {
                $table->renameColumn('devices_id', 'supplies_id');
            }
        });

        Schema::table('device_maintainence_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_maintainence_devices', 'devices_id'))
            {
                $table->renameColumn('devices_id', 'supplies_id');
            }
        });

        Schema::table('device_rental_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_rental_devices', 'devices_id'))
            {
                $table->renameColumn('devices_id', 'supplies_id');
            }
        });

        Schema::table('device_inventory_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_inventory_devices', 'devices_id'))
            {
                $table->renameColumn('devices_id', 'supplies_id');
            }
        });

        Schema::table('device_project_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_project_devices', 'devices_id'))
            {
                $table->renameColumn('devices_id', 'supplies_id');
            }
        });
        
        Schema::table('device_company_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_company_devices', 'devices_id'))
            {
                $table->renameColumn('devices_id', 'supplies_id');
            }
        });
    }
}
