<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProcessLogResource;
use App\Http\Resources\InventoriesDetailResource;
use App\Http\Resources\SupplyDeliveryOnDemandResource;
use App\Models\Inventory;
use App\Services\Api\InventoryService as Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Enums\CommonStatus;
use Carbon\Carbon;

class InventoriesController extends BaseController
{
    protected $service;
    protected $module;

    public function __construct(Service $service, Inventory $inventoryModel)
    {
        $this->service = $service;
        $this->inventoryModel = $inventoryModel;
    }

    public function getSuppliesForDeliveryOnDemand(Request $request)
    {
        $this->module = 'delivery_on_demands';

        $params = $request->only('per_page', 'search_option', 'project_id');

        $supplies = SupplyDeliveryOnDemandResource::apiPaginate($this->service->getSuppliesForDeliveryOnDemand($params), $request);

        return $this->responseSuccess($supplies);
    }

    public function detail(Request $request)
    {
        $this->module = 'inventories_detail';

        $params = $request->only('per_page', 'search_option', 'project_id');

        $inventories = InventoriesDetailResource::apiPaginate($this->service->getDetailInventories($params), $request);

        return $this->responseSuccess($inventories);
    }


    public function getQuantity($supplyId, $inputId, $outputId)
    {
        $searchOption = ['end_date' => Carbon::now()->format('d/m/Y')];

        $periodInput = $this->inventoryModel->getQuantityInputInPeriod($supplyId, $inputId, $searchOption);
        $periodOutput = $this->inventoryModel->getQuantityOutputInPeriod($supplyId, $inputId, $searchOption);
        list($stock, $diff) = $this->inventoryModel->getStock($supplyId, $inputId, $searchOption, true);
        list($periodInput, $periodOutput) = $this->inventoryModel->adjustQuantities($periodInput, $periodOutput, $diff);
        $quantityEndPeriodInput = $periodInput - $periodOutput;

        $periodInput = $this->inventoryModel->getQuantityInputInPeriod($supplyId, $outputId, $searchOption);
        $periodOutput = $this->inventoryModel->getQuantityOutputInPeriod($supplyId, $outputId, $searchOption);
        list($stock, $diff) = $this->inventoryModel->getStock($supplyId, $outputId, $searchOption, true);
        list($periodInput, $periodOutput) = $this->inventoryModel->adjustQuantities($periodInput, $periodOutput, $diff);
        $quantityEndPeriodOutput = $periodInput - $periodOutput;

        return $this->responseSuccess([
            'quantity_input'  => $quantityEndPeriodInput,
            'quantity_output' => $quantityEndPeriodOutput,
        ]);
    }

    public function getQuantityInStock($supplyId, $projectId)
    {
        $quantityInStock = $this->service->getQuantity($supplyId, $projectId);

        $accumulatedQuantity = (float) DB::table('receipt_output_supplies')
            ->join('receipt_outputs', 'receipt_outputs.id', '=', 'receipt_output_supplies.output_id')
            ->join('projects', 'projects.id', '=', 'receipt_outputs.output_id')
            ->where('receipt_outputs.status', CommonStatus::APPROVED)
            ->whereNull('receipt_outputs.deleted_at')
            ->whereNull('projects.deleted_at')
            ->whereNotNull('receipt_outputs.request_supply_id')
            ->where('receipt_output_supplies.supplies_id', $supplyId)
            ->sum('quantity');

        return $this->responseSuccess([
            'quantity_in_stock'    => $quantityInStock,
            'accumulated_quantity' => $accumulatedQuantity
        ]);
    }
}
