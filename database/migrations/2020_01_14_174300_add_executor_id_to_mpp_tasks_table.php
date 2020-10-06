<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExecutorIdToMppTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mpp_tasks', function (Blueprint $table) {
            $table->integer('executor_id')->nullable();
            $table->string('follower_ids')->nullable(); // json field
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mpp_tasks', function (Blueprint $table) {
            $table->dropColumn('executor_id');
            $table->dropColumn('follower_ids');
        });
    }
}
