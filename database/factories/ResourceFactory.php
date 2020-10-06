<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Resource::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'resource_type_id' => \App\Models\ResourceType::inRandomOrder()->first()->id,
        'unit_id' => \App\Models\Unit::inRandomOrder()->first()->id,
        'unit_price' => rand(1000, 9999),
        'project_id' => \App\Models\Project::inRandomOrder()->first()->id,
    ];
});
