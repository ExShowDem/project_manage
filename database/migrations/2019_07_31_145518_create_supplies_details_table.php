<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplies_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('supplies_id');
            $table->string('material_code');
            $table->string('size');
            $table->string('supplier');
            $table->string('color');
            $table->string('intensity');
            $table->string('density');
            $table->string('standard');
            $table->string('source');
            $table->softDeletes();
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
        Schema::dropIfExists('supplies_details');
    }
}
