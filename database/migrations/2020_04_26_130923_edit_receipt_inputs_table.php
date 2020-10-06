<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditReceiptInputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receipt_inputs', function (Blueprint $table) {
            if (Schema::hasColumn('receipt_inputs', 'request_supply_id'))
            {
                $table->dropColumn('request_supply_id');
            }
        });

        Schema::table('receipt_inputs', function (Blueprint $table) {
            if (!Schema::hasColumn('receipt_inputs', 'offer_buy_id'))
            {
                $table->bigInteger('offer_buy_id');
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
        Schema::table('receipt_inputs', function (Blueprint $table) {
            if (!Schema::hasColumn('receipt_inputs', 'request_supply_id'))
            {
                $table->bigInteger('request_supply_id');
            }
        });
        
        Schema::table('receipt_inputs', function (Blueprint $table) {
            if (Schema::hasColumn('receipt_inputs', 'offer_buy_id'))
            {
                $table->dropColumn('offer_buy_id');
            }
        });
    }
}
