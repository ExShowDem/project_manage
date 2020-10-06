<?php
namespace App\Models;

class InventoryLog extends BaseModel
{
    protected $table = 'inventories_log';

    protected $fillable = [
        'project_id',
        'supply_id',
        'quantity',
    ];
}
