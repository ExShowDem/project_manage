<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Enums\CommonStatus;

class CreateDeviceInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('device_inventories')) {
            Schema::create('device_inventories', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('code');
                $table->string('name');
                $table->bigInteger('project_id');
                $table->date('date');
                $table->bigInteger('creator_id');
                $table->string('reason');
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
        Schema::dropIfExists('device_inventories');
    }
}
