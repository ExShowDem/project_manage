<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\OfferBuyResource as Resource;
use App\Services\Api\OfferBuyService as Service;
use App\Http\Resources\ProcessLogResource;
use App\Http\Requests\Api\OfferBuyRequest;
use App\Models\OfferBuy;

class OfferBuyController extends BaseController
{
    protected $service;
    protected $module = 'offer_buys';

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function create(OfferBuyRequest $request)
    {
        $inputs = $request->all();

        $inputs['created_by'] = auth()->id();

        return parent::genericSave($request, $inputs, 'create');
    }

    public function index(Request $request)
    {
        $params = $request->only('per_page', 'search_option', 'project_id');
        $tasks = Resource::apiPaginate($this->service->getOfferBuys($params), $request);

        return $this->responseSuccess($tasks);
    }

    public function deleteOfferBuy(Request $request, $id)
    {
        $result = $this->service->delete($id);

        if ($result) 
        {
            return $this->responseSuccess();
        }

        return $this->responseError('api.code.common.delete_failed');
    }

    public function showOfferBuy(Request $request, $id)
    {
        $item = $this->service->find($id, ['supplies', 'files', 'comments.user', 'supplier', 'project', 'request']);

        if ($item)
        {
            return $this->responseSuccess(compact('item'));
        }
        else
        {
            return $this->responseError('api.code.common.show_failed');
        }
    }

    public function update(OfferBuyRequest $request, $id)
    {
        $inputs = $request->all();

        return parent::genericSave($request, $inputs, 'update', $id);
    }

    public function getSuppliesByOfferBuyId($offerBuyId)
    {
        $offerBuySupplies = OfferBuy::findOrFail($offerBuyId)->supplies;

        return $this->responseSuccess($offerBuySupplies);
    }

    public function tracking($id)
    {
        $items = $this->service->tracking($id);

        return $this->responseSuccess(ProcessLogResource::collection($items));
    }
}
