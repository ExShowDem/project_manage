<?php

namespace App\Models;

use BenSampo\Enum\Traits\CastsEnums;
use App\Models\Traits\TaskableTrait;
use App\Enums\CommonStatus;

class DeviceContract extends BaseModel
{
    use CastsEnums, TaskableTrait;
    
    public $timestamps = true;
    
    protected $table = 'device_contracts';

    protected $fillable = [
        'code', 
        'purchase_id', 
        'place_id', 
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
        return $this->belongsToMany(Devices::class, 'device_contract_devices', 'device_contract_id', 'devices_id')
            ->withPivot([
				'needed_quantity',
				'quantity',
				'unit_price',
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

    public function purchase()
    {
        return $this->belongsTo(DevicePurchase::class, 'purchase_id');
    }

    public function place()
    {
        return $this->belongsTo(Supplier::class, 'place_id');
    }
}
