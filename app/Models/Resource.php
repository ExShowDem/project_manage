<?php

namespace App\Models;

class Resource extends BaseModel
{
    protected $fillable = [
        'name',
        'resource_type_id',
        'unit_id',
        'unit_price',
        'project_id',
    ];

    protected $with = [
        'unit',
        'resourceType'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function resourceType()
    {
        return $this->belongsTo(ResourceType::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
