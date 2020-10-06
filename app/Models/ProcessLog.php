<?php

namespace App\Models;

class ProcessLog extends BaseModel
{
    protected $table = 'process_logs';

    protected $fillable = [
        'project_id',
        'process_user_id',
        'status',
        'table_id',
        'table_type',
        'name',
        'comment',
        'data_object',
    ];

    protected $casts = [
        'data_object' => 'json',
    ];

    public function processUser()
    {
        return $this->belongsTo(User::class);
    }
}
