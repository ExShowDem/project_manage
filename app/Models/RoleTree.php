<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleTree extends Model
{
    public $timestamps = false;
    
    protected $table = 'role_tree';

    protected $fillable = [
        'role_id',
        'parent_id',
    ];
}
