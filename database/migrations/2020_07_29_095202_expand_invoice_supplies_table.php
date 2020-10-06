<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExpandInvoiceSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_supplies', function (Blueprint $table) {
            if (!Schema::hasColumn('invoice_supplies', 'cumulative'))
            {
                $table->double('cumulative')->default(0);
            }
            if (!Schema::hasColumn('invoice_supplies', 'existing_quantity'))
            {
                $table->double('existing_quantity')->default(0);
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
        Schema::table('invoice_supplies', function (Blueprint $table) {
            if (Schema::hasColumn('invoice_supplies', 'cumulative'))
            {
                $table->dropColumn('cumulative');
            }
            if (Schema::hasColumn('invoice_supplies', 'existing_quantity'))
            {
                $table->dropColumn('existing_quantity');
            }
        });
    }
}
