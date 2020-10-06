<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateReceiptInputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receipt_inputs', function (Blueprint $table) {
            if (Schema::hasColumn('receipt_inputs', 'offer_buy_id'))
            {
                $table->renameColumn('offer_buy_id', 'request_id');
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
            if (Schema::hasColumn('receipt_inputs', 'request_id'))
            {
                $table->renameColumn('request_id', 'offer_buy_id');
            }
        });
    }
}
