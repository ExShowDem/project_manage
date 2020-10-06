<?php

namespace App\Models;

use BenSampo\Enum\Traits\CastsEnums;
use App\Enums\CommonStatus;
use App\Models\Traits\TaskableTrait;

class Item extends BaseModel
{
    use CastsEnums, TaskableTrait;

    protected $fillable = [
        'name',
        'project_id',
        'code',
        'created_by',
        'end_date',
        'status', 
        'progress', 
    ];

    protected $enumCasts = [
        'status' => CommonStatus::class
    ];

    protected $appends = [
        'status_str'
    ];

    public function getStatusStrAttribute()
    {
        if ($this->status)
        {
            switch ($this->status->value) 
            {
                case CommonStatus::CREATING:
                    return 'Đang tạo';
                case CommonStatus::CREATED:
                    return 'Chờ duyệt';
                case CommonStatus::APPROVED:
                    return 'Đã duyệt';
            }
        }

        return '';
    }

    public function supplies()
    {
        return $this->belongsToMany(Supplies::class, 'item_supplies', 'item_id', 'supply_id')
            ->withPivot([
                'quantity',
                'unit_price_budget',
                'total',
                'type',
                'is_vlk',
                'progress', 
            ])
            ->withTimestamps();
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function files()
    {
        return $this->morphMany(AttachFile::class, 'fileable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
