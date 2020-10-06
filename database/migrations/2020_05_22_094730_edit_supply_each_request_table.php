<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditSupplyEachRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supply_each_request', function (Blueprint $table) {
            if (!Schema::hasColumn('supply_each_request', 'cumulative'))
            {
                $table->bigInteger('cumulative')->default(0);
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
        Schema::table('supply_each_request', function (Blueprint $table) {
            if (Schema::hasColumn('supply_each_request', 'cumulative'))
            {
                $table->dropColumn('cumulative');
            }
        });
    }
}
