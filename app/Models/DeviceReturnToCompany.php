<?php

namespace App\Models;

use BenSampo\Enum\Traits\CastsEnums;
use App\Enums\CommonStatus;
use App\Models\Traits\TaskableTrait;

class DeviceReturnToCompany extends BaseModel
{
    use CastsEnums, TaskableTrait;
    
    public $timestamps = true;
    
    protected $table = 'devices_return_to_company';

    protected $fillable = [
        'code', 
        'devices_to_project_id', 
        'project_id', 
        'company', 
        'user_id', 
        'return_date', 
        'status', 
    ];

    protected $enumCasts = [
        'status' => CommonStatus::class
    ];

    protected $appends = [
        'status_str'
    ];

    public function deviceToProject()
    {
        return $this->belongsTo(DeviceToProject::class, 'devices_to_project_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
        return $this->belongsToMany(Devices::class, 'device_company_devices', 'device_company_id', 'devices_id')
            ->withPivot([
                'quantity',
                'quantity_returned',
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
