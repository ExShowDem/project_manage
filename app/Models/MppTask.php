<?php

namespace App\Models;

use App\Models\Traits\ActionLogTrait;

class MppTask extends BaseModel
{
    use ActionLogTrait;

    protected $table = 'mpp_tasks';

    protected $fillable = [
        'work_plan_id',
        'text',
        'duration',
        'progress',
        'start_date',
        'parent',
        'executor_id',
        'follower_ids',
    ];

    protected $casts = [
        'follower_ids' => 'array',
    ];

    protected $appends = [
        'open',
        'follower',
    ];

    public function getOpenAttribute()
    {
        return true;
    }

    public function workPlan()
    {
        return $this->belongsTo(WorkPlan::class, 'work_plan_id');
    }

    public function executor()
    {
        return $this->belongsTo(User::class, 'executor_id')->select('id', 'name');
    }

    public function getFollowerAttribute()
    {
        return User::select('id', 'name')->whereIn('id', is_null($this->follower_ids) ? [] : $this->follower_ids)->get();
    }

    public function files()
    {
        return $this->morphMany(AttachFile::class, 'fileable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function resources()
    {
        return $this->hasMany(MppTaskResource::class, 'mpp_task_id');
    }
}
