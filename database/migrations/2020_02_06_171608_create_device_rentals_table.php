<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Enums\CommonStatus;

class CreateDeviceRentalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('device_rentals')) {
            Schema::create('device_rentals', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('code');
                $table->string('name');
                $table->bigInteger('project_id');
                $table->tinyInteger('borrower_type');
                $table->bigInteger('borrower_id');
                $table->date('start_date');
                $table->date('end_date');
                $table->bigInteger('creator_id');
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
        Schema::dropIfExists('device_rentals');
    }
}
