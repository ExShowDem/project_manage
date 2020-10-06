<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSomeInfoOfProjectRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('project_requests', 'supplies_requests');
        Schema::rename('project_request_items', 'supply_each_request');
        Schema::table('supply_each_request', function (Blueprint $table) {
            $table->integer('quantity_actual')->default(0);
            $table->renameColumn('project_request_id', 'supplies_request_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('supplies_requests', 'project_requests');
        Schema::rename('supply_each_request', 'project_request_items');
        Schema::table('supply_each_request', function (Blueprint $table) {
            $table->dropColumn('quantity_actual');
            $table->renameColumn('supplies_request_id', 'project_request_id');
        });
    }
}
