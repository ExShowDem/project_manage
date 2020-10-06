<?php

namespace App\Services\Api;

use App\Enums\CommonStatus;
use App\Models\Inventory;
use App\Models\ReceiptTransfer as Model;
use App\Services\BaseService;

class ReceiptTransferService extends BaseService
{
    protected $inventoryService;

    public function __construct(Model $model, InventoryService $inventoryService)
    {
        $this->model = $model;
        $this->inventoryService = $inventoryService;
    }

    public function getReceiptTransfers($params)
    {
        $query = $this->model;

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
                ->where('date_transfer', '>=', carbon_date($searchOption['date_from'])->startOfDay())
                ->where('date_transfer', '<=', carbon_date($searchOption['date_till'])->endOfDay());
        }

        if (isset($searchOption['input'])) 
        {
            $query = $query->where('input_id', '=', $searchOption['input']);
        }

        if (isset($searchOption['output'])) 
        {
            $query = $query->where('output_id', '=', $searchOption['output']);
        }

        if (isset($searchOption['status']))
        {
            $query = $query->where('status', '=', $searchOption['status']);
        }

        $query->orderBy('created_at', 'desc');

        return $query->with(['project', 'supplier']);
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

            $quantity    = (float) $pivot['quantity']        ?? 0;
            $quantityIn  = (float) $pivot['quantity_input']  ?? 0;
            $quantityOut = (float) $pivot['quantity_output'] ?? 0;

            return [
                'quantity'          => $quantity,
                'unit_price'        => $pivot['unit_price'] ?? 0,
                'quantity_input'    => $quantityIn + $quantity,
                'quantity_output'   => $quantityOut - $quantity,
            ];
        })->toArray();

        list($inputs, $pivotData) = $this->postSmooth($inputs, $pivotData);

        return [$inputs, $pivotData, $pivotName];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'receipt_transfer');
    }

    public function createSpec(&$entry, &$inputs, &$pivotData)
    {
        if ($entry->status->value === CommonStatus::APPROVED) 
        {
            $this->inventoryService->updateInventory($inputs['output_id'], $pivotData, Inventory::OUTPUT);
            $this->inventoryService->updateInventory($inputs['input_id'], $pivotData, Inventory::INPUT);
        }
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'receipt_transfer');
    }

    public function updateSpec(&$entry, &$inputs, &$pivotData)
    {
        if ($entry->status->value === CommonStatus::APPROVED) 
        {
            $this->inventoryService->updateInventory($inputs['output_id'], $pivotData, Inventory::OUTPUT);
            $this->inventoryService->updateInventory($inputs['input_id'], $pivotData, Inventory::INPUT);
        }
    }

    public function getNoticeBody($attributes)
    {
        return 'Chuyển kho bị đổi: [' . $attributes['code'] . ']';
    }
}
