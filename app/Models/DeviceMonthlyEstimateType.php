<?php

namespace App\Models;

class DeviceMonthlyEstimateType extends BaseModel
{
    public $timestamps = true;
    
    protected $table = 'device_monthly_estimate_types';

    protected $fillable = [
        'name'
    ];
}
