<?php

namespace App\Models;

class ItemSupplierType extends BaseModel
{
    public $timestamps = true;
    
    protected $table = 'item_supplier_types';

    protected $fillable = [
        'name'
    ];
}
