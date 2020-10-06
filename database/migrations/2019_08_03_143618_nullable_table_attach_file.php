<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NullableTableAttachFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attach_files', function (Blueprint $table) {
            $table->dropColumn('fileable_type');
            $table->dropColumn('fileable_id');
        });

        Schema::table('attach_files', function (Blueprint $table) {
            $table->string('fileable_type')->nullable();
            $table->bigInteger('fileable_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attach_files', function (Blueprint $table) {
            $table->dropColumn('fileable_type');
            $table->dropColumn('fileable_id');
        });

        Schema::table('attach_files', function (Blueprint $table) {
            $table->string('fileable_type');
            $table->bigInteger('fileable_id');
        });
    }
}
