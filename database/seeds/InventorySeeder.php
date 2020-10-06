<?php

use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Inventory::query()->truncate();
        \App\Models\InventoryLog::query()->truncate();
        $supplies = \App\Models\Supplies::all();

        foreach ($supplies as $supply) {
            $inventory = \App\Models\Inventory::create([
                'project_id' => 1,
                'supply_id' => $supply->id,
                'quantity' => 5,
            ]);

            $inventory->logs()->create([
                'quantity' => 5,
            ]);
        }
    }
}
