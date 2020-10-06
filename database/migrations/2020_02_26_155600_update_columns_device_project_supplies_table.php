<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColumnsDeviceProjectSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_project_supplies', function (Blueprint $table) {
            if (Schema::hasColumn('device_project_supplies', 'quantity_returned'))
            {
                $table->dropColumn('quantity_returned');
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
        Schema::table('device_project_supplies', function (Blueprint $table) {
            if (!Schema::hasColumn('device_project_supplies', 'quantity_returned'))
            {
                $table->string('quantity_returned');
            }
        });
    }
}
