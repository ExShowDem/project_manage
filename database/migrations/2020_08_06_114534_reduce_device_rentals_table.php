<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReduceDeviceRentalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_rentals', function (Blueprint $table) {
            if (Schema::hasColumn('device_rentals', 'start_date'))
            {
                $table->dropColumn('start_date');
            }
            if (Schema::hasColumn('device_rentals', 'end_date'))
            {
                $table->dropColumn('end_date');
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
        Schema::table('device_rentals', function (Blueprint $table) {
            if (!Schema::hasColumn('device_rentals', 'start_date'))
            {
                $table->date('start_date');
            }
            if (!Schema::hasColumn('device_rentals', 'end_date'))
            {
                $table->date('end_date');
            }
        });
    }
}
