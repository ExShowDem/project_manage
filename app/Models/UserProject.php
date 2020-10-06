<?php

namespace App\Models;

class UserProject extends BaseModel
{
    protected $table = 'user_project';

    protected $fillable = [
        'user_id',
        'project_id',
    ];

    protected $dates = ['deleted_at'];
}
