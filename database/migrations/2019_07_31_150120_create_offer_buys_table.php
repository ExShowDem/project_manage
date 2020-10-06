<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfferBuysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_buys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->date('date_offer');
            $table->string('ticket_number');
            $table->bigInteger('project_id');
            $table->bigInteger('supplier_id');
            $table->bigInteger('created_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_buys');
    }
}
