<?php

namespace App\Services\Api;

use App\Models\DeviceContract as Model;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Enums\CommonStatus;

class DeviceContractService extends BaseService
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getListItems($params)
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
            $query = $query->where('code', 'like', '%' . $searchOption['keyword'] . '%');
        }

        if (isset($searchOption['from_date']) && isset($searchOption['till_date'])) 
        {
            $query = $query
                ->where('created_at', '>=', carbon_date($searchOption['from_date'])->startOfDay())
                ->where('created_at', '<=', carbon_date($searchOption['till_date'])->endOfDay());
        }

        if (isset($searchOption['place'])) 
        {
            $query = $query->where('place_id', '=', $searchOption['place']);
        }

        if (isset($searchOption['purchase'])) 
        {
            $query = $query->where('purchase_id', '=', $searchOption['purchase']);
        }

        if (isset($searchOption['project'])) 
        {
            $query = $query->where('project_id', '=', $searchOption['project']);
        }

        if (isset($searchOption['creator'])) 
        {
            $query = $query->where('creator_id', '=', $searchOption['creator']);
        }

        if (isset($searchOption['status']))
        {
            $query = $query->where('status', '=', $searchOption['status']);
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function smoothDataBeforeSave($inputs, $fields = [])
    {
        $pivotName = getPivotName($inputs, 'input');

        $fields = [
            'status' => true,
            'dates'  => [],
        ];

        $fields['dates'][] = [
            'field'      => 'date', 
            'fromFormat' => 'd/m/Y', 
            'toFormat'   => 'Y-m-d H:i:s', 
        ];

        $inputs = $this->preSmooth($inputs, $pivotName, $fields);

        $pivotData = collect($inputs[$pivotName])->keyBy('id')->map(function ($pivot) {

            return [
                'needed_quantity'   => isset($pivot['needed_quantity']) ? $pivot['needed_quantity'] : 0,
                'quantity'          => isset($pivot['quantity'])        ? $pivot['quantity']        : 0,
                'unit_price'        => isset($pivot['unit_price'])      ? $pivot['unit_price']      : 0,
                'total_price'       => isset($pivot['total_price'])     ? $pivot['total_price']     : 0,
                'note'              => isset($pivot['note'])            ? $pivot['note']            : '',
            ];
        })->toArray();

        list($inputs, $pivotData) = $this->postSmooth($inputs, $pivotData);

        foreach ($pivotData as $pivotRow)
        {
            if ($pivotRow['quantity'] > $pivotRow['needed_quantity'])
            {
                throw new \Exception('Số lượng phải nhỏ hơn số lượng cần mua.');
            }
        } 

        return [$inputs, $pivotData, $pivotName];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'device_contract');
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'device_contract');
    }

    public function getNoticeBody($attributes)
    {
        return 'Hóa đơn mua thiết bị bị đổi: [' . $attributes['code'] . ']';
    }

    public function getListSelect2($params, $select = [])
    {
        $query = $this->model;

        if ($select) 
        {
            $query = $query->select($select);
        }

        return $query->apiPaginate($params['per_page'] ?? null);
    }

    private function updateProgress($purchaseId)
    {
        $needed = DB::table('device_purchase_devices')
            ->where('device_purchase_id', $purchaseId)
            ->select(DB::raw("SUM(quantity) AS sum_needed"))
            ->first();

        $relatedContractIds = DB::table('device_contracts')
            ->where('purchase_id', $purchaseId)
            ->pluck('id')
            ->toArray();

        $contracted = DB::table('device_contract_devices')
            ->whereIn('device_contract_id', $relatedContractIds)
            ->select(DB::raw("SUM(quantity) AS sum_contracted"))
            ->first();

        $sumContracted = (int) $contracted->sum_contracted;
        $sumNeeded = (int) $needed->sum_needed;

        $progress = ($sumContracted / $sumNeeded) * 100;
        $progress = ($progress > 100) ? 100 : $progress;
        $progress = (float) number_format((float) $progress, 2);

        DB::table('device_purchases')
            ->where('id', $purchaseId)
            ->update(['progress' => $progress]);

        //@todo log action
    }
}
