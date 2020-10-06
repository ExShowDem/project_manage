<?php

namespace App\Models;

use App\Enums\CommonStatus;
use App\Models\Traits\DeleteRelationsTrait;
use App\Models\Traits\TaskableTrait;
use BenSampo\Enum\Traits\CastsEnums;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiptTransfer extends BaseModel
{
    use SoftDeletes, CastsEnums, TaskableTrait, DeleteRelationsTrait;

    protected $fillable = [
        'output_id',
        'input_id',
        'date_transfer',
        'code',
        'reason',
        'status',
        'created_by',
    ];

    protected $casts = [
        'date_transfer' => 'date:d/m/Y',
    ];

    protected $enumCasts = [
        'status' => CommonStatus::class,
    ];

    protected $with = [
        'creator',
    ];

    public function supplies()
    {
        return $this->belongsToMany(Supplies::class, 'receipt_transfer_supplies', 'input_id', 'supplies_id')
            ->withPivot([
                'quantity',
                'unit_price',
                'quantity_input',
                'quantity_output',
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

    public function setDateTransferAttribute($value)
    {
        if ($value instanceof \DateTime) {
            $this->attributes['date_transfer'] = $value;
        } else {
            $this->attributes['date_transfer'] = carbon_date($value);
        }
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'output_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Project::class, 'input_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
