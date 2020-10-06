<?php

namespace App\Services\Api;

use App\Enums\CommonStatus;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Stocktaking;
use App\Models\Inventory;
use App\Models\InventoryLog;

class StocktakingService extends BaseService
{
    public function __construct(Stocktaking $stocktaking)
    {
        $this->model = $stocktaking;
    }

    public function getListStocktaking($params)
    {
        $query = Stocktaking::query();

        if (isset($params['project_id'])) {
            $query->where('project_id', $params['project_id']);
        }

        if (isset($params['search_option']['keyword'])) {
            $query = $query->whereNested(function($q) use ($params) {
                $q
                    ->where('name', 'like', '%' . $params['search_option']['keyword'] . '%')
                    ->orWhere('code', 'like', '%' . $params['search_option']['keyword'] . '%');
            });
        }

        if (isset($params['search_option']['date_from']) && isset($params['search_option']['date_till'])) {
            $query = $query
                ->where('created_date', '>=', carbon_date($params['search_option']['date_from'])->startOfDay())
                ->where('created_date', '<=', carbon_date($params['search_option']['date_till'])->endOfDay());
        }

        if (isset($params['search_option']['creator']))
        {
            $query = $query->where('created_by', '=', $params['search_option']['creator']);
        }

        if (isset($params['search_option']['status']))
        {
            $query = $query->where('status', '=', $params['search_option']['status']);
        }

        $query->orderBy('created_at', 'desc');

        return $query->with('project', 'creator');
    }

    public function findStocktaking($id, $with = [])
    {
        $query = Stocktaking::query();

        if ($with) {
            $query = $query->with($with);
        }

        return $query->find($id);
    }

    public function storeStocktaking($inputs)
    {        
        try
        {
            list($inputs, $pivotData, $pivotName) = $this->smoothDataBeforeSave($inputs);

            DB::beginTransaction();

            $entry = Stocktaking::create($inputs);

            if ($pivotName !== '')
            {
                $this->logPivotAction('attach', $entry, $pivotName, $pivotData);
            }

            if ($entry['status']->value == CommonStatus::APPROVED) 
            {
                $this->updateQuantityBySupplies($inputs['supplies'], $inputs['project_id']);
            }

            $this->forwardProcess($entry, $inputs);

            DB::commit();

            $result = [
                'error' => false, 
                'response' => $entry,
            ];
        }
        catch (\Exception $e) 
        {
            $result = [
                'error' => true, 
                'key' => 'api.code.common.validate_failed',
                'data' => ['stocktaking' => [errorMessage($e)]],
            ];

            Log::error($result);

            DB::rollBack();
        }
        finally
        {
            return $result;
        }
    }

    public function updateStocktaking($id, $inputs)
    {
        try
        {
            list($inputs, $pivotData, $pivotName) = $this->smoothDataBeforeSave($inputs);

            DB::beginTransaction();

            $entry = Stocktaking::find($id);
            $entry->update($inputs);

            if ($entry['status']->value == CommonStatus::APPROVED) 
            {
                $this->updateQuantityBySupplies($inputs['supplies'], $inputs['project_id']);
            }

            if ($pivotName !== '')
            {
                $this->logPivotAction('sync', $entry, $pivotName, $pivotData);
            }

            $this->forwardProcess($entry, $inputs);

            DB::commit();

            $this->updateNotify($entry, $id);

            $result = [
                'error' => false, 
                'response' => $entry,
            ];
        }
        catch (\Exception $e) 
        {
            $result = [
                'error' => true, 
                'key' => 'api.code.common.validate_failed',
                'data' => ['stocktaking' => [errorMessage($e)]],
            ];

            Log::error($result);

            DB::rollBack();
        }
        finally
        {
            return $result;
        }
    }

    public function getNoticeBody($attributes)
    {
        return 'Kiểm kê kho bị đổi: ' . $attributes['name'] . ' [' . $attributes['code'] . ']';
    }

    private function smoothDataBeforeSave($inputs, $fields = [])
    {
        $pivotName = getPivotName($inputs, 'input');

        $fields = [
            'status' => true,
            'dates'  => [],
            'quantity'  => 'quantity_actual',
        ];

        $inputs = $this->preSmooth($inputs, $pivotName, $fields);

        $pivotData = collect($inputs[$pivotName])->keyBy('id')->map(function ($pivot) {
            return [
                'price'           => isset($pivot['price'])           ? $pivot['price'] : 0,
                'quantity_system' => isset($pivot['quantity_system']) ? $pivot['quantity_system'] : 0,
                'quantity_actual' => isset($pivot['quantity_actual']) ? $pivot['quantity_actual'] : 0,
                'reason'          => isset($pivot['reason'])          ? $pivot['reason'] : '',
                'diff'            => isset($pivot['diff'])            ? $pivot['diff'] : 0,
            ];
        })->toArray();

        list($inputs, $pivotData) = $this->postSmooth($inputs, $pivotData);

        return [$inputs, $pivotData, $pivotName];
    }

    private function updateQuantityBySupplies($supplies, $projectId)
    {
        foreach ($supplies as $supply) {
            $inventory = Inventory::firstOrNew([
                'project_id' => $projectId,
                'supply_id' => $supply['id'],
            ]);

            // update inventory
            $inventory->quantity = $supply['quantity_actual'];
            $inventory->save();

            // update inventory log
            $inventoryLog = new InventoryLog();
            $inventoryLog->inventory_id = $inventory->id;
            $inventoryLog->quantity = $supply['quantity_actual'] - $supply['quantity_system'];
            $inventoryLog->save();
        }
    }
}
