<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('action_log')) {
            Schema::create('action_log', function (Blueprint $table) {
                $table->increments('id');
                $table->timestamp('time')->useCurrent();
                $table->unsignedBigInteger('user_id');
                $table->string('user_name', 100);
                $table->string('model', 100);
                $table->string('table_name', 100);
                $table->string('method', 100);
                $table->string('record_key_name', 50);
                $table->unsignedInteger('record_id');
                $table->mediumText('params');
                $table->tinyInteger('is_restored')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('action_log');
    }
}
