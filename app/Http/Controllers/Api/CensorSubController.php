<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CensorSubRequest;
use App\Http\Resources\CensorSubResource as Resource;
use App\Http\Resources\ProcessLogResource;
use App\Services\Api\CensorSubService as Service;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CensorSubController extends BaseController
{
    protected $service;
    protected $module = 'censor_sub';

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $params = $request->only('per_page', 'search_option', 'project_id');

        $supplies = Resource::apiPaginate($this->service->getListCensorSub($params), $request);

        return $this->responseSuccess($supplies);
    }

    /**
     * @param CensorSubRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    /*public function store(CensorSubRequest $request)
    {
        inconsequencial display full
        $inputs = $request->all();
        return parent::genericSave($request, $inputs, 'create');
    }*/
    public function store(CensorSubRequest $request)
    {
        $inputs = $request->all();
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

    public function update(CensorSubRequest $request, $id)
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
