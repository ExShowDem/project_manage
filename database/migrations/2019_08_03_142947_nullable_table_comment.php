<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NullableTableComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('to_user');
            $table->dropColumn('commentable_id');
            $table->dropColumn('commentable_type');
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->bigInteger('commentable_id')->nullable();
            $table->string('commentable_type')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('commentable_id');
            $table->dropColumn('commentable_type');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->bigInteger('to_user');
            $table->bigInteger('commentable_id');
            $table->string('commentable_type');
        });
    }
}
