<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProjectRequestItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_request_items', function (Blueprint $table) {
            $table->renameColumn('supplies_id', 'supply_id');
            $table->integer('project_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_request_items', function (Blueprint $table) {
            $table->renameColumn('supply_id', 'supplies_id');
            $table->dropColumn('project_id');
        });
    }
}
