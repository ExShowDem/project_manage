<?php

namespace App\Services\Api;

use App\Enums\ReceiptOutputStatus;
use App\Enums\ExportType;
use App\Models\Inventory;
use App\Models\ReceiptOutput as Model;
use App\Models\SupplyEachRequest;
use App\Models\RequestSupply;
use App\Models\Item;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class ReceiptOutputService extends BaseService
{
    protected $inventoryService;

    public function __construct(Model $model, InventoryService $inventoryService)
    {
        $this->model = $model;
        $this->inventoryService = $inventoryService;
    }

    public function getReceiptOutputs($params)
    {
        $query = $this->model;

        $query = $query->where('output_id', $params['project_id']);

        if (isset($params['search_option']))
        {
            $searchOption = $params['search_option'];
        }

        if (isset($searchOption['keyword'])) 
        {
            $query = $query->whereNested(function($q) use ($searchOption) {
                $q
                    ->where('code', 'like', '%' . $searchOption['keyword'] . '%')
                    ->orWhere('reason', 'like', '%' . $searchOption['keyword'] . '%');
            });
        }

        if (isset($searchOption['date_from']) && isset($searchOption['date_till'])) 
        {
            $query = $query
                ->where('date_output', '>=', carbon_date($searchOption['date_from'])->startOfDay())
                ->where('date_output', '<=', carbon_date($searchOption['date_till'])->endOfDay());
        }

        if (isset($searchOption['project'])) 
        {
            $query = $query->where('output_id', '=', $searchOption['project']);
        }

        if (isset($searchOption['recipient'])) 
        {
            $query = $query->where('input_id', '=', $searchOption['recipient']);
        }

        if (isset($searchOption['requestSupply'])) 
        {
            $query = $query->where('request_supply_id', '=', $searchOption['requestSupply']);
        }

        if (isset($searchOption['status']))
        {
            $query = $query->where('status', '=', $searchOption['status']);
        }

        $query->orderBy('created_at', 'desc');

        return $query = $query->with(['project', 'supplier', 'requestSupply']);
    }

    public function smoothDataBeforeSave($inputs, $fields = [])
    {
        $pivotName = getPivotName($inputs, 'input');

        $fields = [
            'status' => true,
            'dates'  => [],
        ];

        $inputs = $this->preSmooth($inputs, $pivotName, $fields);

        $pivotData = collect($inputs[$pivotName])->keyBy('id')->map(function ($pivot) {

            $quantityInStock = $pivot['quantity_in_stock'] ?? 0;
            $quantityNeeded  = $pivot['quantity_needed']   ?? 0;
            $quantity        = $pivot['quantity']          ?? 0;

            if ($quantity > $quantityNeeded)
            {
                throw new \Exception('SL thực xuất không phải lớn hơn SL cần xuất');
            }
            
            if ($quantity > $quantityInStock)
            {
                throw new \Exception('SL thực xuất không phải lớn hơn SL tồn kho. Để tăng SL tồn kho bạn cần tạo nhập kho cho vật tư này ');
            }
            
            return [
                'quantity_needed'   => $quantityNeeded,
                'quantity'          => $quantity,
                'unit_price'        => $pivot['unit_price'] ?? 0,
                'quantity_in_stock' => $quantityInStock,
                'cumulative'        => $pivot['accumulated_quantity'] ?? 0,
            ];
        })->toArray();

        list($inputs, $pivotData) = $this->postSmooth($inputs, $pivotData);

        return [$inputs, $pivotData, $pivotName];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'receipt_output');
    }

    public function createSpec(&$entry, &$inputs, &$pivotData)
    {
        $this->inventoryService->updateInventory($inputs['output_id'], $pivotData, Inventory::OUTPUT);

        if ($entry->status->value === ReceiptOutputStatus::APPROVED) 
        {
            $this->updateDeliveryOnDemand($inputs);
        }
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'receipt_output');
    }

    public function updateSpec(&$entry, &$inputs, &$pivotData)
    {
        $this->inventoryService->updateInventory($inputs['output_id'], $pivotData, Inventory::OUTPUT);

        if ($entry->status->value === ReceiptOutputStatus::APPROVED) 
        {
            $this->updateDeliveryOnDemand($inputs);
        }
    }

    public function getNoticeBody($attributes)
    {
        return 'Xuất kho bị đổi: [' . $attributes['code'] . ']';
    }

    public function getSuppliesInformation($supplyIds, $projectId)
    {
        $supplies = SupplyEachRequest::with(['supply', 'request', 'request.receiverType', 'item'])
            ->whereIn('id', $supplyIds)
            ->get()
            ->toArray();

        $itemId      = $supplies[0]['item_id'];
        $accumulated = $this->inventoryService->getOutputAccumulated($projectId, $supplies[0]['supplies_request_id']);

        foreach ($supplies as $key => $supply)
        {
            if (isset($accumulated[$supply['supply_id']]))
            {
                $supplies[$key]['accumulated_quantity'] = $accumulated[$supply['supply_id']]->accumulated_quantity;
            }
            else
            {
                $supplies[$key]['accumulated_quantity'] = 0;
            }
        }

        return $supplies;
    }

    public function updateRequestProgress($inputs, $pivotData)
    {
        $requestModel = resolve('App\Models\RequestSupply');

        foreach ($pivotData as $supplyId => $supply) 
        {
            $supplyRequest = DB::table('supply_each_request')
                ->where('supply_id', '=', $supplyId)
                ->where('supplies_request_id', '=', $inputs['request_supply_id'])
                ->first();

            $progress = $requestModel->calcProgress($supplyId, $inputs['request_supply_id'], $supplyRequest->quantity);

            DB::table('supply_each_request')
                ->where('id', $supplyRequest->id)
                ->update(['progress' => $progress]);
        }

        $progressSum = DB::table('supply_each_request')
            ->where('supplies_request_id', '=', $inputs['request_supply_id'])
            ->sum('progress');

        $suppliesPerThisRequest = DB::table('supply_each_request')
            ->where('supplies_request_id', '=', $inputs['request_supply_id'])
            ->count();

        $progressSum = $progressSum/$suppliesPerThisRequest;

        DB::table('supplies_requests')
            ->where('id', $inputs['request_supply_id'])
            ->update(['progress' => $progressSum]);
    }

    public function updateItemProgress($inputs, $pivotData)
    {
        $requestSupply = RequestSupply::find($inputs['request_supply_id']);
        $supplyModel   = resolve('App\Models\Supplies');

        foreach ($pivotData as $supplyId => $supply) 
        {
            $numerator = (float) $supplyModel->getInputCumulative($supplyId, $requestSupply->item_id, $inputs['project_id']);

            $itemSupply = DB::table('item_supplies')
                ->where('supply_id', '=', $supplyId)
                ->where('item_id', '=', $requestSupply->item_id)
                ->first();

            $denominator = (float) $itemSupply->quantity;

            $progress = $denominator ? ($numerator/$denominator) * 100 : 0;

            DB::table('item_supplies')
                ->where('id', $itemSupply->id)
                ->update(['progress' => $progress]);
        }

        $progressSum = DB::table('item_supplies')
            ->where('item_id', '=', $requestSupply->item_id)
            ->sum('progress');

        $suppliesPerThisItem = DB::table('item_supplies')
            ->where('item_id', '=', $requestSupply->item_id)
            ->count();

        $progressSum = $progressSum/$suppliesPerThisItem;

        DB::table('items')
            ->where('id', $requestSupply->item_id)
            ->update(['progress' => $progressSum]);
    }

    private function updateDeliveryOnDemand($inputs)
    {
        if (!isset($inputs['request_supply_id']) || is_null($inputs['request_supply_id'])) 
        {
            return false;
        }

        $result = collect($inputs['supplies'])->keyBy('supply_each_request_id')->map(function ($supply) {
            return $supply['quantity'];
        });

        foreach ($result as $supplyEachRequestId => $quantity) 
        {
            $supplyEachRequest = SupplyEachRequest::find($supplyEachRequestId);

            // Only come in here if export type is supply_request. Won't come in here if export type is offer_buy
            if ($supplyEachRequest)
            {
                $supplyEachRequest->quantity_actual += $quantity;
                $supplyEachRequest->save();
            }
        }
    }

    public function undoUpdateDeliveryOnDemand($receiptOutput)
    {
        $pivotName = getPivotName($receiptOutput, 'model');
        $model     = $receiptOutput->getAttributes();
        $pivot     = $receiptOutput->$pivotName;

        if (!isset($model['request_supply_id']) || is_null($model['request_supply_id'])) 
        {
            return false;
        }

        $requestSupplyId = $model['request_supply_id'];

        foreach ($pivot as $supply) 
        {
            $supplyEachRequest = SupplyEachRequest::where(['supplies_request_id' => $requestSupplyId, 'supply_id' => $supply->id])->first();

            if ($supplyEachRequest)
            {
                $supplyEachRequest->quantity_actual -= $supply->pivot->quantity;
                $supplyEachRequest->save();
            }
        }
    }
}
