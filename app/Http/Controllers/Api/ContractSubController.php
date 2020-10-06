<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ContractSubRequest;
use App\Http\Resources\ContractSubResource as Resource;
use App\Http\Resources\ProcessLogResource;
use App\Services\Api\ContractSubService as Service;
use Illuminate\Http\Request;

class ContractSubController extends BaseController
{
    protected $service;
    protected $module = 'contract_sub';

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $params   = $request->only('per_page', 'search_option', 'project_id');
        $query    = $this->service->getListContractSub($params);
        $supplies = Resource::apiPaginate($query, $request);

        $remainValueSum   = Resource::collection($query->paginate(false)->appends($request->query()))->sum('remain_value');
        $contractValueSum = $query->sum('contract_value_vat');

        $supplies->sums = [];
        $supplies->sums['remain_value']   = $remainValueSum;
        $supplies->sums['contract_value'] = $contractValueSum;

        return $this->responseSuccess($supplies);
    }

    /**
     * @param ContractSubRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ContractSubRequest $request)
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
       // echo "<pre>";print_r($item);exit;
    }

    public function update(ContractSubRequest $request, $id)
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
