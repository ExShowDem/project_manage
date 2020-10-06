<?php

namespace App\Models;

class Subcontractor extends BaseModel
{
    protected $fillable = [
        'name',
        'type',
        'code',
        'tax_code',
        'representative',
        'bank',
        'account_name',
        'account_owner',
    ];
}
