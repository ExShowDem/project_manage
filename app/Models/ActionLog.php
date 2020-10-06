<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionLog extends Model
{
    public $timestamps  = false;
    protected $table    = 'action_log';
    protected $fillable = [
        'user_id',
        'user_name',
        'model',
        'table_name',
        'method',
        'record_key_name',
        'record_id',
        'params',
    ];
}