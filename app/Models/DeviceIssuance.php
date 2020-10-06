<?php

namespace App\Models;

use BenSampo\Enum\Traits\CastsEnums;
use App\Models\Traits\TaskableTrait;
use App\Enums\CommonStatus;

class DeviceIssuance extends BaseModel
{
    use CastsEnums, TaskableTrait;
    
    public $timestamps = true;
    
    protected $table = 'device_issuances';

    protected $fillable = [
        'name', 
        'code', 
        'intention', 
        'monthly_estimates_id', 
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

    public function monthlyEstimates()
    {
        return $this->belongsTo(DeviceMonthlyEstimate::class, 'monthly_estimates_id');
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
        return $this->belongsToMany(Devices::class, 'device_issuance_devices', 'device_issuance_id', 'devices_id')
            ->withPivot([
				'monthly_estimated_quantity',
				'quantity',
				'supply_date',
				'return_date',
				'supply_date1',
                'quantity1',
                'has_surpassed_estimates',
                'total_quantity',
                'accumulated_quantity',
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
