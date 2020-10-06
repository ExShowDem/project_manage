<?php

namespace App\Models;

use BenSampo\Enum\Traits\CastsEnums;
use App\Models\Traits\TaskableTrait;
use App\Enums\CommonStatus;

class DeviceMonthlyEstimate extends BaseModel
{
    use CastsEnums, TaskableTrait;
    
    public $timestamps = true;
    
    protected $table = 'device_monthly_estimates';

    protected $fillable = [
        'name', 
        'code', 
        'intention', 
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
        }

        return '';
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
        $types= DeviceMonthlyEstimateType::all()->pluck('name', 'id');

        return $this->belongsToMany(Devices::class, 'device_monthly_estimate_devices', 'device_monthly_estimate_id', 'devices_id')
            ->withPivot([
				'type',
                'total_quantity',
                'accumulated_quantity',
				'quantity',
				'batch1',
				'batch2',
				'batch3',
				'batch4',
				'batch5',
				'batch6',
                'quantity1',
                'quantity2',
                'quantity3',
                'quantity4',
                'quantity5',
                'quantity6',
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
