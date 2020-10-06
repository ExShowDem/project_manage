<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExpandReceiptInputSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receipt_input_supplies', function (Blueprint $table) {
            if (!Schema::hasColumn('receipt_input_supplies', 'cumulative'))
            {
                $table->double('cumulative')->default(0);
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
        Schema::table('receipt_input_supplies', function (Blueprint $table) {
            if (Schema::hasColumn('receipt_input_supplies', 'cumulative'))
            {
                $table->dropColumn('cumulative');
            }
        });
    }
}
