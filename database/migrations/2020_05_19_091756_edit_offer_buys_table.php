<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditOfferBuysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offer_buys', function (Blueprint $table) {
            if (!Schema::hasColumn('offer_buys', 'request_id'))
            {
                $table->bigInteger('request_id')->nullable();
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
        Schema::table('offer_buys', function (Blueprint $table) {
            if (Schema::hasColumn('offer_buys', 'request_id'))
            {
                $table->dropColumn('request_id');
            }
        });
    }
}
