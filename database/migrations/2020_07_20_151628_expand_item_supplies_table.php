<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExpandItemSuppliesTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_supplies', function (Blueprint $table) {
            if (!Schema::hasColumn('item_supplies', 'progress'))
            {
                $table->double('progress')->default(0);
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
        Schema::table('item_supplies', function (Blueprint $table) {
            if (Schema::hasColumn('item_supplies', 'progress'))
            {
                $table->dropColumn('progress');
            }
        });
    }
}
