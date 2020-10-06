<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Supplier::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'project_id' => \App\Models\Project::inRandomOrder()->first()->id,
        'type' => $faker->randomElement(\App\Enums\SupplierType::getValues()),
    ];
});
