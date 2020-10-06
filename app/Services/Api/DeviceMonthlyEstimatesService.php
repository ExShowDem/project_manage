<?php

namespace App\Services\Api;

use App\Models\DeviceMonthlyEstimate as Model;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Enums\CommonStatus;

class DeviceMonthlyEstimatesService extends BaseService
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

        $query->orderBy('created_at', 'desc');

        return $query;
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

            if (!isset($pivot['type']))
            {
                throw new \Exception('Bạn Chưa Nhập Cấp/Trả.');
            }

            $quantity1 = isset($pivot['quantity1']) ? $pivot['quantity1'] : 0;
            $quantity2 = isset($pivot['quantity2']) ? $pivot['quantity2'] : 0;
            $quantity3 = isset($pivot['quantity3']) ? $pivot['quantity3'] : 0;
            $quantity4 = isset($pivot['quantity4']) ? $pivot['quantity4'] : 0;
            $quantity5 = isset($pivot['quantity5']) ? $pivot['quantity5'] : 0;
            $quantity6 = isset($pivot['quantity6']) ? $pivot['quantity6'] : 0;

            $accumulatedQuantity = isset($pivot['accumulated_quantity']) ? $pivot['accumulated_quantity'] : 0;
            $quantity            = isset($pivot['quantity'])             ? $pivot['quantity']             : 0;
            $totalQuantity       = isset($pivot['total_quantity'])       ? $pivot['total_quantity']       : 0;

            // Create
            if (!isset($inputs['id']) && $accumulatedQuantity + $quantity > $totalQuantity)
            {
                throw new \Exception('Số Lượng lũy kế + Số Lượng phải nhỏ hơn Số Lượng dự trù tổng.');
            }

            // Edit
            if (isset($inputs['id']))
            {
                $prevQuantity = isset($pivot['prev_quantity']) ? $pivot['prev_quantity'] : 0;

                if ($accumulatedQuantity - $prevQuantity + $quantity > $totalQuantity)
                {
                    throw new \Exception('Số Lượng lũy kế + Số Lượng phải nhỏ hơn Số Lượng dự trù tổng.');
                }
            }

            if ($inputs['status'] === CommonStatus::APPROVED)
            {
                $accumulatedQuantity += $quantity;
            }

            return [
                'type'                 => $pivot['type'],
                'total_quantity'       => $totalQuantity,
                'accumulated_quantity' => $accumulatedQuantity,
                'quantity'             => $quantity,

                'batch1' => isset($pivot['batch1']) ? Carbon::createFromFormat('d/m/Y', $pivot['batch1'])->format('Y-m-d H:i:s') : null,
                'batch2' => isset($pivot['batch2']) ? Carbon::createFromFormat('d/m/Y', $pivot['batch2'])->format('Y-m-d H:i:s') : null,
                'batch3' => isset($pivot['batch3']) ? Carbon::createFromFormat('d/m/Y', $pivot['batch3'])->format('Y-m-d H:i:s') : null,
                'batch4' => isset($pivot['batch4']) ? Carbon::createFromFormat('d/m/Y', $pivot['batch4'])->format('Y-m-d H:i:s') : null,
                'batch5' => isset($pivot['batch5']) ? Carbon::createFromFormat('d/m/Y', $pivot['batch5'])->format('Y-m-d H:i:s') : null,
                'batch6' => isset($pivot['batch6']) ? Carbon::createFromFormat('d/m/Y', $pivot['batch6'])->format('Y-m-d H:i:s') : null,

                'quantity1' => $quantity1,
                'quantity2' => $quantity2,
                'quantity3' => $quantity3,
                'quantity4' => $quantity4,
                'quantity5' => $quantity5,
                'quantity6' => $quantity6,
            ];
        })->toArray();

        list($inputs, $pivotData) = $this->postSmooth($inputs, $pivotData);

        // foreach ($pivotData as $pivotRow)
        // {
        //     if ($pivotRow['quantity'] + $pivotRow['accumulated_quantity'] > $pivotRow['total_quantity'])
        //     {
        //         throw new \Exception('Số lượng lũy kế + số lượng không phải lớn hơn số lượng dự trù tổng.');
        //     }
        // } 

        return [$inputs, $pivotData, $pivotName];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'device_monthly_estimates');
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'device_monthly_estimates');
    }

    public function getNoticeBody($attributes)
    {
        return 'Dự trù thiết bị tháng bị đổi: ' . $attributes['name'] . ' [' . $attributes['code'] . ']';
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
            $query = $query->where('project_id', $params['search_option']['current_project_id']);
        }

        return $query->apiPaginate($params['per_page'] ?? null);
    }
}
