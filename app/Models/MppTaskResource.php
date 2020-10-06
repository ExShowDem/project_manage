<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MppTaskResource extends Model
{
    protected $table = 'mpp_task_resources';

    protected $fillable = [
        'mpp_task_id',
        'resource_type_id',
        'resource_id',
        'tracking_date',
        'quantity',
        'unit_price',
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resource_id');
    }
}
