<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditDevicePurchaseRequestsTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_purchase_requests', function (Blueprint $table) {
            if (Schema::hasColumn('device_purchase_requests', 'estimate_id'))
            {
                $table->bigInteger('estimate_id')->charset(null)->change();
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
                $table->string('estimate_id')->change();
            }
        });
    }
}
