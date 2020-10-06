<?php

namespace App\Models;

class MppLink extends BaseModel
{
    protected $table = 'mpp_links';

    protected $fillable = [
        'work_plan_id',
        'type',
        'source',
        'target',
    ];
}
