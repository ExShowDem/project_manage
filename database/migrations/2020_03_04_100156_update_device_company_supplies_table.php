<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDeviceCompanySuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_company_supplies', function (Blueprint $table) {
            if (Schema::hasColumn('device_company_supplies', 'device_project_id'))
            {
                $table->renameColumn('device_project_id', 'device_company_id');
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
        Schema::table('device_company_supplies', function (Blueprint $table) {
            if (Schema::hasColumn('device_company_supplies', 'device_company_id'))
            {
                $table->renameColumn('device_company_id', 'device_project_id');
            }
        });
    }
}
