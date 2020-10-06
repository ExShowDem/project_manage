<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemSuppliesTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_supplies', function (Blueprint $table) {
            if (!Schema::hasColumn('item_supplies', 'type'))
            {
                $table->tinyInteger('type')->nullable();
            }
        });
        
        if (!Schema::hasTable('item_supplier_types'))
        {
            Schema::create('item_supplier_types', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
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
        //
    }
}
