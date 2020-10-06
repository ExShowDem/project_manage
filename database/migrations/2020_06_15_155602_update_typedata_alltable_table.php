<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTypedataAlltableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_subcontractors', function (Blueprint $table) {
            if (Schema::hasColumn('contract_subcontractors', 'contract_value'))
            {
                $table->bigInteger('contract_value')->charset(null)->change();

            }
            if (Schema::hasColumn('contract_subcontractors', 'contract_value_vat'))
            {
                $table->bigInteger('contract_value_vat')->charset(null)->change();

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
        //
    }
}
