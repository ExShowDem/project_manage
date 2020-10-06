<?php

namespace App\Services\Api;

use App\Enums\CommonStatus;
use App\Enums\RequestSuppliesStatus;
use App\Enums\ExportType;
use App\Models\Inventory;
use App\Models\InventoryLog;
use App\Models\SupplyEachRequest;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class InventoryService extends BaseService
{
    public function __construct(Inventory $inventory)
    {
        $this->model = $inventory;
    }

    public function updateInventory($projectId, $supplies, $type, $reverse = false)
    {
        try 
        {
            DB::beginTransaction();

            foreach ($supplies as $key => $value) 
            {
                if ($reverse)
                {
                    $inventory = Inventory::where([
                        'project_id' => $projectId,
                        'supply_id'  => $value->pivot->supplies_id,
                    ])->first();
                }
                else
                {
                    $inventory = Inventory::firstOrNew([
                        'project_id' => $projectId,
                        'supply_id'  => $key,
                    ]);
                }

                if ($reverse)
                {
                    $quantity = $type == Inventory::INPUT ? -$value->pivot->quantity : $value->pivot->quantity;
                }
                else
                {
                    $quantity = $type == Inventory::INPUT ? $value['quantity'] : -$value['quantity'];
                }
                
                $inventory->quantity += $quantity;

                $inventory->save();

                $inventory->logs()->create([
                    'quantity' => $quantity,
                ]);
            }

            DB::commit();

            return true;
        } 
        catch (\Exception $e) 
        {
            Log::error(errorMessage($e));

            DB::rollBack();

            return false;
        }
    }

    public function getSuppliesForDeliveryOnDemand($params)
    {
        $query = SupplyEachRequest::query();

        $query = $query->select([
            DB::raw('supply_each_request.id AS id'),
            DB::raw('supply_each_request.item_id AS item_id'),
            DB::raw('supply_each_request.quantity AS quantity'),
            DB::raw('supply_each_request.unit_price AS unit_price'),
            DB::raw('supply_each_request.date_arrival AS date_arrival'),
            DB::raw('supply_each_request.note AS note'),
            DB::raw('supply_each_request.deleted_at AS deleted_at'),
            DB::raw('supply_each_request.created_at AS created_at'),
            DB::raw('supply_each_request.updated_at AS updated_at'),
            DB::raw('supply_each_request.supply_id AS supply_id'),
            DB::raw('supply_each_request.supplies_request_id AS supplies_request_id'),
            DB::raw('supply_each_request.project_id AS project_id'),
            DB::raw('supply_each_request.quantity_actual AS quantity_actual'),
            DB::raw('null AS offer_buy_id'),
            DB::raw(ExportType::SUPPLY_REQUEST . ' AS export_type'),
            DB::raw('null AS offer_name'),
            DB::raw('null AS offer_code'),
        ]);

        $query = $query->whereIn('supplies_request_id', function ($query) {
            $query->select('id')
                ->from('supplies_requests')
                ->where('status', CommonStatus::APPROVED);
        });

        if (isset($params['project_id'])) {
            $query = $query->where('project_id', $params['project_id']);
        }

        if (isset($params['search_option']['keyword'])) {
            $query = $query->whereIn('supplies_request_id', function ($subQuery) use ($params) {
                $subQuery->select('id')
                    ->from('supplies_requests')
                    ->whereNested(function($sq) use ($params) {
                        $sq
                            ->where('name', 'like', '%' . $params['search_option']['keyword'] . '%')
                            ->orWhere('code', 'like', '%' . $params['search_option']['keyword'] . '%');
                        });
            });
        }

        if (isset($params['search_option']['recipient'])) {
            $query = $query->whereIn('supplies_request_id', function ($subQuery) use ($params) {
                $subQuery->select('id')
                    ->from('supplies_requests')
                    ->where('to_user', '=', $params['search_option']['recipient']);
            });
        }

        if (isset($params['search_option']['status']))
        {
            $query = $query->whereIn('supplies_request_id', function ($subQuery) use ($params) {
                $subQuery->select('id')
                    ->from('supplies_requests')
                    ->where('status', '=', $params['search_option']['status']);
            });
        }

        if (isset($params['search_option']['arrival_date_from']) && isset($params['search_option']['arrival_date_till'])) {
            $query = $query
                ->where('date_arrival', '>=', carbon_date($params['search_option']['arrival_date_from'])->startOfDay())
                ->where('date_arrival', '<=', carbon_date($params['search_option']['arrival_date_till'])->endOfDay());
        }

        if (isset($params['search_option']['item'])) {
            $query = $query->whereIn('supplies_request_id', function ($subQuery) use ($params) {
                $subQuery->select('id')
                    ->from('supplies_requests')
                    ->where('item_id', '=', $params['search_option']['item']);
            });
        }

        if (isset($params['search_option']['supply'])) {
            $query = $query->whereIn('supplies_request_id', function ($subQuery) use ($params) {
                $subQuery->select('id')
                    ->from('supplies_requests')
                    ->where('supply_id', '=', $params['search_option']['supply']);
            });
        }

        $query = $query->with('supply', 'item', 'request.userReceive');

        return $query;
    }

    public function getDetailInventories($params)
    {
        $query = $this->model->newQuery();

        if (isset($params['project_id'])) {
            $query->where('project_id', $params['project_id']);
        }

        if (isset($params['search_option']['view_type'])) {
            // Hien thi vat tu phat sinh
            if ($params['search_option']['view_type'] == 2) {
                $query = $query->whereIn('id', function ($subQuery) use ($params) {
                    $subQuery->select('inventory_id')
                        ->from('inventories_log')
                        ->where('inventories_log.created_at', '>=', carbon_date($params['search_option']['start_date'])->startOfDay())
                        ->where('inventories_log.created_at', '<=', carbon_date($params['search_option']['end_date'])->endOfDay());
                });
            }
        }

        if (isset($params['search_option']['keyword'])) {
            $query = $query->whereIn('supply_id', function ($subQuery) use ($params) {
                $subQuery->select('id')
                    ->from('supplies')
                    ->whereNested(function($sq) use ($params) {
                        $sq
                            ->where('name', 'like', '%' . $params['search_option']['keyword'] . '%')
                            ->orWhere('code', 'like', '%' . $params['search_option']['keyword'] . '%');
                    });
            });
        }

        if (isset($params['search_option']['unit'])) {
            $query = $query->whereIn('supply_id', function ($subQuery) use ($params) {
                $subQuery->select('id')
                    ->from('supplies')
                    ->where('unit_id', '=', $params['search_option']['unit']);
            });
        }

        return $query->with('supply', 'logs');
    }

    public function getQuantity($supplyId, $projectId)
    {
        $searchOption = ['end_date' => Carbon::now()->format('d/m/Y')];
        $periodInput  = $this->model->getQuantityInputInPeriod($supplyId, $projectId, $searchOption);
        $periodOutput = $this->model->getQuantityOutputInPeriod($supplyId, $projectId, $searchOption);

        list($stock, $diff) = $this->model->getStock($supplyId, $projectId, $searchOption, true);
        list($periodInput, $periodOutput) = $this->model->adjustQuantities($periodInput, $periodOutput, $diff);

        $quantityInStock = $periodInput - $periodOutput;

        return (float) (string) $quantityInStock;
    }

    public function getOutputAccumulated($projectId, $requestSupplyId)
    {
        $receiptOutputModel = resolve('App\Models\ReceiptOutput');

        return $receiptOutputModel->getOutputAccumulated($projectId, $requestSupplyId);
    }
}
