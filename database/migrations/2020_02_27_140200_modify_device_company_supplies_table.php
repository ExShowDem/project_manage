<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyDeviceCompanySuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('device_company_supplies')) 
        {
            DB::statement("ALTER TABLE device_company_supplies MODIFY COLUMN quantity_returned BIGINT(20) AFTER updated_at");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('device_company_supplies')) 
        {
            DB::statement("ALTER TABLE device_company_supplies MODIFY COLUMN quantity_returned BIGINT(20) AFTER quantity");
        }
    }
}
