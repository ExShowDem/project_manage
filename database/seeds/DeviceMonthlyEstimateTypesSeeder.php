<?php

use Illuminate\Database\Seeder;

class DeviceMonthlyEstimateTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('device_monthly_estimate_types')->insert([
            ['id' => 1, 'name' => 'Cấp'],
            ['id' => 2, 'name' => 'Trả'],
        ]);
    }
}
