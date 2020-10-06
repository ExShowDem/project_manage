<?php

namespace App\Models;

class Project extends BaseModel
{
    protected $fillable = [
        'created_by',
        'name',
        'code',
        'description',
        'featured_image',
        'file_in_month',
        'number_file',
    ];

    protected $dates = ['file_in_month'];

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'user_project',
            'project_id',
            'user_id');
    }

    public function supplies()
    {
        return $this->belongsToMany(Supplies::class, 'inventories', 'project_id', 'supply_id')
            ->withPivot([
                'quantity',
            ])
            ->withTimestamps();
    }
}
