<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditDevicesInputTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devices_input', function (Blueprint $table) {
            if (!Schema::hasColumn('devices_input', 'reason'))
            {
                $table->string('reason')->nullable();
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
            if (Schema::hasColumn('devices_input', 'reason'))
            {
                $table->dropColumn('reason');
            }
        });
    }
}
