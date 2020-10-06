<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReduceCensorContractSubcontractorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('censor_contract_subcontractors', function (Blueprint $table) {
            if (Schema::hasColumn('censor_contract_subcontractors', 'subcontractor_id'))
            {
                $table->dropColumn('subcontractor_id');
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
        Schema::table('censor_contract_subcontractors', function (Blueprint $table) {
            if (!Schema::hasColumn('censor_contract_subcontractors', 'subcontractor_id'))
            {
                $table->bigInteger('subcontractor_id')->nullable();
            }
        });
    }
}
