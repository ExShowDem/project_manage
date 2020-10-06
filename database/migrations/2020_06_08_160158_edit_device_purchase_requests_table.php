<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditDevicePurchaseRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_purchase_requests', function (Blueprint $table) {
            if (Schema::hasColumn('device_purchase_requests', 'month'))
            {
                $table->renameColumn('month', 'estimate_id');
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
            if (Schema::hasColumn('device_purchase_requests', 'estimate_id'))
            {
                $table->renameColumn('estimate_id', 'month');
            }
        });
    }
}
