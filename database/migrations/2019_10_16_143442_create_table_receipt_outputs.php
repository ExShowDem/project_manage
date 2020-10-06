<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableReceiptOutputs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_outputs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('request_supply_id')->nullable();
            $table->bigInteger('invoice_id')->nullable();
            $table->bigInteger('output_id');
            $table->bigInteger('input_id');
            $table->date('date_output');
            $table->string('code');
            $table->string('reason')->nullable();
            $table->tinyInteger('status');
            $table->bigInteger('created_by');
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
        Schema::dropIfExists('receipt_outputs');
    }
}
