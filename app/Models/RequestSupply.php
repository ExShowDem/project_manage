<?php

namespace App\Models;

use App\Enums\CommonStatus;
use App\Models\Traits\DeleteRelationsTrait;
use App\Models\Traits\TaskableTrait;
use BenSampo\Enum\Traits\CastsEnums;
use Illuminate\Support\Facades\DB;
use App\Enums\ReceiptOutputStatus;

class RequestSupply extends BaseModel
{
    use CastsEnums, TaskableTrait, DeleteRelationsTrait;

    protected $table = 'supplies_requests';

    protected $fillable = [
        'name',
        'code',
        'receiver_type',
        'to_user',
        'created_by',
        'created_date',
        'item_id',
        'project_id',
        'status',
        'target',        
        'content_offer',
        'progress',
    ];

    protected $casts = [
        'created_date' => 'date:d/m/Y',
    ];

    protected $enumCasts = [
        'status' => CommonStatus::class
    ];

    protected $appends = [
        'receiver_name'
    ];

    public function supplies()
    {
        return $this->belongsToMany(Supplies::class, 'supply_each_request', 'supplies_request_id', 'supply_id')
            ->withPivot([
                'quantity',
                'unit_price',
                'date_arrival',
                'note',                
                'estimate_quantity',
                'cumulative',
                'approved_cum',
                'input_cumulative',
                'remainder',
                'progress',
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function userReceive()
    {
        return $this->belongsTo(User::class, 'to_user');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function receiverType()
    {
        return $this->belongsTo(ReceiverType::class, 'receiver_type');
    }

    public function getReceiver()
    {
        $receiverId = $this->to_user;

        switch ($this->receiver_type) {
            case \App\Enums\ReceiverType::SUPPLIER:
                $receiverName = Supplier::findOrFail($receiverId)->name;
                break;
            case \App\Enums\ReceiverType::PROJECT:
                $receiverName = Project::findOrFail($receiverId)->name;
                break;
            default:
                $receiverName = User::findOrFail($receiverId)->name;
        }

        return $receiverName;
    }

    public function getReceiverNameAttribute()
    {
        if (empty($this->to_user)) return '';

        return $this->getReceiver();
    }

    public function setCreatedDateAttribute($value)
    {
        if ($value instanceof \DateTime) {
            $this->attributes['created_date'] = $value;
        } else {
            $this->attributes['created_date'] = carbon_date($value);
        }
    }

    public function isFromCompany()
    {
        return $this->target == 'company';
    }

    public function getExistingQuantity($supplyId, $requestSupplyId)
    {
        return DB::table('supply_each_request')
            ->where('supply_id', $supplyId)
            ->where('supplies_request_id', $requestSupplyId)
            ->sum('quantity');
    }

    public function calcProgress($supplyId, $requestId, $supplyQuantity)
    {
        $numerator = DB::table('receipt_output_supplies')
            ->join('receipt_outputs', 'receipt_outputs.id', '=', 'receipt_output_supplies.output_id')
            ->whereNull('receipt_outputs.deleted_at')
            ->where('receipt_outputs.status', '=', ReceiptOutputStatus::APPROVED)
            ->where('receipt_output_supplies.supplies_id', '=', $supplyId)
            ->where('receipt_outputs.request_supply_id', '=', $requestId)
            ->select('receipt_output_supplies.quantity')
            ->first();

        $numerator   = (float) ($numerator->quantity ?? 0);
        $denominator = (float) $supplyQuantity;
        $progress    = $denominator ? ($numerator/$denominator) * 100 : 0;

        return $progress;
    }
}
