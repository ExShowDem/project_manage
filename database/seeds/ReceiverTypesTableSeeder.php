<?php

use Illuminate\Database\Seeder;

class ReceiverTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('receiver_types')->insert([
            ['id' => 1, 'name' => 'User'],
            ['id' => 2, 'name' => 'Nhà cung cấp'],
            ['id' => 3, 'name' => 'Kho'],
        ]);
    }
}
