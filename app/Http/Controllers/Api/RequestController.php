<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\RequestSupplyResource as Resource;
use App\Services\Api\RequestService as Service;
use App\Http\Resources\ProcessLogResource;
use App\Http\Requests\Api\RequestSuppliesRequest;
use App\Models\Invoice;
use App\Models\ReceiptInput;

class RequestController extends BaseController
{
    protected $service;
    protected $invoiceModel;
    protected $receiptInputModel;
    protected $module = 'supplies.request_from_project';

    public function __construct(Service $service, Invoice $invoiceModel, ReceiptInput $receiptInputModel)
    {
        $this->service      = $service;
        $this->invoiceModel = $invoiceModel;
        $this->receiptInputModel = $receiptInputModel;
    }

    public function create(RequestSuppliesRequest $request)
    {
        $inputs = $request->all();

        if ($inputs['target'] === "company" && !auth()->user()->can('supplies.request_from_company.create'))
        {
            return $this->responseError('api.code.common.not_authorized');
        }

        if ($inputs['target'] === "project" && !auth()->user()->can('supplies.request_from_project.create'))
        {
            return $this->responseError('api.code.common.not_authorized');
        }

        $inputs['created_by'] = auth()->id();


        return parent::genericSave($request, $inputs, 'create');
    }

    public function showRequestSupplies(Request $request, $id)
    {
        $item = $this->service->find($id, ['supplies', 'files', 'comments.user', 'creator', 'receiverType']);

        if ($request->has('search_option') && isset($request->input('search_option')['for_invoice'])) 
        {
            foreach ($item->supplies as &$supply) 
            {
                $supply->accumulate = $this->invoiceModel->getAccumulate($supply->id, $id);
                $supply->existing_quantity = $supply->pivot->quantity;
            }
        }

        if ($request->has('search_option') && isset($request->input('search_option')['for_receipt_input'])) 
        {
            foreach ($item->supplies as &$supply) 
            {
                $supply->accumulate = $this->receiptInputModel->getAccumulate($supply->id, $id);
            }
        }

        if ($item)
        {
            if ($item->target === "company" && !auth()->user()->can('supplies.request_from_company.read')) 
            {
                return $this->responseError('api.code.common.not_authorized');
            }

            if ($item->target === "project" && !auth()->user()->can('supplies.request_from_project.read')) 
            {
                return $this->responseError('api.code.common.not_authorized');
            }

            return $this->responseSuccess(new Resource($item));
        }
        else
        {
            return $this->responseError('api.code.common.show_failed');
        }
    }

    public function update(RequestSuppliesRequest $request, $id)
    {
        if (!auth()->user()->can('supplies.request_from_project.update')) 
        {
            return $this->responseError('api.code.common.not_authorized');
        }

        $inputs = $request->all();

        return parent::genericSave($request, $inputs, 'update', $id);
    }

    public function requestSupplies(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $items = Resource::apiPaginate($this->service->getListItems($params), $request);

        return $this->responseSuccess($items);
    }

    public function deleteRequestSupplies(Request $request, $id)
    {
        $item = $this->service->find($id);

        if ($item->target === "company" && !auth()->user()->can('supplies.request_from_company.delete')) {
            return $this->responseError('api.code.common.not_authorized');
        }

        if ($item->target === "project" && !auth()->user()->can('supplies.request_from_project.delete')) {
            return $this->responseError('api.code.common.not_authorized');
        }

        $result = $this->service->delete($id);

        if ($result) {
            return $this->responseSuccess();
        }

        return $this->responseError('api.code.common.delete_failed');
    }

    public function tracking($id)
    {
        $items = $this->service->tracking($id);

        return $this->responseSuccess(ProcessLogResource::collection($items));
    }
}
