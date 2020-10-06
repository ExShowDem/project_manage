<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id');
            $table->integer('from_user');
            $table->integer('to_user');
            $table->string('name');
            $table->string('code');
            $table->text('comment')->nullable();
            $table->dateTime('process_date')->nullable();
            $table->dateTime('created_date');
            $table->tinyInteger('status');
            $table->morphs('taskable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
