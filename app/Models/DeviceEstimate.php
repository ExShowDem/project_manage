<?php

namespace App\Models;

use BenSampo\Enum\Traits\CastsEnums;
use App\Models\Traits\TaskableTrait;
use App\Enums\CommonStatus;

class DeviceEstimate extends BaseModel
{
    use CastsEnums, TaskableTrait;
    
    public $timestamps = true;
    
    protected $table = 'device_estimates';

    protected $fillable = [
        'code', 
        'name', 
        'project_id', 
        'creator_id', 
        'status', 
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

            return '';
        }
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function devices()
    {
        return $this->belongsToMany(Devices::class, 'device_estimate_devices', 'device_estimate_id', 'devices_id')
            ->withPivot([
				'mass',
				'mass1',
				'price',
				'rent',
				'rent_price',
				'mass2',
				'estimated_unit_price',
				'input_time',              
				'return_time',
				'days_used',
				'total_price',
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
