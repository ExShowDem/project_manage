<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProgressToDevicePurchaseRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_purchase_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('device_purchase_requests', 'progress'))
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
        Schema::table('device_purchase_requests', function (Blueprint $table) {
            if (Schema::hasColumn('device_purchase_requests', 'progress'))
            {
                $table->dropColumn('progress');
            }
        });
    }
}
