<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMppTaskResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mpp_task_resources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('mpp_task_id');
            $table->integer('resource_id');
            $table->integer('resource_type_id');
            $table->date('tracking_date');
            $table->integer('quantity')->default(0);
            $table->integer('unit_price')->default(0);
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
        Schema::dropIfExists('mpp_task_resources');
    }
}
