<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketImportSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_import_supplies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ticket_import_id');
            $table->bigInteger('supplies_id');
            $table->bigInteger('quantity');
            $table->string('reason');
            $table->bigInteger('unit_price');
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
        Schema::dropIfExists('ticket_import_supplies');
    }
}
