<?php

namespace App\Models;

class DeviceTransferDirection extends BaseModel
{
    public $timestamps = true;
    
    protected $table = 'device_transfer_directions';

    protected $fillable = [
        'name'
    ];
}
