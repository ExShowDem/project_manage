<?php

namespace App\Models;

use App\Models\Traits\EloquentTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\ActionLogTrait;

class BaseModel extends Model
{
    use EloquentTrait, SoftDeletes;
    use ActionLogTrait;

    protected function getSerializeDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('api.date_format')) : null;
    }
}
