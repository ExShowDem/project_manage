<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExpandReceiptTransferSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receipt_transfer_supplies', function (Blueprint $table) {
            if (!Schema::hasColumn('receipt_transfer_supplies', 'quantity_input'))
            {
                $table->decimal('quantity_input');
            }

            if (!Schema::hasColumn('receipt_transfer_supplies', 'quantity_output'))
            {
                $table->decimal('quantity_output');
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
        Schema::table('receipt_transfer_supplies', function (Blueprint $table) {
            if (Schema::hasColumn('receipt_transfer_supplies', 'quantity_input'))
            {
                $table->dropColumn('quantity_input');
            }

            if (Schema::hasColumn('receipt_transfer_supplies', 'quantity_output'))
            {
                $table->dropColumn('quantity_output');
            }
        });
    }
}
