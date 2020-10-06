<?php

namespace App\Models;

use App\Enums\CommonStatus;
use App\Models\Traits\TaskableTrait;
use App\Enums\PaymentOrderStatus;
use Illuminate\Support\Facades\DB;

class PaymentOrder extends BaseModel
{
    use TaskableTrait;

    protected $table = 'payment_orders';

    protected $fillable = [
        'contract_subcontractor_id',
        'code',
        'subcontractor_id',
        'project_id',
        'created_by',
        'payment_date',
        'content',
        'type_payment',
        'contract_value',
        'settlement_value',
        'status',
        'annex_value',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function subContractor()
    {
        return $this->belongsTo(Subcontractor::class, 'subcontractor_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function contractSub()
    {
        return $this->belongsTo(ContractSubContractor::class, 'contract_subcontractor_id');
    }

    public function files()
    {
        return $this->morphMany(AttachFile::class, 'fileable');
    }

    public function getSumSettle()
    {
        return self::where('status', '=', PaymentOrderStatus::PAID)
            ->sum('settlement_value');
    }
    
    public function getSumSettleByIdContractSubCon($idConSubCon)
    {
        return self::where('status', '=', PaymentOrderStatus::PAID)
            ->where('contract_subcontractor_id', $idConSubCon)
            ->sum('settlement_value');
    }

    public function calcAnnexValue($contractSub, $sumSettle)
    {
        return $contractSub ? ($contractSub->contract_annex_value ?? 0) + ($contractSub->contract_value_vat ?? 0) - $sumSettle : 0;
    }

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {
            $model->tasks()->delete();

        });
    }
}