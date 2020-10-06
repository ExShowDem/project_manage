<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\StocktakingRequest;
use App\Http\Resources\ProcessLogResource;
use App\Http\Resources\StocktakingResource;
use App\Models\Stocktaking as Model;
use Illuminate\Support\Facades\DB;
use App\Enums\CommonStatus;
use Carbon\Carbon;
use App\Services\Api\StocktakingService as Service;

class StocktakingController extends BaseController
{
    protected $service;
    protected $module = 'stocktaking';

    public function __construct(Service $service, Model $model)
    {
        $this->service = $service;
        $this->model = $model;
    }

    public function storeStocktaking(StocktakingRequest $request)
    {
        $inputs = $request->all();

        $inputs['created_by'] = auth()->id();

        return parent::genericSave($request, $inputs, 'storeStocktaking');
    }

    public function getListStocktaking(Request $request)
    {
        $params = $request->only('per_page', 'search_option', 'project_id');

        $stocktakings = StocktakingResource::apiPaginate($this->service->getListStocktaking($params), $request);

        return $this->responseSuccess($stocktakings);
    }

    public function showStocktaking(Request $request, $id)
    {
        $item = $this->service->findStocktaking($id, ['project', 'supplies', 'files', 'comments.user']);

        $projectId = $item->project_id;
        $inventoryModel = resolve('App\Models\Inventory');
        $searchOption1 = ['end_date' => Carbon::now()->format('d/m/Y')];

        if ($item)
        {
            foreach ($item->supplies as &$supply) 
            {
                $periodInput1 = $inventoryModel->getQuantityInputInPeriod($supply->id, $projectId, $searchOption1);
                $periodOutput1 = $inventoryModel->getQuantityOutputInPeriod($supply->id, $projectId, $searchOption1);
                list($stock1, $diff1) = $inventoryModel->getStock($supply->id, $projectId, $searchOption1, true);
                list($periodInput1, $periodOutput1) = $inventoryModel->adjustQuantities($periodInput1, $periodOutput1, $diff1);
                $quantityEndPeriod1 = $periodInput1 - $periodOutput1;

                $supply->pivot->quantity_system = $quantityEndPeriod1;
            }

            return $this->responseSuccess(compact('item'));
        }
        else
        {
            return $this->responseError('api.code.common.show_failed');
        }
    }

    public function updateStocktaking(StocktakingRequest $request, $id)
    {
        $inputs = $request->all();

        return parent::genericSave($request, $inputs, 'updateStocktaking', $id);
    }

    public function deleteStocktaking(Request $request, $id)
    {
        $result = $this->service->delete($id);

        if ($result) 
        {
            return $this->responseSuccess();
        }

        return $this->responseError('api.code.common.delete_failed');
    }

    public function trackingStocktaking($id)
    {
        $items = $this->service->tracking($id, Stocktaking::class);

        return $this->responseSuccess(ProcessLogResource::collection($items));
    }
}
