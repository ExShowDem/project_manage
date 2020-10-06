<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteDeviceStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('device_stats');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('device_stats')) {
            Schema::create('device_stats', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('code');
                $table->string('name');
                $table->bigInteger('unit_id');
                $table->bigInteger('total_quantity');
                $table->bigInteger('quantity');
                $table->bigInteger('project_id');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }
}
