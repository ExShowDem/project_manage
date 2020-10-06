<?php

namespace App\Services\Api;

use App\Models\DeviceInput as Model;
use App\Services\BaseService;

class DeviceInputService extends BaseService
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getListItems($params)
    {
        $query = $this->model;
        
        $query = $query->where('project_id', $params['project_id']);

        if (isset($params['search_option']))
        {
            $searchOption = $params['search_option'];
        }

        if (isset($searchOption['keyword'])) 
        {
            $query = $query->whereNested(function($q) use ($searchOption) {
                $q
                    ->where('code', 'like', '%' . $searchOption['keyword'] . '%')
                    ->orWhere('company', 'like', '%' . $searchOption['keyword'] . '%');
            });
        }

        if (isset($searchOption['from_created_date']) && isset($searchOption['till_created_date'])) 
        {
            $query = $query
                ->where('created_date', '>=', carbon_date($searchOption['from_created_date'])->startOfDay())
                ->where('created_date', '<=', carbon_date($searchOption['till_created_date'])->endOfDay());
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
            'field'      => 'created_date', 
            'fromFormat' => 'd/m/Y', 
            'toFormat'   => 'Y-m-d H:i:s', 
        ];

        $inputs = $this->preSmooth($inputs, $pivotName, $fields);

        $pivotData = collect($inputs[$pivotName])->keyBy('id')->map(function ($pivot) {
            return [
                'quantity'   => isset($pivot['quantity'])   ? $pivot['quantity']   : 0,
                'existing_quantity'   => isset($pivot['existing_quantity'])   ? $pivot['existing_quantity']   : 0,
                'unit_price' => isset($pivot['unit_price']) ? $pivot['unit_price'] : 0,
                'note'       => isset($pivot['note'])       ? $pivot['note']       : '',
            ];
        })->toArray();

        list($inputs, $pivotData) = $this->postSmooth($inputs, $pivotData);

        return [$inputs, $pivotData, $pivotName];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'device_input');
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'device_input');
    }

    public function getNoticeBody($attributes)
    {
        return 'Nhập thiết bị bị đổi: [' . $attributes['code'] . ']';
    }
}
