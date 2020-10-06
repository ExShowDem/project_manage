<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Inventory::class, function (Faker $faker) {
    return [
        'supply_id' => \App\Models\Supplies::inRandomOrder()->first()->id,
        'project_id' => \App\Models\Project::inRandomOrder()->first()->id,
        'quantity' => rand(1, 10),
    ];
});
