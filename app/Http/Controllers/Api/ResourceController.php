<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ResourceRequest;
use App\Http\Resources\ResourceResource as Resource;
use App\Services\Api\ResourceService as Service;
use Illuminate\Http\Request;

class ResourceController extends BaseController
{
    protected $service;
    protected $module = 'resources';

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $supplies = Resource::apiPaginate($this->service->getList($params), $request);

        return $this->responseSuccess($supplies);
    }

    public function store(ResourceRequest $request)
    {
        $inputs = $request->all();

        return parent::genericSave($request, $inputs, 'create');
    }

    public function show($id)
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

    public function update(ResourceRequest $request, $id)
    {
        $inputs = $request->all();

        return parent::genericSave($request, $inputs, 'update', $id);
    }


    public function destroy($id)
    {
        $result = $this->service->delete($id);

        if ($result) 
        {
            return $this->responseSuccess();
        }

        return $this->responseError('api.code.common.delete_failed');
    }
}
