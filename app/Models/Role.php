<?php

namespace App\Models;

use App\Models\Traits\EloquentTrait;
use Spatie\Permission\Models\Role as OriginalRole;
use App\Models\Traits\ActionLogTrait;
use App\Models\RoleTree;

class Role extends OriginalRole
{
    use EloquentTrait;
    use ActionLogTrait;

    public $guard_name = 'api';

    protected $appends = [
        'permissions'
    ];

    public function getPermissionsAttribute()
    {
        return $this->permissions()->pluck('name');
    }

    public function roleTree()
    {
        return $this->belongsTo(
            RoleTree::class,
            'id',
            'role_id'
        );
    }
}
