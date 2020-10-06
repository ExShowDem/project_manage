<?php

use App\Models\Project;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'created_by' => 1,
        'name' => $faker->name,
        'code' => Str::random(8),
        'description' => $faker->text(200),
        'featured_image' => $faker->imageUrl(100, 100),
    ];
});
