<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ReceiptInputRequest;
use App\Http\Resources\ProcessLogResource;
use App\Http\Resources\ReceiptInputResource as Resource;
use App\Services\Api\ReceiptInputService as Service;
use Illuminate\Http\Request;
use App\Models\ReceiptInput;

class ReceiptInputController extends BaseController
{
    protected $service;
    protected $model;
    protected $module = 'receipt_inputs';

    public function __construct(Service $service, ReceiptInput $receiptInput)
    {
        $this->service = $service;
        $this->model = $receiptInput;
    }

    public function create(ReceiptInputRequest $request)
    {
        $inputs = $request->all();

        $inputs['created_by'] = auth()->id();

        return parent::genericSave($request, $inputs, 'create');
    }

    public function index(Request $request)
    {
        $params        = $request->only('per_page', 'search_option', 'project_id');
        $receiptInputs = Resource::apiPaginate($this->service->getReceiptInputs($params), $request);

        return $this->responseSuccess($receiptInputs);
    }

    public function deleteReceiptInput(Request $request, $id)
    {
        $result = $this->service->delete($id);

        if ($result) 
        {
            return $this->responseSuccess();
        }

        return $this->responseError('api.code.common.delete_failed');
    }

    public function showReceiptInput(Request $request, $id)
    {
        $item = $this->service->find($id, ['project', 'supplies', 'files', 'comments.user', 'supplier', 'request']);

        foreach ($item->supplies as &$supply) 
        {
            $supply->pivot->accumulate = $this->model->getAccumulate($supply->id, $item->request_id);
        }
        
        if ($item)
        {
            return $this->responseSuccess(compact('item'));
        }
        else
        {
            return $this->responseError('api.code.common.show_failed');
        }
    }

    public function update(ReceiptInputRequest $request, $id)
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
