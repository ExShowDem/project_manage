<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateReceiptOutputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receipt_outputs', function (Blueprint $table) {
            if (Schema::hasColumn('receipt_outputs', 'export_type'))
            {
                $table->dropColumn('export_type');
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
        Schema::table('receipt_outputs', function (Blueprint $table) {
            if (!Schema::hasColumn('receipt_outputs', 'export_type'))
            {
                $table->bigInteger('export_type')->nullable();
            }
        });
    }
}
