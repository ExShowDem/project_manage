<?php

use App\Models\CategorySupplies;
use Illuminate\Database\Seeder;

class CategorySuppliesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CategorySupplies::truncate();

        // factory(\App\Models\CategorySupplies::class, 20)->create();
    }
}
