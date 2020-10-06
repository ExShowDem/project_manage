<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApproveLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approve_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('approved_by');
            $table->tinyInteger('status');
            $table->bigInteger('logable_id');
            $table->string('logable_type');
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
        Schema::dropIfExists('approve_logs');
    }
}
