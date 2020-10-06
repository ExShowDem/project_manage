<?php

namespace App\Models;

use BenSampo\Enum\Traits\CastsEnums;
use App\Enums\CommonStatus;
use App\Models\Traits\TaskableTrait;

class DeviceClearance extends BaseModel
{
    use CastsEnums, TaskableTrait;
    
    public $timestamps = true;
    
    protected $table = 'device_clearances';

    protected $fillable = [
        'code', 
        'name', 
        'project_id', 
        'date', 
        'creator_id', 
        'status', 
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

    public function devices()
    {
        return $this->belongsToMany(Devices::class, 'device_clearance_devices', 'device_clearance_id', 'devices_id')
            ->withPivot([
                'quantity',
                'existing_quantity',
                'unit_price',
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
