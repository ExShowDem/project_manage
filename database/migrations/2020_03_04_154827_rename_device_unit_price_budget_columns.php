<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameDeviceUnitPriceBudgetColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_clearance_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_clearance_devices', 'unit_price_budget'))
            {
                $table->renameColumn('unit_price_budget', 'unit_price');
            }
        });

        Schema::table('device_maintainence_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_maintainence_devices', 'unit_price_budget'))
            {
                $table->renameColumn('unit_price_budget', 'unit_price');
            }
        });

        Schema::table('device_rental_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_rental_devices', 'unit_price_budget'))
            {
                $table->renameColumn('unit_price_budget', 'unit_price');
            }
        });

        Schema::table('device_inventory_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_inventory_devices', 'unit_price_budget'))
            {
                $table->renameColumn('unit_price_budget', 'unit_price');
            }
        });

        Schema::table('device_project_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_project_devices', 'unit_price_budget'))
            {
                $table->renameColumn('unit_price_budget', 'unit_price');
            }
        });

        Schema::table('device_company_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_company_devices', 'unit_price_budget'))
            {
                $table->renameColumn('unit_price_budget', 'unit_price');
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
            if (Schema::hasColumn('device_clearance_devices', 'unit_price'))
            {
                $table->renameColumn('unit_price', 'unit_price_budget');
            }
        });

        Schema::table('device_maintainence_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_maintainence_devices', 'unit_price'))
            {
                $table->renameColumn('unit_price', 'unit_price_budget');
            }
        });

        Schema::table('device_rental_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_rental_devices', 'unit_price'))
            {
                $table->renameColumn('unit_price', 'unit_price_budget');
            }
        });

        Schema::table('device_inventory_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_inventory_devices', 'unit_price'))
            {
                $table->renameColumn('unit_price', 'unit_price_budget');
            }
        });

        Schema::table('device_project_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_project_devices', 'unit_price'))
            {
                $table->renameColumn('unit_price', 'unit_price_budget');
            }
        });
        
        Schema::table('device_company_devices', function (Blueprint $table) {
            if (Schema::hasColumn('device_company_devices', 'unit_price'))
            {
                $table->renameColumn('unit_price', 'unit_price_budget');
            }
        });
    }
}
