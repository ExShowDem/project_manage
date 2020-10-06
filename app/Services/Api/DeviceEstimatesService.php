<?php

namespace App\Services\Api;

use App\Models\DeviceEstimate as Model;
use App\Services\BaseService;
use Carbon\Carbon;

class DeviceEstimatesService extends BaseService
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

        $fields['quantity'] = 'mass';

        $inputs = $this->preSmooth($inputs, $pivotName, $fields);

        $pivotData = collect($inputs[$pivotName])->keyBy('id')->map(function ($pivot) {

            if (!isset($pivot['input_time']))
            {
                throw new \Exception('Bạn Chưa Nhập ngày dự trù cấp.');
            }

            if (!isset($pivot['return_time']))
            {
                throw new \Exception('Bạn Chưa Nhập ngày dự trù trả.');
            }

            return [
                'mass'        => isset($pivot['mass'])       ? $pivot['mass']       : 0,
                'mass1'       => isset($pivot['mass1'])      ? $pivot['mass1']      : 0,
                'price'       => isset($pivot['price'])      ? $pivot['price']      : 0,
                'rent'        => isset($pivot['rent'])       ? $pivot['rent']       : 0,
                'rent_price'  => isset($pivot['rent_price']) ? $pivot['rent_price'] : 0,
                'mass2'       => isset($pivot['mass2'])      ? $pivot['mass2']      : 0,

                'estimated_unit_price' => isset($pivot['estimated_unit_price']) ? $pivot['estimated_unit_price'] : 0,

                'input_time'  => Carbon::createFromFormat('d/m/Y', $pivot['input_time'])->format('Y-m-d H:i:s'),
                'return_time' => Carbon::createFromFormat('d/m/Y', $pivot['return_time'])->format('Y-m-d H:i:s'),

                'days_used'   => isset($pivot['days_used'])   ? $pivot['days_used']   : 0,
                'total_price' => isset($pivot['total_price']) ? $pivot['total_price'] : 0,
                'note'        => isset($pivot['note'])        ? $pivot['note']        : '',
            ];
        })->toArray();

        list($inputs, $pivotData) = $this->postSmooth($inputs, $pivotData);

        return [$inputs, $pivotData, $pivotName];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'device_estimates');
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'device_estimates');
    }

    public function getNoticeBody($attributes)
    {
        return 'Dự trù thiết bị tổng bị đổi: ' . $attributes['name'] . ' [' . $attributes['code'] . ']';
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
