<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExpandItemSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_supplies', function (Blueprint $table) {
            if (!Schema::hasColumn('item_supplies', 'is_vlk'))
            {
                $table->boolean('is_vlk')->default(false);
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
            if (Schema::hasColumn('item_supplies', 'is_vlk'))
            {
                $table->dropColumn('is_vlk');
            }
        });
    }
}
