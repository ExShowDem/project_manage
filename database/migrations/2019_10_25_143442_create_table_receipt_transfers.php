<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableReceiptTransfers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_transfers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('output_id');
            $table->bigInteger('input_id');
            $table->date('date_transfer');
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
        Schema::dropIfExists('receipt_transfers');
    }
}
