<?php

use Illuminate\Database\Seeder;

class DeviceTransferDirectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('device_transfer_directions')->insert([
            ['id' => 1, 'name' => 'Nhập'],
            ['id' => 2, 'name' => 'Xuất'],
            ['id' => 3, 'name' => 'Chuyển'],
        ]);
    }
}
