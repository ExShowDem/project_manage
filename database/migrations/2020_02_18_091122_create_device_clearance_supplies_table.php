<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceClearanceSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('device_clearance_supplies')) {
            Schema::create('device_clearance_supplies', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('device_clearance_id');
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
        Schema::dropIfExists('device_clearance_supplies');
    }
}
