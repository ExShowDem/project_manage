<?php

namespace App\Models;

class SupplyDetail extends BaseModel
{
    protected $table = 'supplies_details';

    protected $fillable = [
        'supplies_id',
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
