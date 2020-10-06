<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCensorSubcontractorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('censor_subcontractor')) {
            Schema::create('censor_subcontractor', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('censor_id');
                $table->bigInteger('subcontractor_id');
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
        Schema::dropIfExists('censor_subcontractor');
    }
}
