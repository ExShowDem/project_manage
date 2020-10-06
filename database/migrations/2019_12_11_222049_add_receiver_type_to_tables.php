<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReceiverTypeToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplies_requests', function (Blueprint $table) {
            $table->tinyInteger('receiver_type')->after('code')->nullable();
        });

        Schema::table('receipt_outputs', function (Blueprint $table) {
            $table->tinyInteger('receiver_type')->after('output_id')->nullable();
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
            $table->dropColumn('receiver_type');
        });

        Schema::table('receipt_outputs', function (Blueprint $table) {
            $table->dropColumn('receiver_type');
        });
    }
}
