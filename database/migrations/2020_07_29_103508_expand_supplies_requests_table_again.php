<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExpandSuppliesRequestsTableAgain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplies_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('supplies_requests', 'progress'))
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
        Schema::table('supplies_requests', function (Blueprint $table) {
            if (Schema::hasColumn('supplies_requests', 'progress'))
            {
                $table->dropColumn('progress');
            }
        });
    }
}
