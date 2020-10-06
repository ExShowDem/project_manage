<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CategorySupplyRequest;
use App\Http\Resources\CategorySuppliesResource as Resource;
use App\Services\Api\CategorySupplyService as Service;
use Illuminate\Http\Request;

class CategorySuppliesController extends BaseController
{
    protected $service;
    protected $module = 'category_supplies';

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

    public function store(CategorySupplyRequest $request)
    {
        $inputs = $request->all();

        return parent::genericSave($request, $inputs, 'create');
    }

    public function show($id)
    {
        $item = $this->service->find($id, ['parent']);

        if ($item)
        {
            return $this->responseSuccess(new Resource($item));
        }
        else
        {
            return $this->responseError('api.code.common.show_failed');
        }
    }

    public function update(CategorySupplyRequest $request, $id)
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
