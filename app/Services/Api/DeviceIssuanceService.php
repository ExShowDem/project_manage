<?php

namespace App\Services\Api;

use App\Models\DeviceIssuance as Model;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Enums\CommonStatus;

class DeviceIssuanceService extends BaseService
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
            $query = $query->whereNested(function($q) use ($searchOption) {
                $q
                    ->where('code', 'like', '%' . $searchOption['keyword'] . '%')
                    ->orWhere('name', 'like', '%' . $searchOption['keyword'] . '%')
                    ->orWhere('intention', 'like', '%' . $searchOption['keyword'] . '%');
            });
        }

        if (isset($searchOption['from_date']) && isset($searchOption['till_date'])) 
        {
            $query = $query
                ->where('created_at', '>=', carbon_date($searchOption['from_date'])->startOfDay())
                ->where('created_at', '<=', carbon_date($searchOption['till_date'])->endOfDay());
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

        $pivotData = collect($inputs[$pivotName])->keyBy('id')->map(function ($pivot) use ($inputs) {

            $monthlyQuantity   = isset($pivot['monthly_estimated_quantity']) ? $pivot['monthly_estimated_quantity'] : 0;
            $quantity          = isset($pivot['quantity'])                   ? $pivot['quantity']                   : 0;
            $surpassedEstimate = ($quantity > $monthlyQuantity)              ? 1                                    : 0;
            $totalQuantity     = isset($pivot['total_quantity'])             ? $pivot['total_quantity']             : 0;
            $accumulatedQuantity = isset($pivot['accumulated_quantity']) ? $pivot['accumulated_quantity'] : 0;

            // Create
            if (!isset($inputs['id']) && $quantity > $totalQuantity)
            {
                throw new \Exception('Lỗi Số lượng + SL lũy kế > SL Dự trù tổng');
            }

            // Edit
            if (isset($inputs['id']))
            {
                $prevQuantity = isset($pivot['prev_quantity']) ? $pivot['prev_quantity'] : 0;

                if ($accumulatedQuantity - $prevQuantity + $quantity > $totalQuantity)
                {
                    throw new \Exception('Lỗi Số lượng + SL lũy kế > SL Dự trù tổng');
                }
            }

            return [
                'monthly_estimated_quantity' => $monthlyQuantity,
                'quantity'                   => $quantity,
                'has_surpassed_estimates'    => $surpassedEstimate,

                'supply_date' => isset($pivot['supply_date']) ? Carbon::createFromFormat('d/m/Y', $pivot['supply_date'])->format('Y-m-d H:i:s') : null,
                'return_date' => isset($pivot['return_date']) ? Carbon::createFromFormat('d/m/Y', $pivot['return_date'])->format('Y-m-d H:i:s') : null,
                'supply_date1' => isset($pivot['supply_date1']) ? Carbon::createFromFormat('d/m/Y', $pivot['supply_date1'])->format('Y-m-d H:i:s') : null,

                'quantity1'            => isset($pivot['quantity1'])            ? $pivot['quantity1']            : 0,
                'total_quantity'       => $totalQuantity,
                'accumulated_quantity' => $accumulatedQuantity,

            ];
        })->toArray();

        list($inputs, $pivotData) = $this->postSmooth($inputs, $pivotData);

        return [$inputs, $pivotData, $pivotName];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'device_issuance');
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'device_issuance');
    }

    public function getNoticeBody($attributes)
    {
        return 'Phiếu đề nghị cấp thiết bị bị đổi: ' . $attributes['name'] . ' [' . $attributes['code'] . ']';
    }

    public function getListSelect2($params, $select = [])
    {
        $query = $this->model;

        if ($select) 
        {
            $query = $query->select($select);
        }

        if (isset($params['search_option']['current_project_id']) && $params['search_option']['current_project_id'] > 0) 
        {
            $query = $query->where('project_id', '=', $params['search_option']['current_project_id']);
        }

        return $query->apiPaginate($params['per_page'] ?? null);
    }
}
