<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Enums\CommonStatus;

class CreateDevicesToProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('devices_to_project')) {
            Schema::create('devices_to_project', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('code');
                $table->string('company');
                $table->bigInteger('project_id');
                $table->bigInteger('creator_id');
                $table->date('created_date');
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
        Schema::dropIfExists('devices_to_project');
    }
}
