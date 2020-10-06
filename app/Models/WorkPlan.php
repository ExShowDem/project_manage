<?php

namespace App\Models;

class WorkPlan extends BaseModel
{
    protected $table = 'work_plans';

    protected $fillable = [
        'name',
        'is_limit_user',
        'user_ids',
        'created_by',
        'project_id',
    ];

    protected $casts = [
        'user_ids' => 'array'
    ];
}
