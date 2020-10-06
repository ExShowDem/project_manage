<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CategorySupplies;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(CategorySupplies::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'code' => Str::random(8),
        'parent_id' => rand(1, 3),
        'project_id' => \App\Models\Project::inRandomOrder()->first()->id,
    ];
});
