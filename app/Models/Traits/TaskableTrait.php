<?php

namespace App\Models\Traits;

use App\Models\Task;

trait TaskableTrait
{
    public function tasks()
    {
        return $this->morphMany(Task::class, 'taskable');
    }
}
