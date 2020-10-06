<?php

namespace App\Models;

use App\Enums\RequestSuppliesStatus;
use Illuminate\Support\Facades\DB;
use App\Enums\CommonStatus;
use App\Enums\ReceiverType;
use App\Enums\ReceiptOutputStatus;

class Supplies extends BaseModel
{
    protected $fillable = [
        'name',
        'parent_id',
        'unit_id',
        'category_supplies_id',
        'code',
        'project_id',
        'description',
    ];

    protected $with = [
        'unit',
        'supplyDetail'
    ];

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_supplies', 'supplies_id', 'plan_id')
            ->withPivot([
                'quantity',
                'unit_price_budget',
                'date_arrival',
            ]);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_supplies', 'supply_id', 'item_id')
            ->withPivot([
                'quantity',
                'unit_price_budget',
                'total',
                'type',
            ]);
    }

    public function categorySupplies()
    {
        return $this->belongsTo(CategorySupplies::class, 'category_supplies_id');
    }

    public function parent()
    {
        return $this->hasOne(Supplies::class, 'id', 'parent_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function supplyDetail()
    {
        return $this->hasOne(SupplyDetail::class, 'supplies_id');
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class, 'supply_id', 'id');
    }

    public function getExportableQuantity($itemId, $supplyId)
    {
        $itemSupply = DB::table('item_supplies')
            ->where([
                'item_id' => $itemId,
                'supply_id' => $supplyId
            ])->first();

        $quantityItem = !is_null($itemSupply) ? $itemSupply->quantity : 0;

        $quantityRequest = DB::table('supply_each_request')
            ->join('supplies_requests', 'supply_each_request.supplies_request_id', '=', 'supplies_requests.id')
            ->where([
                'supply_each_request.item_id' => $itemId,
                'supply_each_request.supply_id' => $supplyId,
                'supplies_requests.status' => RequestSuppliesStatus::APPROVED,
            ])->sum('supply_each_request.quantity');

        return $quantityItem - $quantityRequest;
    }

    public function getItemSupplyType($id)
    {
        if (is_null($id))
        {
            return '';
        }

        return DB::table('item_supplier_types')->where('id', '=', $id)->select('name')->first()->name;
    }

    public function getCumulativeQuantity($supplyId, $itemId, $projectId, $excludeFwds = false)
    {
        $cumulative = DB::table('supplies_requests')
            ->join('supply_each_request', 'supplies_requests.id', '=', 'supply_each_request.supplies_request_id')
            ->where('supply_each_request.supply_id', $supplyId)
            ->where('supply_each_request.item_id', $itemId)
            ->where('supplies_requests.project_id', $projectId);

        if ($excludeFwds)
        {
            $cumulative = $cumulative->where('supplies_requests.status', '=', CommonStatus::APPROVED);
        }
        else
        {
            $cumulative = $cumulative->whereNested(function($q) {
                $q
                    ->where('supplies_requests.status', '=', CommonStatus::APPROVED)
                    ->orWhere('supplies_requests.status', '=', CommonStatus::FORWARDED);
            });
        }

        $cumulative = $cumulative->groupBy('supply_each_request.supply_id')
            ->sum('supply_each_request.quantity');

        return $cumulative;
    }

    public function getProjectBySupplies($id)
    {
        $getIdProject = DB::table('supplies_requests')->select('project_id')->where('id', $id)->get()->toArray();

        $idProject = $getIdProject[0]->project_id;
        $tableProject = DB::table('projects')->where('id', $idProject)->get()->toArray();

        return $tableProject[0];
    }

    public function getItems($idProject)
    {
        $tableItems = DB::table('items')->where('project_id', $idProject)->get()->toArray();

        if(isset($tableItems[0])){
            $result = $tableItems[0];
        }else{
            $result = 0;
        }
        return $result;
    }

    public function getSuppliesRequests($id)
    {
        $tableSuppliesRequests = DB::table('supplies_requests')->where('id', $id)->get()->toArray();
        return $tableSuppliesRequests[0];
    }

    public function getInputCumulative($supplyId, $itemId, $projectId)
    {
        return DB::table('receipt_outputs')
            ->join('receipt_output_supplies', 'receipt_output_supplies.output_id', '=', 'receipt_outputs.id')
            ->join('supply_each_request', 'supply_each_request.supplies_request_id', '=', 'receipt_outputs.request_supply_id')
            ->whereNull('receipt_outputs.deleted_at')
            ->whereNull('supply_each_request.deleted_at')
            ->whereNull('receipt_output_supplies.deleted_at')
            ->where('receipt_output_supplies.supplies_id', '=', $supplyId)
            ->where('receipt_outputs.status', '=', ReceiptOutputStatus::APPROVED)
            ->where('supply_each_request.project_id', '=', $projectId)
            ->where('supply_each_request.item_id', '=', $itemId)
            ->where('supply_each_request.supply_id', '=', $supplyId)
            ->sum('receipt_output_supplies.quantity');
    }
}
