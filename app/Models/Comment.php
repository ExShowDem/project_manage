<?php

namespace App\Models;

use App\Models\Traits\ActionLogTrait;

class Comment extends BaseModel
{
    use ActionLogTrait;
    
    protected $fillable = [
        'from_user',
        'content',
        'commentable_id',
        'commentable_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'from_user');
    }
}
