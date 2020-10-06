<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceMaintainenceSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('device_maintainence_supplies')) {
            Schema::create('device_maintainence_supplies', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('device_maintainence_id');
                $table->bigInteger('supplies_id');
                $table->bigInteger('quantity');
                $table->bigInteger('unit_price_budget');
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
        Schema::dropIfExists('device_maintainence_supplies');
    }
}
