<?php

namespace App\Models;

use BenSampo\Enum\Traits\CastsEnums;
use App\Enums\CommonStatus;
use App\Models\Traits\TaskableTrait;

class DeviceInput extends BaseModel
{
    use CastsEnums, TaskableTrait;
    
    public $timestamps = true;
    
    protected $table = 'devices_input';

    protected $fillable = [
        'code', 
        'company', 
        'project_id', 
        'creator_id', 
        'created_date', 
        'status', 
        'reason', 
        'purchase_request_id',
    ];

    protected $enumCasts = [
        'status' => CommonStatus::class
    ];

    protected $appends = [
        'status_str'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
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
        return $this->belongsToMany(Devices::class, 'device_input_devices', 'device_input_id', 'devices_id')
            ->withPivot([
                'quantity',
                'existing_quantity',
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

    public function purchaseRequest()
    {
        return $this->belongsTo(DevicePurchaseRequest::class, 'purchase_request_id');
    }
}
