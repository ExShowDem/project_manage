<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('devices_details')) {
            Schema::create('devices_details', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('devices_id');
                $table->string('material_code')->nullable();
                $table->string('size')->nullable();
                $table->string('specification')->nullable();
                $table->string('supplier')->nullable();
                $table->string('color')->nullable();
                $table->string('intensity')->nullable();
                $table->string('density')->nullable();
                $table->string('standard')->nullable();
                $table->string('source')->nullable();
                $table->softDeletes();
                $table->timestamps();
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
        Schema::dropIfExists('devices_details');
    }
}
