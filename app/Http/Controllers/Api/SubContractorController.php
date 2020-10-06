<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\SubContractorRequest;
use App\Http\Resources\ProcessLogResource;
use App\Http\Resources\SubContractorResource as Resource;
use App\Services\Api\SubContractorService as Service;
use Illuminate\Http\Request;

class SubContractorController extends BaseController
{
    protected $service;
    protected $module = 'sub_contractors';

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $params = $request->only('per_page', 'search_option', 'project_id');

        $supplies = Resource::apiPaginate($this->service->getListSubContractor($params), $request);

        return $this->responseSuccess($supplies);
    }

    /**
     * @param SubContractorRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SubContractorRequest $request)
    {
        $inputs = $request->validated();

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

    public function update(SubContractorRequest $request, $id)
    {
        $inputs = $request->all();

        return parent::genericSave($request, $inputs, 'update', $id);
    }

    public function tracking($id)
    {
        $items = $this->service->tracking($id);

        return $this->responseSuccess(ProcessLogResource::collection($items));
    }
    public function getSubcontractors(Subcontractor $subcontractor)
    {
        return $this->responseSuccess(new SubContractorResource($subcontractor));
    }
}
