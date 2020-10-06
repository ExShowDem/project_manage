<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Invoice;
use Faker\Generator as Faker;

$factory->define(Invoice::class, function (Faker $faker) {
    return [
        'supplier_id' => rand(1, 5),
        'contract_date' => \Carbon\Carbon::now(),
        'offer_buy_id' => rand(1, 10),
        'project_id' => 1,
        'contract_number' => \Illuminate\Support\Str::random(8),
        'status' => 1,
    ];
});
