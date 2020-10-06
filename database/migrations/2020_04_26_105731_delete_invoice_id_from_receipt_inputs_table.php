<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteInvoiceIdFromReceiptInputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receipt_inputs', function (Blueprint $table) {
            if (Schema::hasColumn('receipt_inputs', 'invoice_id'))
            {
                $table->dropColumn('invoice_id');
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
            if (!Schema::hasColumn('receipt_inputs', 'invoice_id'))
            {
                $table->bigInteger('invoice_id');
            }
        });
    }
}
