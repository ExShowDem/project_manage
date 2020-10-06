<?php

namespace App\Models;

use App\Models\Traits\EloquentTrait;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
	use EloquentTrait;
	
    protected $fillable = [
        'name'
    ];
}
