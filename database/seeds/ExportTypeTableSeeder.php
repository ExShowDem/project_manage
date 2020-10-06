<?php

use Illuminate\Database\Seeder;

class ExportTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('export_types')->insert([
            ['id' => 1, 'name' => 'Yêu cầu vật tư dự án'],
            ['id' => 2, 'name' => 'Đề xuất mua vật tư'],
        ]);
    }
}
