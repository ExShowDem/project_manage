<?php

namespace App\Models;

use App\Enums\CommonStatus;
use App\Models\Traits\DeleteRelationsTrait;
use App\Models\Traits\TaskableTrait;
use BenSampo\Enum\Traits\CastsEnums;

class Plan extends BaseModel
{
    use CastsEnums, TaskableTrait, DeleteRelationsTrait;

    protected $fillable = [
        'name',
        'code',
        'status',
        'start_time',
        'end_time',
        'project_id',
        'created_by',
    ];

    protected $casts = [
        'start_time' => 'date:d/m/Y',
        'end_time' => 'date:d/m/Y',
    ];

    protected $enumCasts = [
        'status' => CommonStatus::class
    ];

    protected $appends = [
        'status_str'
    ];

    public function supplies()
    {
        return $this->belongsToMany(Supplies::class, 'plan_supplies', 'plan_id', 'supplies_id')
            ->withPivot([
                'quantity',
                'unit_price_budget',
                'date_arrival',
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function setStartTimeAttribute($value)
    {
        if ($value instanceof \DateTime) {
            $this->attributes['start_time'] = $value;
        } else {
            $this->attributes['start_time'] = carbon_date($value);
        }
    }

    public function setEndTimeAttribute($value)
    {
        if ($value instanceof \DateTime) {
            $this->attributes['end_time'] = $value;
        } else {
            $this->attributes['end_time'] = carbon_date($value);
        }
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function getStatusStrAttribute()
    {
        switch ($this->status->value) {
            case CommonStatus::CREATING:
                return 'Đang tạo';
            case CommonStatus::CREATED:
                return 'Chờ duyệt';
            case CommonStatus::APPROVED:
                return 'Đã duyệt';
        }

        return '';
    }
}
