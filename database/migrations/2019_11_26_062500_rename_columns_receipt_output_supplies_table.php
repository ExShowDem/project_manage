<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnsReceiptOutputSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receipt_output_supplies', function (Blueprint $table) {
            $table->renameColumn('original_quantity', 'quantity_needed');
            $table->dropColumn('difference_reason');
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
            $table->renameColumn('quantity_needed', 'original_quantity');
            $table->string('difference_reason')->nullable();
        });
    }
}
