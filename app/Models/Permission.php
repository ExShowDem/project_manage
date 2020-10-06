<?php

namespace App\Models;

use App\Models\Traits\EloquentTrait;
use Spatie\Permission\Models\Permission as OriginalPermission;
use App\Models\Traits\ActionLogTrait;
use App\Models\Project;

class Permission extends OriginalPermission
{
    use EloquentTrait;
    use ActionLogTrait;

    public $guard_name = 'api';

    public function includeProjects(&$permissions)
    {
        $projectIndex = array_search("Dự án", array_keys($permissions));
        $projectsLabel = '- Những dự án';

        $permissions = array_slice($permissions, 0, $projectIndex+1, true) +
            array($projectsLabel => []) +
            array_slice($permissions, $projectIndex+1, count($permissions)-1, true);

        foreach (Project::all()->toArray() as $project) 
        {
            $permissions[$projectsLabel][$project['name']] = ['project_' . $project['id'] . '.read'];
        }
    }
}
