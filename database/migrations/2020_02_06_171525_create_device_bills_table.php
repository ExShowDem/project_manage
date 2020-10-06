<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('device_bills')) {
            Schema::create('device_bills', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('code');
                $table->string('name');
                $table->bigInteger('total_quantity');
                $table->bigInteger('project_id');
                $table->enum('type', ['increase', 'decrease'])->default('increase');
                $table->bigInteger('unit_id');
                $table->date('date');
                $table->bigInteger('days_used');
                $table->bigInteger('quantity');
                $table->float('unit_price');
                $table->float('total_price');
                $table->timestamps();
                $table->softDeletes();
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
        Schema::dropIfExists('device_bills');
    }
}
