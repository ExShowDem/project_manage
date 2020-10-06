<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\SupplyRequest;
use App\Http\Resources\SuppliesResource;
use App\Services\Api\InventoryService;
use App\Services\Api\SupplyService;
use Illuminate\Http\Request;
use App\Enums\ExportType;

class SuppliesController extends BaseController
{
    protected $supplyService;
    protected $inventoryService;
    protected $module = 'supplies';

    public function __construct(SupplyService $supplyService, InventoryService $inventoryService)
    {
        $this->supplyService = $supplyService;
        $this->inventoryService = $inventoryService;
    }

    public function divideRequest(SupplyRequest $request)
    {
        $basicInputs = $request->only([
            'name',
            'parent_id',
            'unit_id',
            'category_supplies_id',
            'code',
            'project_id',
            'description',
        ]);

        $detailInputs = $request->only([
            'material_code',
            'size',
            'specification',
            'supplier',
            'color',
            'intensity',
            'density',
            'standard',
            'source',
        ]);

        $inputs = [
            'basicInputs' => $basicInputs,
            'detailInputs' => $detailInputs,
        ];

        return $inputs;
    }

    public function index(Request $request)
    {

        $params = $request->only('per_page', 'search_option');

        $supplies = SuppliesResource::apiPaginate($this->supplyService->getList($params), $request);

        return $this->responseSuccess($supplies);
    }

    public function store(SupplyRequest $request)
    {

        $result = (object) $this->supplyService->createItem($this->divideRequest($request));

        if (isset($result->error) && $result->error === true)
        {
            $response = $this->responseError($result->key, $result->data);
        }
        elseif ($result->response) 
        {
            $response = $this->responseSuccess($result->response);
        }
        else
        {
            $response = $this->responseError('api.code.common.create_failed');
        }

        return $response;
    }

    public function show($id)
    {
        $item = $this->supplyService->find($id, ['parent', 'unit']);

        if ($item)
        {
            return $this->responseSuccess(new SuppliesResource($item));
        }
        else
        {
            return $this->responseError('api.code.common.show_failed');
        }
    }


    public function update(SupplyRequest $request, $id)
    {

        $result = (object) $this->supplyService->updateItem($this->divideRequest($request), $id);

        if (isset($result->error) && $result->error === true)
        {
            $response = $this->responseError($result->key, $result->data);
        }
        elseif ($result->response) 
        {
            $response = $this->responseSuccess($result->response);
        }
        else
        {
            $response = $this->responseError('api.code.common.update_failed');
        }

        return $response;
    }


    public function destroy($id)
    {
        $result = $this->supplyService->delete($id);

        if ($result) {
            return $this->responseSuccess();
        }

        return $this->responseError('api.code.common.delete_failed');
    }

    public function getSuppliesByRequest($requestSupplyId, $projectId)
    {
        $supplies    = $this->supplyService->getSuppliesByRequest($requestSupplyId);
        $itemId      = $supplies[0]['item_id']; // All supplies inside a supply-request have the same item_id
        $accumulated = $this->inventoryService->getOutputAccumulated($projectId, $requestSupplyId);

        $items = [];

        foreach ($supplies as $supply) 
        {
            $supply['quantity_in_stock'] = $this->inventoryService->getQuantity($supply['supply_id'], $projectId) ?? 0;

            if (isset($accumulated[$supply['supply_id']]))
            {
                $supply['accumulated_quantity'] = $accumulated[$supply['supply_id']]->accumulated_quantity;
            }
            else
            {
                $supply['accumulated_quantity'] = 0;
            }

            if (!is_null($supply['item']) && !is_null($supply['supply']))
            {
                array_push($items, $supply);
            }
        }

        return $this->responseSuccess($items);
    }

    public function getExportableQuantity($itemId, $supplyId)
    {
        $exportableQuantity = $this->supplyService->getExportableQuantity($itemId, $supplyId) ?? null;

        return $this->responseSuccess($exportableQuantity);
    }
}
