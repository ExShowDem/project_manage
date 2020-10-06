<?php

namespace App\Models;

class DeviceDetail extends BaseModel
{
    protected $table = 'devices_details';

    protected $fillable = [
        'devices_id',
        'material_code',
        'size',
        'specification',
        'supplier',
        'color',
        'intensity',
        'density',
        'standard',
        'source',
    ];
}
