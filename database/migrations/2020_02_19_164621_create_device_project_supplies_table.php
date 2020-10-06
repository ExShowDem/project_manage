<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceProjectSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('device_project_supplies')) {
            Schema::create('device_project_supplies', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('device_project_id');
                $table->bigInteger('supplies_id');
                $table->bigInteger('quantity');
                $table->bigInteger('quantity_returned');
                $table->bigInteger('unit_price_budget');
                $table->string('note');
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
        Schema::dropIfExists('device_project_supplies');
    }
}
