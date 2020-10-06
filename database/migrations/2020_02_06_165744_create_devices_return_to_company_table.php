<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Enums\CommonStatus;

class CreateDevicesReturnToCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('devices_return_to_company')) {
            Schema::create('devices_return_to_company', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('code');
                $table->bigInteger('devices_to_project_id');
                $table->bigInteger('project_id');
                $table->string('company');
                $table->bigInteger('user_id');
                $table->date('return_date');
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
        Schema::dropIfExists('devices_return_to_company');
    }
}
