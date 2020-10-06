<?php

use Illuminate\Database\Seeder;

class ReceiptOutputSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\ReceiptOutput::class, 10)->create();
    }
}
