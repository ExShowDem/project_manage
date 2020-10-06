<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusAndProjectIdToProjectRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_requests', function (Blueprint $table) {
            $table->tinyInteger('status');
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
        Schema::table('project_requests', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('project_id');
        });
    }
}
