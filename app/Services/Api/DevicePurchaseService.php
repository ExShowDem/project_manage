<?php

namespace App\Services\Api;

use App\Models\DevicePurchase as Model;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Enums\CommonStatus;

class DevicePurchaseService extends BaseService
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

        if (isset($params['search_option']['keyword'])) 
        {
            $query = $query->whereNested(function($q) use ($params) {
                $q
                    ->where('code', 'like', '%' . $params['search_option']['keyword'] . '%')
                    ->orWhere('name', 'like', '%' . $params['search_option']['keyword'] . '%');
            });
        }

        if (isset($params['search_option']['from_date']) && isset($params['search_option']['till_date'])) 
        {
            $query = $query
                ->where('created_at', '>=', carbon_date($params['search_option']['from_date'])->startOfDay())
                ->where('created_at', '<=', carbon_date($params['search_option']['till_date'])->endOfDay());
        }

        if (isset($params['search_option']['place'])) 
        {
            $query = $query->where('place_id', '=', $params['search_option']['place']);
        }

        if (isset($params['search_option']['request'])) 
        {
            $query = $query->where('request_id', '=', $params['search_option']['request']);
        }

        if (isset($params['search_option']['project'])) 
        {
            $query = $query->where('project_id', '=', $params['search_option']['project']);
        }

        if (isset($params['search_option']['creator'])) 
        {
            $query = $query->where('creator_id', '=', $params['search_option']['creator']);
        }

        if (isset($params['search_option']['status']))
        {
            $query = $query->where('status', '=', $params['search_option']['status']);
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
                'requested_quantity'   => isset($pivot['requested_quantity']) ? $pivot['requested_quantity'] : 0,
                'quantity'             => isset($pivot['quantity'])           ? $pivot['quantity']           : 0,
                'unit_price'           => isset($pivot['unit_price'])         ? $pivot['unit_price']         : 0,
                'total_price'          => isset($pivot['total_price'])        ? $pivot['total_price']        : 0,
                'note'                 => isset($pivot['note'])               ? $pivot['note']               : '',
            ];
        })->toArray();

        list($inputs, $pivotData) = $this->postSmooth($inputs, $pivotData);

        foreach ($pivotData as $pivotRow)
        {
            if ($pivotRow['quantity'] > $pivotRow['requested_quantity'])
            {
                throw new \Exception('Số lượng phải nhỏ hơn số lượng yêu cầu.');
            }
        } 

        return [$inputs, $pivotData, $pivotName];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'device_purchase');
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'device_purchase');
    }

    public function getNoticeBody($attributes)
    {
        return 'Mua thiết bị bị đổi: ' . $attributes['name'] . ' [' . $attributes['code'] . ']';
    }

    public function getListSelect2($params, $select = [])
    {
        $query = $this->model;

        if ($select) 
        {
            $query = $query->select($select);
        }

        $query = $query->where('progress', '<', 100);

        return $query->apiPaginate($params['per_page'] ?? null);
    }

    private function updateProgress($purchaseRequestId)
    {
        $requested = DB::table('device_purchase_request_devices')
            ->where('device_purchase_request_id', $purchaseRequestId)
            ->select(DB::raw("SUM(quantity) AS sum_requested"))
            ->first();

        $sumRequested = (int) $requested->sum_requested;

        $relatedPurchaseIds = DB::table('device_purchases')
            ->where('request_id', $purchaseRequestId)
            ->pluck('id')
            ->toArray();

        $bought = DB::table('device_purchase_devices')
            ->whereIn('device_purchase_id', $relatedPurchaseIds)
            ->select(DB::raw("SUM(quantity) AS sum_bought"))
            ->first();

        $sumBought = (int) $bought->sum_bought;

        $progress = ($sumBought / $sumRequested) * 100;
        $progress = ($progress > 100) ? 100 : $progress;
        $progress = (float) number_format((float) $progress, 2);

        DB::table('device_purchase_requests')
            ->where('id', $purchaseRequestId)
            ->update(['progress' => $progress]);

        //@todo log action
    }
}
