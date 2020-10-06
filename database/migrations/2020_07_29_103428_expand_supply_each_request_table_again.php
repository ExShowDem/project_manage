<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExpandSupplyEachRequestTableAgain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supply_each_request', function (Blueprint $table) {
            if (!Schema::hasColumn('supply_each_request', 'estimate_quantity'))
            {
                $table->double('estimate_quantity')->default(0);
            }

            if (!Schema::hasColumn('supply_each_request', 'approved_cum'))
            {
                $table->double('approved_cum')->default(0);
            }

            if (!Schema::hasColumn('supply_each_request', 'input_cumulative'))
            {
                $table->double('input_cumulative')->default(0);
            }

            if (!Schema::hasColumn('supply_each_request', 'remainder'))
            {
                $table->double('remainder')->default(0);
            }

            if (!Schema::hasColumn('supply_each_request', 'progress'))
            {
                $table->double('progress')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supply_each_request', function (Blueprint $table) {
            if (Schema::hasColumn('supply_each_request', 'estimate_quantity'))
            {
                $table->dropColumn('estimate_quantity');
            }

            if (Schema::hasColumn('supply_each_request', 'approved_cum'))
            {
                $table->dropColumn('approved_cum');
            }
            
            if (Schema::hasColumn('supply_each_request', 'input_cumulative'))
            {
                $table->dropColumn('input_cumulative');
            }
            
            if (Schema::hasColumn('supply_each_request', 'remainder'))
            {
                $table->dropColumn('remainder');
            }
            
            if (Schema::hasColumn('supply_each_request', 'progress'))
            {
                $table->dropColumn('progress');
            }
        });
    }

}
