<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParentIdToCategorySupplies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_supplies', function (Blueprint $table) {
            $table->bigInteger('parent_id')->after('id')->nullable();
            $table->bigInteger('project_id')->after('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_supplies', function (Blueprint $table) {
            $table->dropColumn('parent_id');
            $table->dropColumn('project_id');
        });
    }
}
