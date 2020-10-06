<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExistingQuantityColumnToDeviceInventorySuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_inventory_supplies', function (Blueprint $table) {
            if (!Schema::hasColumn('device_inventory_supplies', 'existing_quantity'))
            {
                $table->bigInteger('existing_quantity');
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
        Schema::table('device_inventory_supplies', function (Blueprint $table) {
            if (Schema::hasColumn('device_inventory_supplies', 'existing_quantity'))
            {
                $table->dropColumn('existing_quantity');
            }
        });
    }
}
