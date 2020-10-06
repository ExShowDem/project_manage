<?php

use Illuminate\Database\Seeder;

class ResourceTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('resource_types')->truncate();
        DB::table('resource_types')->insert([
            ['name' => 'Nhân công'],
            ['name' => 'Vật liệu'],
            ['name' => 'Ca máy'],
            ['name' => 'Chi phí'],
        ]);
    }
}
