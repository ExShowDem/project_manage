<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NullableTablePlanSupplies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plan_supplies', function (Blueprint $table) {
            $table->dropColumn('quantity');
            $table->dropColumn('unit_price_budget');
            $table->dropColumn('date_arrival');
        });

        Schema::table('plan_supplies', function (Blueprint $table) {
            $table->bigInteger('quantity')->nullable();
            $table->bigInteger('unit_price_budget')->nullable();
            $table->date('date_arrival')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plan_supplies', function (Blueprint $table) {
            $table->dropColumn('quantity');
            $table->dropColumn('unit_price_budget');
            $table->dropColumn('date_arrival');
        });

        Schema::table('plan_supplies', function (Blueprint $table) {
            $table->bigInteger('quantity');
            $table->bigInteger('unit_price_budget');
            $table->date('date_arrival');
        });
    }
}
