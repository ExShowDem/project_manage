<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExpandDevicesInputTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devices_input', function (Blueprint $table) {
            if (!Schema::hasColumn('devices_input', 'purchase_request_id'))
            {
                $table->bigInteger('purchase_request_id')->nullable();
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
        Schema::table('devices_input', function (Blueprint $table) {
            if (Schema::hasColumn('devices_input', 'purchase_request_id'))
            {
                $table->dropColumn('purchase_request_id');
            }
        });
    }
}
