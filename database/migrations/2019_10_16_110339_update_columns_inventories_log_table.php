<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColumnsInventoriesLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventories_log', function (Blueprint $table) {
            $table->renameColumn('project_id', 'inventory_id');
            $table->dropColumn('supply_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventories_log', function (Blueprint $table) {
            $table->renameColumn('inventory_id', 'project_id');
            $table->integer('supply_id');
        });
    }
}
