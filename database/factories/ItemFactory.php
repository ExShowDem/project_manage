<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Item;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'project_id' => \App\Models\Project::inRandomOrder()->first()->id,
        'code' => Str::random(8),
        'created_by' => \App\Models\User::inRandomOrder()->first()->id,
    ];
});
