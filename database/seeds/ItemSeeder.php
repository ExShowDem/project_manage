<?php

use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Item::class, 10)->create();

        $items = \App\Models\Item::all();

        foreach ($items as $item) {
            $supplies = \App\Models\Supplies::inRandomOrder()->limit(3)->get();

            $attachData = $supplies->keyBy('id')->map(function ($supply) {
                return [
                    'quantity' => random_int(3, 20),
                    'unit_price_budget' => random_int(10000, 1000000)
                ];
            })->toArray();
            $item->supplies()->attach($attachData);
        }
    }
}
