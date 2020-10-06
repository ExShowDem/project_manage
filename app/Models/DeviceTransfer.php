<?php

namespace App\Models;

use BenSampo\Enum\Traits\CastsEnums;
use App\Models\Traits\TaskableTrait;
use App\Enums\CommonStatus;

class DeviceTransfer extends BaseModel
{
    use CastsEnums, TaskableTrait;
    
    public $timestamps = true;
    
    protected $table = 'device_transfers';

    protected $fillable = [
        'device_issuance_id', 
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
        }

        return '';
    }

    public function issuance()
    {
        return $this->belongsTo(DeviceIssuance::class, 'device_issuance_id');
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
        return $this->belongsToMany(Devices::class, 'device_transfer_devices', 'device_transfer_id', 'devices_id')
            ->withPivot([
				'issued_quantity',
				'quantity',
				'carrier_type',
				'carrier_number',
				'transfer_unit',
				'from_project',
				'to_project',
				'sent',
				'arrived',
                'existing_quantity',
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
