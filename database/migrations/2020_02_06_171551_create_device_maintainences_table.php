<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Enums\CommonStatus;

class CreateDeviceMaintainencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('device_maintainences')) {
            Schema::create('device_maintainences', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('code');
                $table->string('name');
                $table->bigInteger('project_id');
                $table->date('date');
                $table->bigInteger('creator_id');
                $table->string('result');
                $table->tinyInteger('status')->default(CommonStatus::CREATED);
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
        Schema::dropIfExists('device_maintainences');
    }
}
