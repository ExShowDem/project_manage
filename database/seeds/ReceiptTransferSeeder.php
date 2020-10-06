<?php

use Illuminate\Database\Seeder;

class ReceiptTransferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\ReceiptTransfer::class, 10)->create();
    }
}
