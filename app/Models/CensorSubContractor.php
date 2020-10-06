<?php

namespace App\Models;

class CensorSubContractor extends BaseModel
{
    protected $table = 'censor_contract_subcontractors';

    protected $fillable = [
        'package',
        'project_id',
        'date_browsing',
        'date_approve',
        'type',
        'link',
        'status',
    ];

    public function files()
    {
        return $this->morphMany(AttachFile::class, 'fileable');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function subcontractors()
    {
        return $this->belongsToMany(
            Subcontractor::class,
            'censor_subcontractor',
            'censor_id',
            'subcontractor_id');
    }
}
