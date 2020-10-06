<?php

namespace App\Models;

use App\Enums\CommonStatus;
use App\Models\Traits\DeleteRelationsTrait;
use App\Models\Traits\TaskableTrait;
use BenSampo\Enum\Traits\CastsEnums;

class Invoice extends BaseModel
{
    use CastsEnums, TaskableTrait, DeleteRelationsTrait;

    protected $fillable = [
        'code',
        'supplier_id',
        'contract_date',
        'request_id',
        'project_id',
        'contract_number',
        'status',
        'created_by',
    ];

    protected $casts = [
        'contract_date' => 'date:d/m/Y',
    ];

    protected $enumCasts = [
        'status' => CommonStatus::class,
    ];

    protected $with = [
        'creator',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function supplies()
    {
        return $this->belongsToMany(Supplies::class, 'invoice_supplies', 'invoice_id', 'supplies_id')
            ->withPivot([
                'quantity',
                'unit_price',
                'discount',
                'other_cost',
                'tax',
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

    public function setContractDateAttribute($value)
    {
        if ($value instanceof \DateTime) {
            $this->attributes['contract_date'] = $value;
        } else {
            $this->attributes['contract_date'] = carbon_date($value);
        }
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function request()
    {
        return $this->belongsTo(RequestSupply::class, 'request_id');
    }

    public function getAccumulate($supplyId, $requestSupplyId)
    {
        return self::join('invoice_supplies', 'invoice_supplies.invoice_id', '=', 'invoices.id')
            ->where('status', CommonStatus::APPROVED)
            ->where('supplies_id', $supplyId)
            ->where('request_id', $requestSupplyId)
            ->sum('quantity');
    }
}
