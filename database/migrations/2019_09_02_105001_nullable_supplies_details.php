<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NullableSuppliesDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplies_details', function (Blueprint $table) {
            $table->string('material_code')->nullable()->change();
            $table->string('size')->nullable()->change();
            $table->string('supplier')->nullable()->change();
            $table->string('color')->nullable()->change();
            $table->string('intensity')->nullable()->change();
            $table->string('density')->nullable()->change();
            $table->string('standard')->nullable()->change();
            $table->string('source')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplies_details', function (Blueprint $table) {
            $table->string('material_code')->nullable(false)->change();
            $table->string('size')->nullable(false)->change();
            $table->string('supplier')->nullable(false)->change();
            $table->string('color')->nullable(false)->change();
            $table->string('intensity')->nullable(false)->change();
            $table->string('density')->nullable(false)->change();
            $table->string('standard')->nullable(false)->change();
            $table->string('source')->nullable(false)->change();
        });
    }
}
