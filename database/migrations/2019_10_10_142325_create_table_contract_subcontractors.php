<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableContractSubcontractors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_subcontractors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('subcontractor_id');
            $table->bigInteger('project_id');
            $table->bigInteger('created_by');
            $table->dateTime('contract_sign_date');
            $table->integer('process')->comment('số ngày hoàn phải hoàn thành hợp đồng');
            $table->string('contract_form')->comment('Hình thức hợp đồng');
            $table->string('contract_number')->unique();
            $table->double('contract_annex_value');
            $table->double('contract_value')->comment('not vat');
            $table->double('contract_value_vat')->comment('include vat');
            $table->double('value_custody_warranty')->comment('Giá Trị tạm giữ bảo hành');
            $table->text('content');
            $table->dateTime('date_warranty');
            $table->tinyInteger('status')->comment('Tạo mới,  đang tiến hành, hoàn thành');
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
        Schema::dropIfExists('contract_subcontractors');
    }
}
