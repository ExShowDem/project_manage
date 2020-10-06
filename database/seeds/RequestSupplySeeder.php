<?php

use Illuminate\Database\Seeder;

class RequestSupplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\RequestSupply::class, 10)->create();
    }
}
