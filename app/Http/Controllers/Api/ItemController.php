<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\ItemResource as Resource;
use App\Services\Api\ItemService as Service;
use App\Http\Resources\ProcessLogResource;
use App\Http\Requests\Api\ItemRequest;

class ItemController extends BaseController
{
    protected $service;
    protected $module = 'items';

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

        $items = Resource::apiPaginate($this->service->getListItems($params), $request);

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

    /**
     * @param ItemRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ItemRequest $request)
    {
        $inputs = $request->all();

        $inputs['created_by'] = auth()->id();

        return parent::genericSave($request, $inputs, 'create');
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $item = $this->service->find($id, ['project', 'user', 'supplies', 'files', 'comments.user']);

        if ($item)
        {
            $item = new Resource($item);

            if ($request->has('search_options') && isset($request->input('search_options')['for_request']))
            {
                $suppliesModel = resolve('App\Models\Supplies');
                $projectId = $request->input('search_options')['project_id'];

                foreach ($item->supplies as &$supply) 
                {
                    $supply->pivot->cumulative = $suppliesModel->getCumulativeQuantity($supply->id, $item->id, $projectId) ?? 0;
                    $supply->pivot->approved_cum = $suppliesModel->getCumulativeQuantity($supply->id, $item->id, $projectId, true) ?? 0;
                    $supply->pivot->input_cumulative = $suppliesModel->getInputCumulative($supply->id, $item->id, $projectId) ?? 0;
                }
            }

            return $this->responseSuccess($item);
        }
        else
        {
            return $this->responseError('api.code.common.show_failed');
        }
    }

    /**
     * @param ItemRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ItemRequest $request, $id)
    {
        $inputs = $request->all();

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
