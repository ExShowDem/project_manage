<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\ReceiptTransfer::class, function (Faker $faker) {
    return [
        'output_id' => \App\Models\Project::inRandomOrder()->first()->id,
        'input_id' => \App\Models\Project::inRandomOrder()->first()->id,
        'date_transfer' => \Carbon\Carbon::now(),
        'code' => \Illuminate\Support\Str::random(8),
        'status' => 1,
        'created_by' => \App\Models\User::inRandomOrder()->first()->id,
    ];
});
