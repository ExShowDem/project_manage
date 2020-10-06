<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ReceiptOutputRequest;
use App\Http\Resources\ProcessLogResource;
use App\Http\Resources\ReceiptOutputResource as Resource;
use App\Services\Api\InventoryService;
use App\Services\Api\ReceiptOutputService as Service;
use Illuminate\Http\Request;

class ReceiptOutputController extends BaseController
{
    protected $service;
    protected $inventoryService;
    protected $module = 'receipt_outputs';

    public function __construct(Service $service, InventoryService $inventoryService)
    {
        $this->service = $service;
        $this->inventoryService = $inventoryService;
    }

    public function create(ReceiptOutputRequest $request)
    {
        $inputs = $request->all();

        $inputs['created_by'] = auth()->id();

        return parent::genericSave($request, $inputs, 'create');
    }

    public function index(Request $request)
    {
        $params = $request->only('per_page', 'search_option', 'project_id');
        $receiptOutput = Resource::apiPaginate($this->service->getReceiptOutputs($params), $request);

        return $this->responseSuccess($receiptOutput);
    }

    public function deleteReceiptOutput(Request $request, $id)
    {
        $result = $this->service->delete($id);

        if ($result) 
        {
            return $this->responseSuccess();
        }

        return $this->responseError('api.code.common.delete_failed');
    }

    public function showReceiptOutput(Request $request, $id)
    {
        $item = $this->service->find($id, ['project', 'supplies', 'files', 'comments.user', 'supplier', 'requestSupply', 'receiverType']);

        if ($item)
        {
            return $this->responseSuccess(new Resource($item));
        }
        else
        {
            return $this->responseError('api.code.common.show_failed');
        }
    }

    public function update(ReceiptOutputRequest $request, $id)
    {
        $inputs = $request->all();

        return parent::genericSave($request, $inputs, 'update', $id);
    }

    public function getSuppliesInformation($supplyIds, $projectId, $exportType)
    {
        $explodeSupplyIds = explode_ids($supplyIds);

        $supplies = $this->service->getSuppliesInformation($explodeSupplyIds, $projectId, $exportType);

        $items = [];

        foreach ($supplies as $supply) 
        {
            $supply['quantity_in_stock'] = $this->inventoryService->getQuantity($supply['supply_id'], $projectId) ?? 0;

            array_push($items, $supply);
        }

        return $this->responseSuccess($items);
    }

    public function tracking($id)
    {
        $items = $this->service->tracking($id);

        return $this->responseSuccess(ProcessLogResource::collection($items));
    }
}
