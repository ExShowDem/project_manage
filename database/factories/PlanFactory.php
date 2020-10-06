<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Plan;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Plan::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'code' => Str::random(8),
        'status' => 1,
        'start_time' => $faker->dateTimeBetween('-1 month'),
        'end_time' => $faker->dateTimeBetween('now', '+1 month'),
        'project_id' => \App\Models\Project::inRandomOrder()->first()->id,
        'created_by' => \App\Models\User::inRandomOrder()->first()->id,
    ];
});
