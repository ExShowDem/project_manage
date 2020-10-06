<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Models\BaseModel;
use App\Models\Project;
use App\Models\User;
use BenSampo\Enum\Traits\CastsEnums;

class Task extends BaseModel
{
    use CastsEnums;

    protected $enumCasts = [
        'status' => TaskStatus::class
    ];

    protected $fillable = [
        'name',
        'code',
        'process_date',
        'project_id',
        'from_user',
        'to_user',
        'assignee_type',
        'created_date',
        'comment',
        'due_date',
        'status',
        'taskable_id',
        'taskable_type'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'from_user');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'to_user');
    }

    public function taskable()
    {
        return $this->morphTo();
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function scopeGetRelatedTask($query, $task)
    {
        $query->where('taskable_type', $task->taskable_type)
            ->where('taskable_id', $task->taskable_id)
            ->where('id', '!=', $task->id);
    }
}
