<?php

namespace App\Models;

use BenSampo\Enum\Traits\CastsEnums;
use App\Enums\CommonStatus;
use App\Enums\BorrowerType as Type;
use App\Models\Traits\TaskableTrait;

class DeviceRental extends BaseModel
{
    use CastsEnums, TaskableTrait;
    
    public $timestamps = true;
    
    protected $table = 'device_rentals';

    protected $fillable = [
        'code', 
        'name', 
        'project_id', 
        'borrower_type', 
        'borrower_id', 
        'creator_id', 
        'status', 
    ];

    protected $enumCasts = [
        'status' => CommonStatus::class
    ];

    protected $appends = [
        'status_str',
        'borrower_name',
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

    public function borrowerType()
    {
        return $this->belongsTo(BorrowerType::class, 'borrower_type');
    }

    public function getBorrowerNameAttribute()
    {
        $borrowerId = $this->borrower_id;

        switch ($this->borrower_type) 
        {
            case Type::PROJECT:
                $borrowerName = Project::find($borrowerId)->name;
                break;
            case Type::USER:
                $borrowerName = User::find($borrowerId)->name;
                break;
            case Type::CUSTOMER:
                $borrowerName = Supplier::find($borrowerId)->name;
                break;
            case Type::SUPPLIER:
                $borrowerName = Supplier::find($borrowerId)->name;
                break;
        }

        return $borrowerName;
    }

    public function devices()
    {
        return $this->belongsToMany(Devices::class, 'device_rental_devices', 'device_rental_id', 'devices_id')
            ->withPivot([
                'quantity',
                'unit_price',
                'start_date',
                'end_date',
                'days_used',
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
