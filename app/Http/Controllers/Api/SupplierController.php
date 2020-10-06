<?php

namespace App\Http\Controllers\Api;

use App\Enums\SupplierType;
use App\Http\Requests\Api\SupplierRequest;
use App\Http\Resources\SupplierResource as Resource;
use App\Models\Item;
use App\Services\Api\SupplierService as Service;
use Illuminate\Http\Request;
use App\Http\Resources\ProcessLogResource;

class SupplierController extends BaseController
{
    protected $service;
    protected $module = 'suppliers';

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->only('per_page', 'search_option', 'project_id');

        $params['type'] = $this->detectType($request);

        $items = Resource::apiPaginate($this->service->getListSuppliers($params), $request);

        return $this->responseSuccess($items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    private function detectType(Request $request)
    {
        return SupplierType::getValue(strtoupper($request->get('type', 'supplier')));
    }


    /**
     * @param SupplierRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SupplierRequest $request)
    {
        $inputs = $request->validated();

        $inputs['type']               = $this->detectType($request);
        $inputs['current_project_id'] = $request->input('current_project_id');
        $inputs['project'] = $request->input('project');

        return parent::genericSave($request, $inputs, 'create');
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $item = $this->service->find($id, ['project']);

        if ($item)
        {
            return $this->responseSuccess(new Resource($item));
        }
        else
        {
            return $this->responseError('api.code.common.show_failed');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * @param SupplierRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(SupplierRequest $request, $id)
    {
        $inputs = $request->validated();

        $inputs['type']               = $this->detectType($request);
        $inputs['current_project_id'] = $request->input('current_project_id');
        $inputs['project'] = $request->input('project');

        return parent::genericSave($request, $inputs, 'update', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->service->delete($id);

        if ($result) 
        {
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
