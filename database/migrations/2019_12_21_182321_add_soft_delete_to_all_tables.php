<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeleteToAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('inventories_log', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('mpp_links', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('mpp_tasks', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('process_logs', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('ticket_imports', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('work_plans', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('inventories_log', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('mpp_links', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('mpp_tasks', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('process_logs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('ticket_imports', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('work_plans', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
