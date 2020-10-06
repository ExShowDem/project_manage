<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProgressToDevicePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_purchases', function (Blueprint $table) {
            if (!Schema::hasColumn('device_purchases', 'progress'))
            {
                $table->float('progress')->default(0);
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
        Schema::table('device_purchases', function (Blueprint $table) {
            if (Schema::hasColumn('device_purchases', 'progress'))
            {
                $table->dropColumn('progress');
            }
        });
    }
}
