<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\OfferBuy::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'date_offer' => \Carbon\Carbon::now(),
        'ticket_number' => Str::random(8),
        'project_id' => \App\Models\Project::inRandomOrder()->first()->id,
        'supplier_id' => rand(1, 5),
        'created_by' => \App\Models\User::inRandomOrder()->first()->id,
        'status' => 1,
    ];
});
