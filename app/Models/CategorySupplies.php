<?php

namespace App\Models;

class CategorySupplies extends BaseModel
{
    protected $fillable = [
        'name',
        'code',
        'project_id',
        'parent_id',
    ];

    public function parent()
    {
        return $this->hasOne(CategorySupplies::class, 'id', 'parent_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
