<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ReceiptTransferRequest;
use App\Http\Resources\ProcessLogResource;
use App\Http\Resources\ReceiptTransferResource as Resource;
use App\Services\Api\ReceiptTransferService as Service;
use App\Services\Api\InventoryService;
use Illuminate\Http\Request;

class ReceiptTransferController extends BaseController
{
    protected $service;
    protected $inventoryService;
    protected $module = 'receipt_transfers';

    public function __construct(Service $service, InventoryService $inventoryService)
    {
        $this->service = $service;
        $this->inventoryService = $inventoryService;
    }

    public function create(ReceiptTransferRequest $request)
    {
        $inputs = $request->all();

        $inputs['created_by'] = auth()->id();

        return parent::genericSave($request, $inputs, 'create');
    }

    public function index(Request $request)
    {
        $params = $request->only('per_page', 'search_option', 'project_id');
        $tasks = Resource::apiPaginate($this->service->getReceiptTransfers($params), $request);

        return $this->responseSuccess($tasks);
    }

    public function deleteReceiptTransfer(Request $request, $id)
    {
        $result = $this->service->delete($id);

        if ($result) 
        {
            return $this->responseSuccess();
        }

        return $this->responseError('api.code.common.delete_failed');
    }

    public function showReceiptTransfer(Request $request, $id)
    {
        $item = $this->service->find($id, ['project', 'supplies', 'files', 'comments.user', 'supplier']);

        if ($item)
        {
            return $this->responseSuccess(compact('item'));
        }
        else
        {
            return $this->responseError('api.code.common.show_failed');
        }
    }

    public function update(ReceiptTransferRequest $request, $id)
    {
        $inputs = $request->all();

        return parent::genericSave($request, $inputs, 'update', $id);
    }

    public function tracking($id)
    {
        $items = $this->service->tracking($id);

        return $this->responseSuccess(ProcessLogResource::collection($items));
    }
}
