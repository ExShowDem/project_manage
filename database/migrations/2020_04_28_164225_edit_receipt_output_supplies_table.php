<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditReceiptOutputSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receipt_output_supplies', function (Blueprint $table) {
            if (!Schema::hasColumn('receipt_output_supplies', 'quantity_in_stock'))
            {
                $table->bigInteger('quantity_in_stock');
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
        Schema::table('receipt_output_supplies', function (Blueprint $table) {
            if (Schema::hasColumn('receipt_output_supplies', 'quantity_in_stock'))
            {
                $table->dropColumn('quantity_in_stock');
            }
        });
    }
}
