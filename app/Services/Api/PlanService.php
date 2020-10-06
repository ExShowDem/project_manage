<?php

namespace App\Services\Api;

use App\Models\Plan as Model;
use App\Services\BaseService;

class PlanService extends BaseService
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getPlanSupplies($params)
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

        if (isset($searchOption['start_date']) && isset($searchOption['end_date']))
        {
            $query = $query
                ->where('start_time', '>=', carbon_date($searchOption['start_date'])->startOfDay())
                ->where('end_time', '<=', carbon_date($searchOption['end_date'])->endOfDay());
        }

        if (isset($searchOption['status']))
        {
            $query = $query->where('status', '=', $searchOption['status']);
        }

        if (isset($searchOption['keyword'])) 
        {
            $query = $query->whereNested(function($q) use ($searchOption) {
                $q
                    ->where('name', 'like', '%' . $searchOption['keyword'] . '%')
                    ->orWhere('code', 'like', '%' . $searchOption['keyword'] . '%');
            });
        }

        $query->orderBy('id', 'desc');

        return $query->with('project');
    }

    public function smoothDataBeforeSave($inputs, $fields = [])
    {
        $pivotName = getPivotName($inputs, 'input');

        $fields = [
            'status' => true,
            'dates'  => [],
        ];

        $inputs = $this->preSmooth($inputs, $pivotName, $fields);

        $pivotData = collect($inputs[$pivotName])->keyBy('id')->map(function ($pivot) {
            return [
                'quantity'          => isset($pivot['quantity'])          ? $pivot['quantity']                  : 0,
                'unit_price_budget' => isset($pivot['unit_price_budget']) ? $pivot['unit_price_budget']         : 0,
                'date_arrival'      => isset($pivot['date_arrival'])      ? carbon_date($pivot['date_arrival']) : null,
            ];
        })->toArray();

        list($inputs, $pivotData) = $this->postSmooth($inputs, $pivotData);

        return [$inputs, $pivotData, $pivotName];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'plan');
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'plan');
    }

    public function getNoticeBody($attributes)
    {
        return 'Kế hoạch bị đổi: ' . $attributes['name'] . ' [' . $attributes['code'] . ']';
    }
}
