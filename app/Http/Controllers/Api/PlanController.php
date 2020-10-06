<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\PlanResource as Resource;
use App\Services\Api\PlanService as Service;
use App\Http\Resources\ProcessLogResource;
use App\Http\Requests\Api\PlanSupplyRequest;

class PlanController extends BaseController
{
    protected $service;
    protected $module = 'plans.supplies';

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function create(PlanSupplyRequest $request)
    {
        $inputs = $request->all();

        return parent::genericSave($request, $inputs, 'create');
    }

    public function planSupplies(Request $request)
    {
        $params = $request->only('per_page', 'search_option', 'project_id');

        $tasks = Resource::apiPaginate($this->service->getPlanSupplies($params), $request);

        return $this->responseSuccess($tasks);
    }

    public function deletePlanSupplies(Request $request, $id)
    {
        $result = $this->service->delete($id);

        if ($result) 
        {
            return $this->responseSuccess();
        }

        return $this->responseError('api.code.common.delete_failed');
    }

    public function showPlanSupplies(Request $request, $id)
    {
        $item = $this->service->find($id, ['supplies', 'files', 'comments.user', 'creator']);

        if ($item)
        {
            return $this->responseSuccess(compact('item'));
        }
        else
        {
            return $this->responseError('api.code.common.show_failed');
        }
    }

    public function update(PlanSupplyRequest $request, $id)
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
