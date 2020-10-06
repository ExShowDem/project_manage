<?php

namespace App\Models;

use App\Enums\CommonStatus;
use Illuminate\Support\Facades\DB;
use App\Models\Traits\DeleteRelationsTrait;
use App\Models\Traits\TaskableTrait;
use BenSampo\Enum\Traits\CastsEnums;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiptOutput extends BaseModel
{
    use SoftDeletes, CastsEnums, TaskableTrait, DeleteRelationsTrait;

    protected $fillable = [
        'request_supply_id',
        'output_id',
        'receiver_type',
        'input_id',
        'date_output',
        'code',
        'reason',
        'status',
        'created_by',
    ];

    protected $casts = [
        'date_output' => 'date:d/m/Y',
    ];

    protected $enumCasts = [
        'status' => CommonStatus::class,
    ];

    protected $appends = [
        'receiver_name'
    ];

    protected $with = [
        'creator',
    ];

    public function supplies()
    {
        return $this->belongsToMany(Supplies::class, 'receipt_output_supplies', 'output_id', 'supplies_id')
            ->withPivot([
                'quantity_needed',
                'quantity',
                'unit_price',
                'quantity_in_stock',
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

    public function setDateOutputAttribute($value)
    {
        if ($value instanceof \DateTime) {
            $this->attributes['date_output'] = $value;
        } else {
            $this->attributes['date_output'] = carbon_date($value);
        }
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'output_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'input_id');
    }

    public function requestSupply()
    {
        return $this->belongsTo(RequestSupply::class, 'request_supply_id');
    }

    public function offerBuy()
    {
        return $this->belongsTo(OfferBuy::class, 'request_supply_id');
    }

    public function receiverType()
    {
        return $this->belongsTo(ReceiverType::class, 'receiver_type');
    }

    public function getReceiverNameAttribute()
    {
        $receiverId = $this->input_id;

        switch ($this->receiver_type) {
            case \App\Enums\ReceiverType::SUPPLIER:
                $receiverName = Supplier::find($receiverId)->name;
                break;
            case \App\Enums\ReceiverType::PROJECT:
                $project = Project::find($receiverId);
                $receiverName = $project->name ?? '';
                break;
            default:
                $receiverName = User::findOrFail($receiverId)->name;
        }

        return $receiverName;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getOutputAccumulated($projectId, $requestSupplyId)
    {
        $accumulated = DB::table('receipt_output_supplies')
            ->join('receipt_outputs', 'receipt_outputs.id', '=', 'receipt_output_supplies.output_id')
            ->join('projects', 'projects.id', '=', 'receipt_outputs.output_id')
            ->where('receipt_outputs.status', CommonStatus::APPROVED)
            ->whereNull('receipt_outputs.deleted_at')
            ->whereNull('projects.deleted_at')
            ->where('receipt_outputs.request_supply_id', '=', $requestSupplyId)
            ->where('receipt_outputs.output_id', $projectId)
            ->select('supplies_id', DB::raw("SUM(quantity) AS accumulated_quantity"))
            ->groupBy('supplies_id')
            ->get()
            ->keyBy('supplies_id')
            ->toArray();

        return $accumulated;
    }
}
