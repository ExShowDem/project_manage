<?php

namespace App\Services\Api;

use App\Enums\RequestSuppliesStatus;
use App\Models\Supplies;
use App\Models\SupplyDetail;
use App\Models\SupplyEachRequest;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class SupplyService extends BaseService
{
    public function __construct(Supplies $model)
    {
        $this->model = $model;
    }

    public function getListSelect2($params, $select = [])
    {
        $query = $this->model;

        if ($select) 
        {
            unset($select['name']);

            $select[] = DB::raw("(SELECT CONCAT(supplies.name, ' - ', supply_unit.name) 
                            FROM units as supply_unit 
                            WHERE supply_unit.id = supplies.unit_id) as name"
            );

            $query = $query->select($select);
        }

        if (isset($params['search_option']))
        {
            $searchOption = $params['search_option'];
        }

        if (isset($searchOption['keyword'])) 
        {
            $query = $query->whereNested(function($q) use ($searchOption) {
                $q
                    ->where('name', 'like', '%' . $searchOption['keyword'] . '%')
                    ->orWhere('code', 'like', '%' . $searchOption['keyword'] . '%');
            });
        }

        if (isset($searchOption['category'])) 
        {
            $query = $query->where('category_supplies_id', '=', $searchOption['category']);
        }

        if (isset($searchOption['unit'])) 
        {
            $query = $query->where('unit_id', '=', $searchOption['unit']);
        }

        if (isset($searchOption['with_inventory'])) 
        {
            $query = $query->with(['inventory' => function ($inventories) use ($searchOption) {
                $inventories->where('project_id', $searchOption['with_inventory']);
            }]);
        }

        if (isset($searchOption['exclude_ids'])) 
        {
            $query = $query->whereNotIn('id', $searchOption['exclude_ids']);
        }

        return $query->apiPaginate($params['per_page'] ?? null);
    }

    public function getList($params, $relations = [])
    {
        $query = $this->model;

        if (isset($params['project_id']) && $params['project_id']) 
        {
            $query = $query->where('project_id', $params['project_id']);
        }

        if (isset($params['search_option']))
        {
            $searchOption = $params['search_option'];
        }

        if (isset($searchOption['keyword'])) 
        {
            $query = $query->whereNested(function($q) use ($searchOption) {
                $q
                    ->where('name', 'like', '%' . $searchOption['keyword'] . '%')
                    ->orWhere('code', 'like', '%' . $searchOption['keyword'] . '%');
            });
        }

        $query->orderBy('created_at', 'desc');

        return $query = $query->with($relations);
    }

    public function createItem($inputs)
    {
        try 
        {
            DB::beginTransaction();

            $supply = $this->model->create($inputs['basicInputs']);

            $supply->supplyDetail()->create($inputs['detailInputs']);

            DB::commit();

            $result = [
                'error' => false,
                'response' => $supply,
            ];
        } 
        catch (\Exception $e) 
        {
            $result = [
                'error' => true,
                'key' => 'api.code.common.validate_failed',
                'data' => ['supply' => [errorMessage($e)]],
            ];

            Log::error($result);

            DB::rollBack();
        }
        finally
        {
            return $result;
        }
    }

    public function updateItem($inputs, $itemId)
    {
        try 
        {
            DB::beginTransaction();

            $supply = $this->model->findOrFail($itemId);

            $supply->update($inputs['basicInputs']);

            SupplyDetail::where('supplies_id', $supply->id)->update($inputs['detailInputs']);

            DB::commit();

            $result = [
                'error' => false,
                'response' => $supply,
            ];
        } 
        catch (\Exception $e) 
        {
            $result = [
                'error' => true,
                'key' => 'api.code.common.validate_failed',
                'data' => ['supply' => [errorMessage($e)]],
            ];

            Log::error($result);

            DB::rollBack();
        }
        finally
        {
            return $result;
        }
    }

    public function getSuppliesByRequest($requestSupplyId)
    {
        $supplies = SupplyEachRequest::with(['item', 'supply'])
            ->where('supplies_request_id', $requestSupplyId)
            ->whereNull('deleted_at')
            ->get()
            ->toArray();

        return $supplies;
    }

    public function getSuppliesByOffer($offerBuyId)
    {
        $supplies = DB::table('offer_buy_supplies')
            ->join('supplies', 'supplies.id' , '=', 'offer_buy_supplies.supplies_id')
            ->join('units', 'units.id' , '=', 'supplies.unit_id')
            ->where('offer_buy_supplies.offer_buy_id', $offerBuyId)
            ->select([
                DB::raw('offer_buy_supplies.*'), 
                DB::raw('supplies.*'), 
                DB::raw('units.name AS unit_name')
            ])
            ->get()
            ->toArray();

        return $supplies;
    }

    public function getExportableQuantity($itemId, $supplyId)
    {
        return $this->model->getExportableQuantity($itemId, $supplyId);
    }
}
