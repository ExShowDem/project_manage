<?php

use App\Models\Supplies;
use Illuminate\Database\Seeder;

class SuppliesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Supplies::truncate();

        // parent
//        factory(Supplies::class, 10)->create();
//        factory(Supplies::class, 2000)->create();
    }
}
