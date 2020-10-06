<?php

namespace App\Models;

use App\Models\Traits\ActionLogTrait;

class Notification extends BaseModel
{
    use ActionLogTrait;
    
    protected $table = 'notifications';

    protected $fillable = [
        'project_id',
        'from_user',
        'to_user',
        'content',
        'url',
        'targetable_id',
        'targetable_type',
        'is_read'
    ];

    public function targetable()
    {
        return $this->morphTo();
    }
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user');
    }
}
