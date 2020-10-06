<?php

namespace App\Services\Api;

use App\Models\DevicePurchaseRequest as Model;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Enums\CommonStatus;

class DevicePurchaseRequestService extends BaseService
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
                    ->orWhere('name', 'like', '%' . $searchOption['keyword'] . '%');
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

        if (isset($searchOption['filter_progress']))
        {
            $query = $query->where('progress', '<', 100);
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
                'estimated_quantity'   => isset($pivot['estimated_quantity'])   ? $pivot['estimated_quantity'] : 0,
                'quantity'             => isset($pivot['quantity'])             ? $pivot['quantity']           : 0,
                'required_return_date' => isset($pivot['required_return_date']) ? Carbon::createFromFormat('d/m/Y', $pivot['required_return_date'])->format('Y-m-d H:i:s') : null,
                'note'                 => isset($pivot['note'])                 ? $pivot['note']               : '',
            ];
        })->toArray();

        list($inputs, $pivotData) = $this->postSmooth($inputs, $pivotData);

        foreach ($pivotData as $pivotRow)
        {
            if ($pivotRow['quantity'] > $pivotRow['estimated_quantity'])
            {
                throw new \Exception('Số lượng phải nhỏ hơn số lượng dự trù.');
            }
        } 

        return [$inputs, $pivotData, $pivotName];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'device_purchase_request');
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'device_purchase_request');
    }

    public function getNoticeBody($attributes)
    {
        return 'Yêu cầu mua mới thiết bị bị đổi: ' . $attributes['name'] . ' [' . $attributes['code'] . ']';
    }

    public function getListSelect2($params, $select = [])
    {
        $query = $this->model;

        if ($select) 
        {
            $query = $query->select($select);
        }

        if (isset($params['search_option']) && $params['search_option']['for_device_input'] && $params['search_option']['current_project_id'])
        {
            $query = $query->where('project_id', '=', $params['search_option']['current_project_id']);
        }
        else
        {
            $query = $query->where('progress', '<', 100);
        }

        return $query->apiPaginate($params['per_page'] ?? null);
    }
}
