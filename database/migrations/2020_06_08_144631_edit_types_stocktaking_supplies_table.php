<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTypesStocktakingSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stocktaking_supplies', function (Blueprint $table) {

            $table->decimal('price')->change();
            $table->decimal('quantity_system')->change();
            $table->decimal('quantity_actual')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stocktaking_supplies', function (Blueprint $table) {
            
            $table->integer('price')->change();
            $table->integer('quantity_system')->change();
            $table->integer('quantity_actual')->change();
        });
    }
}
