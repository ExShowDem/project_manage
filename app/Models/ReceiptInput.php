<?php

namespace App\Models;

use App\Enums\CommonStatus;
use App\Models\Traits\DeleteRelationsTrait;
use App\Models\Traits\TaskableTrait;
use BenSampo\Enum\Traits\CastsEnums;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiptInput extends BaseModel
{
    use SoftDeletes, CastsEnums, TaskableTrait, DeleteRelationsTrait;

    protected $fillable = [
        'request_id',
        'output_id',
        'input_id',
        'date_input',
        'code',
        'reason',
        'status',
        'created_by',
    ];

    protected $casts = [
        'date_input' => 'date:d/m/Y',
    ];

    protected $enumCasts = [
        'status' => CommonStatus::class,
    ];

    protected $with = [
        'creator',
    ];

    public function supplies()
    {
        return $this->belongsToMany(Supplies::class, 'receipt_input_supplies', 'input_id', 'supplies_id')
            ->withPivot([
                'original_quantity',
                'quantity',
                'unit_price',
                'difference_reason',
                'cumulative',
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

    public function setDateInputAttribute($value)
    {
        if ($value instanceof \DateTime) {
            $this->attributes['date_input'] = $value;
        } else {
            $this->attributes['date_input'] = carbon_date($value);
        }
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'input_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'output_id');
    }

    public function request()
    {
        return $this->belongsTo(RequestSupply::class, 'request_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getAccumulate($supplyId, $requestSupplyId)
    {
        return self::join('receipt_input_supplies', 'receipt_input_supplies.input_id', '=', 'receipt_inputs.id')
            ->where('status', CommonStatus::APPROVED)
            ->where('supplies_id', $supplyId)
            ->where('request_id', $requestSupplyId)
            ->sum('quantity');
    }
}
