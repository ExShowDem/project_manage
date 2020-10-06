<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Enums\CommonStatus;

class CreateDevicePurchaseRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('device_purchase_requests'))
        {
            Schema::create('device_purchase_requests', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('code');
                $table->string('month');
                $table->bigInteger('project_id');
                $table->bigInteger('creator_id');
                $table->tinyInteger('status')->default(CommonStatus::CREATED);
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
        Schema::dropIfExists('device_purchase_requests');
    }
}
