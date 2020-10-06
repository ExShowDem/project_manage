<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditProcessLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('process_logs', function (Blueprint $table) {
            if (Schema::hasColumn('process_logs', 'data_object'))
            {
                $table->longText('data_object')->change();
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
        Schema::table('process_logs', function (Blueprint $table) {
            if (Schema::hasColumn('process_logs', 'data_object'))
            {
                $table->text('data_object')->change();
            }
        });
    }
}
