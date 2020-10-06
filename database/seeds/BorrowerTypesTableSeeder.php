<?php

use Illuminate\Database\Seeder;

class BorrowerTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('borrower_types')->insert([
            ['id' => 1, 'name' => 'Dự án'],
            ['id' => 2, 'name' => 'User'],
            ['id' => 3, 'name' => 'Khách hàng'],
            ['id' => 4, 'name' => 'Nhà cung cấp'],
        ]);
    }
}
