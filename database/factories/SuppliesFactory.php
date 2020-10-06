<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Supplies;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Supplies::class, function (Faker $faker) {
    $tmpSup = Supplies::inRandomOrder()->whereNull('parent_id')->first();
    return [
        'name' => $faker->name,
        'parent_id' => $tmpSup ? $tmpSup->id : null,
        'unit_id' => \App\Models\Unit::inRandomOrder()->first()->id,
        'category_supplies_id' => \App\Models\CategorySupplies::inRandomOrder()->first()->id,
        'code' => Str::random(8),
        'project_id' => \App\Models\Project::inRandomOrder()->first()->id,
        'description' => $faker->text,
    ];
});
