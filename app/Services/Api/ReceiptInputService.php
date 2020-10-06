<?php

namespace App\Services\Api;

use App\Enums\ReceiptInputStatus;
use App\Models\ReceiptInput as Model;
use App\Services\BaseService;
use App\Models\Inventory;

class ReceiptInputService extends BaseService
{
    protected $inventoryService;

    public function __construct(Model $model, InventoryService $inventoryService)
    {
        $this->model = $model;
        $this->inventoryService = $inventoryService;
    }

    public function getReceiptInputs($params)
    {
        $query = $this->model;

        $query = $query->where('input_id', $params['project_id']);

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
                ->where('date_input', '>=', carbon_date($searchOption['date_from'])->startOfDay())
                ->where('date_input', '<=', carbon_date($searchOption['date_till'])->endOfDay());
        }

        if (isset($searchOption['supplier'])) 
        {
            $query = $query->where('output_id', '=', $searchOption['supplier']);
        }

        if (isset($searchOption['project'])) 
        {
            $query = $query->where('input_id', '=', $searchOption['project']);
        }

        if (isset($searchOption['status']))
        {
            $query = $query->where('status', '=', $searchOption['status']);
        }

        $query->orderBy('created_at', 'desc');

        return $query->with(['project', 'supplier', 'request']);
    }

    public function smoothDataBeforeSave($inputs, $fields = [])
    {
        $pivotName = getPivotName($inputs, 'input');

        $fields = [
            'status' => true,
            'dates'  => [],
        ];

        $inputs = $this->preSmooth($inputs, $pivotName, $fields);

        $pivotData = collect($inputs[$pivotName])->keyBy('id')->map(function ($pivot) use ($inputs) {

            $quantity         = $pivot['quantity']   ?? 0;
            $accumulate       = $pivot['accumulate'] ?? 0;
            $originalQuantity = $pivot['original_quantity'] ?? 0;

            if (isset($inputs['id']))
            {
                $prevQuantity = $pivot['prev_quantity'] ?? 0;
                $cumulative   = $accumulate;// + ($quantity - $prevQuantity);
            }
            else
            {
                $cumulative = $accumulate;// + $quantity;
            }

            if ($accumulate + $quantity > $originalQuantity)
            {
                throw new \Exception('Số lượng + SL lũy kế nhập kho không phải lớn hơn SL nhập kho chứng từ');
            }

            return [
                'original_quantity' => $originalQuantity           ?? 0,
                'quantity'          => $quantity                   ?? 0,
                'unit_price'        => $pivot['unit_price']        ?? 0,
                'difference_reason' => $pivot['difference_reason'] ?? null,
                'cumulative'        => $cumulative,
            ];
        })->toArray();

        list($inputs, $pivotData) = $this->postSmooth($inputs, $pivotData);

        return [$inputs, $pivotData, $pivotName];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'receipt_input');
    }

    public function createSpec(&$entry, &$inputs, &$pivotData)
    {
        $this->inventoryService->updateInventory($inputs['input_id'], $pivotData, Inventory::INPUT);
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'receipt_input');
    }

    public function updateSpec(&$entry, &$inputs, &$pivotData)
    {
        $this->inventoryService->updateInventory($inputs['input_id'], $pivotData, Inventory::INPUT);
    }

    public function getNoticeBody($attributes)
    {
        return 'Nhập kho bị đổi: [' . $attributes['code'] . ']';
    }
}
