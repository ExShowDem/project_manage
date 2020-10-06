<?php

use App\Models\Project;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(\App\Models\UserProject::class, function (Faker $faker) {
    static $userIds, $projectIds;

    if (!$userIds) {
        $userIds = User::pluck('id');
    }

    if (!$projectIds) {
        $projectIds = Project::pluck('id');
    }

    return [
        'user_id' => $userIds->random(),
        'project_id' => $projectIds->random(),
    ];
});
