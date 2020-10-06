<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\ReceiptInput::class, function (Faker $faker) {
    return [
        'request_supply_id' => \App\Models\RequestSupply::inRandomOrder()->first()->id,
        'invoice_id' => \App\Models\Invoice::inRandomOrder()->first()->id,
        'output_id' => \App\Models\Supplier::inRandomOrder()->first()->id,
        'input_id' => \App\Models\Project::inRandomOrder()->first()->id,
        'date_input' => \Carbon\Carbon::now(),
        'code' => \Illuminate\Support\Str::random(8),
        'status' => 1,
        'created_by' => \App\Models\User::inRandomOrder()->first()->id,
    ];
});
