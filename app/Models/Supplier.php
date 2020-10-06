<?php

namespace App\Models;

class Supplier extends BaseModel
{
    protected $fillable = [
        'name',
        'project_id',
        'email',
        'phone_number',
        'address',
        'type',
        'tax_code',
        'branch',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
