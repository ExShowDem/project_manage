<?php

namespace App\Models;

use App\Enums\CommonStatus;
use App\Models\Traits\DeleteRelationsTrait;
use App\Models\Traits\TaskableTrait;
use BenSampo\Enum\Traits\CastsEnums;

class Stocktaking extends BaseModel
{
    use CastsEnums, TaskableTrait, DeleteRelationsTrait;

    protected $table = 'stocktaking';

    protected $fillable = [
        'name',
        'code',
        'project_id',
        'created_date',
        'created_by',
        'status'
    ];

    protected $casts = [
        'created_date' => 'date:d/m/Y',
    ];

    protected $enumCasts = [
        'status' => CommonStatus::class,
    ];

    protected $with = [
        'creator',
    ];

    public function supplies()
    {
        return $this->belongsToMany(Supplies::class, 'stocktaking_supplies', 'stocktaking_id', 'supply_id')
            ->withPivot([
                'price',
                'quantity_system',
                'quantity_actual',
                'reason',
            ])
            ->withTimestamps();
    }

    public function files()
    {
        return $this->morphMany(AttachFile::class, 'fileable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function setCreatedDateAttribute($value)
    {
        if ($value instanceof \DateTime) {
            $this->attributes['created_date'] = $value;
        } else {
            $this->attributes['created_date'] = carbon_date($value);
        }
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
