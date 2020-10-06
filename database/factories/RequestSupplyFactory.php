<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\RequestSupply::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'code' => \Illuminate\Support\Str::random(8),
        'to_user' => \App\Models\User::inRandomOrder()->first()->id,
        'created_by' => \App\Models\User::inRandomOrder()->first()->id,
        'created_date' => \Carbon\Carbon::now(),
        'item_id' => \App\Models\Item::inRandomOrder()->first()->id,
        'status' => 1,
        'project_id' => \App\Models\Project::inRandomOrder()->first()->id,
    ];
});
