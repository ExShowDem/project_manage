<?php

namespace App\Models;

use BenSampo\Enum\Traits\CastsEnums;
use App\Enums\CommonStatus;
use App\Models\Traits\TaskableTrait;

class DeviceRoundRobin extends BaseModel
{
    use CastsEnums, TaskableTrait;
    
    public $timestamps = true;
    
    protected $table = 'device_round_robins';

    protected $fillable = [
        'name', 
        'code', 
        'from_project_id', 
        'to_project_id', 
        'creator_id', 
        'created_date', 
        'status', 
    ];

    protected $enumCasts = [
        'status' => CommonStatus::class
    ];

    protected $appends = [
        'status_str'
    ];

    public function fromProject()
    {
        return $this->belongsTo(Project::class, 'from_project_id');
    }

    public function toProject()
    {
        return $this->belongsTo(Project::class, 'to_project_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

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

    public function devices()
    {
        return $this->belongsToMany(Devices::class, 'device_round_robin_devices', 'device_round_robin_id', 'devices_id')
            ->withPivot([
                'existing_quantity',
                'quantity',
                'unit_price',
                'note',
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
}
