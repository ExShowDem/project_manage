<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCensorContractSubcontractors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('censor_contract_subcontractors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('subcontractor_id');
            $table->bigInteger('project_id');
            $table->dateTime('date_browsing');
            $table->dateTime('date_approve');
            $table->tinyInteger('type')->comment('Phê duyệt giá, hồ sơ thanh toán, hợp đồng, công văn đến, công văn đi, bản vẽ, khác');
            $table->string('link');
            $table->tinyInteger('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('censor_contract_subcontractors');
    }
}
