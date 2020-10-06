<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExpandStocktakingSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stocktaking_supplies', function (Blueprint $table) {
            if (!Schema::hasColumn('stocktaking_supplies', 'diff'))
            {
                $table->double('diff')->default(0);
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
        Schema::table('stocktaking_supplies', function (Blueprint $table) {
            if (Schema::hasColumn('stocktaking_supplies', 'diff'))
            {
                $table->dropColumn('diff');
            }
        });
    }
}
