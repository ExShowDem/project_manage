<?php

use App\Enums\SupplierType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->tinyInteger('type')->default(SupplierType::SUPPLIER);
            $table->string('tax_code')->nullable();
            $table->dropColumn('email');
            $table->dropColumn('phone_number');
            $table->dropColumn('address');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('tax_code');
        });
    }
}
