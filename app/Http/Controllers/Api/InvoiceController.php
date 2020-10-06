<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\InvoiceRequest;
use App\Http\Requests\Api\TicketImportRequest;
use App\Http\Resources\InvoiceResource as Resource;
use App\Http\Resources\ProcessLogResource;
use App\Services\Api\InvoiceService as Service;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\RequestSupply;

class InvoiceController extends BaseController
{
    protected $service;
    protected $model;
    protected $requestModel;
    protected $module = 'invoices';

    public function __construct(Service $service, Invoice $invoice, RequestSupply $requestModel)
    {
        $this->service = $service;
        $this->model   = $invoice;
        $this->requestModel = $requestModel;
    }

    public function create(InvoiceRequest $request)
    {
        $inputs = $request->all();

        $inputs['created_by'] = auth()->id();

        return parent::genericSave($request, $inputs, 'create');
    }

    public function index(Request $request)
    {
        $params = $request->only('per_page', 'search_option', 'project_id');

        $tasks = Resource::apiPaginate($this->service->getInvoices($params), $request);

        return $this->responseSuccess($tasks);
    }

    public function deleteInvoice(Request $request, $id)
    {
        $result = $this->service->delete($id);

        if ($result) 
        {
            return $this->responseSuccess();
        }

        return $this->responseError('api.code.common.delete_failed');
    }

    public function showInvoice(Request $request, $id)
    {
        $item = $this->service->find($id, ['supplies', 'files', 'comments.user', 'supplier', 'project', 'request']);

        foreach ($item->supplies as &$supply) 
        {
            $supply->pivot->accumulate = $this->model->getAccumulate($supply->id, $item->request_id);
            $supply->pivot->existing_quantity = $this->requestModel->getExistingQuantity($supply->id, $item->request_id);
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

    public function update(InvoiceRequest $request, $id)
    {
        $inputs = $request->all();

        return parent::genericSave($request, $inputs, 'update', $id);
    }

    public function storeTicketImport(TicketImportRequest $request, $id)
    {
        $this->storeTicketImport($id, $request->all());
    }

    public function tracking($id)
    {
        $items = $this->service->tracking($id);

        return $this->responseSuccess(ProcessLogResource::collection($items));
    }
}
