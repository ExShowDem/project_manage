<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSuppliesIdToProjectRequestItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_request_items', function (Blueprint $table) {
            $table->integer('supplies_id');
            $table->integer('project_request_id');
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
            $table->dropColumn('supplies_id');
            $table->dropColumn('project_request_id');
        });
    }
}
