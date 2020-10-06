<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\PaymentOrderRequest;
use App\Http\Resources\PaymentOrderResource as Resource;
use App\Http\Resources\ProcessLogResource;
use App\Services\Api\PaymentOrderService as Service;
use Illuminate\Http\Request;

class PaymentOrderController extends BaseController
{
    protected $service;
    protected $module = 'payment_order';

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $params = $request->only('per_page', 'search_option', 'project_id');
        $query  = $this->service->getListPaymentOrder($params);

        $supplies = Resource::apiPaginate($query, $request);

        $supplies->sums                   = [];
        $contractValueSum                 = $query->sum('contract_value');
        $supplies->sums['contract_value'] = $contractValueSum;
        $settlementValueSum                 = $query->sum('settlement_value');
        $supplies->sums['settlement_value'] = $settlementValueSum;

        return $this->responseSuccess($supplies);
    }

    /**
     * @param PaymentOrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PaymentOrderRequest $request)
    {
        $inputs = $request->all();

        $inputs['created_by'] = auth()->id();

        return parent::genericSave($request, $inputs, 'create');
    }

    public function destroy(Request $request, $id)
    {
        $result = $this->service->delete($id);

        if ($result) 
        {
            return $this->responseSuccess();
        }

        return $this->responseError('api.code.common.delete_failed');
    }

    public function show(Request $request, $id)
    {
        $item = $this->service->find($id);

        if ($item)
        {
            return $this->responseSuccess(new Resource($item));
        }
        else
        {
            return $this->responseError('api.code.common.show_failed');
        }
    }

    public function update(PaymentOrderRequest $request, $id)
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
