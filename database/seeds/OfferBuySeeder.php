<?php

use Illuminate\Database\Seeder;

class OfferBuySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\OfferBuy::class, 10)->create();
    }
}
