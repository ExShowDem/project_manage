<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateRoleTreeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_tree', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('parent_id');
        });

        $roleIds = DB::table('roles')
            ->pluck('id')
            ->toArray();

        $defaultTree = [];

        foreach ($roleIds as $roleId) 
        {
            $defaultTree[] = ['role_id' => $roleId, 'parent_id' => 0];
        }

        DB::table('role_tree')->insert($defaultTree);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_tree');
    }
}
