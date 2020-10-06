<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemIdToProjectRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_requests', function (Blueprint $table) {
            $table->integer('item_id');
            $table->date('created_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_requests', function (Blueprint $table) {
            $table->dropColumn('item_id');
            //$table->dropColumn('created_date');
        });
    }
}
