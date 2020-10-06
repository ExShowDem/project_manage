<?php

namespace App\Services\Api;

use App\Models\Item as Model;
use App\Models\Supplies;
use App\Services\BaseService;
use App\Enums\ReceiptOutputStatus;
use Illuminate\Support\Facades\DB;

class ItemService extends BaseService
{
    public function __construct(Model $model, Supplies $supplies)
    {
        $this->model    = $model;
        $this->supplies = $supplies;
    }

    public function getListItems($params)
    {
        $query = $this->model;

        if (isset($params['project_id']) && $params['project_id']) 
        {
            $query = $query->where('items.project_id', $params['project_id']);
        }

        if (isset($params['search_option']))
        {
            $searchOption = $params['search_option'];
        }

        if (isset($searchOption['project_id'])) 
        {
            $query = $query->where('items.project_id', $searchOption['project_id']);
        }

        if (isset($searchOption['with_supplies'])) 
        {
            $query = $query->with('supplies');
        }

        if (isset($searchOption['keyword'])) 
        {
            $query = $query->whereNested(function($q) use ($searchOption) {
                $q
                    ->where('items.name', 'like', '%' . $searchOption['keyword'] . '%')
                    ->orWhere('items.code', 'like', '%' . $searchOption['keyword'] . '%');
            });
        }

        if (isset($searchOption['creator'])) 
        {
            $query = $query->where('items.created_by', '=', $searchOption['creator']);
        }

        if (isset($searchOption['created_from']) && isset($searchOption['created_till'])) 
        {
            $query = $query
                ->where('items.created_at', '>=', carbon_date($searchOption['created_from'])->startOfDay())
                ->where('items.created_at', '<=', carbon_date($searchOption['created_till'])->endOfDay());
        }

        if (isset($searchOption['status'])) 
        {
            $query = $query->where('items.status', '=', $searchOption['status']);
        }

        $query->orderBy('items.created_at', 'desc');

        return $query->with(['project', 'user']);
    }

    public function smoothDataBeforeSave($inputs, $fields = [])
    {
        $pivotName = getPivotName($inputs, 'input');

        $fields = [
            'status' => true,
            'dates'  => [],
        ];

        $fields['dates'][] = [
            'field'      => 'end_date', 
            'fromFormat' => 'd/m/Y', 
            'toFormat'   => 'Y-m-d H:i:s', 
        ];

        $inputs      = $this->preSmooth($inputs, $pivotName, $fields);
        $progressSum = 0;
        $supplyModel = resolve('App\Models\Supplies');

        $pivotData = collect($inputs[$pivotName])->keyBy('id')->map(function ($pivot) use ($inputs, $supplyModel) {

            $quantity = isset($pivot['quantity']) ? (float) $pivot['quantity'] : 0;
            $numerator = (float) $supplyModel->getInputCumulative($pivot['id'], $inputs['id'], $inputs['project_id']);
            $denominator = $quantity;
            $progress = $denominator ? ($numerator/$denominator) * 100 : 0;

            return [
                'quantity'          => $quantity,
                'unit_price_budget' => isset($pivot['unit_price_budget']) ? $pivot['unit_price_budget'] : 0,
                'type'              => isset($pivot['type'])              ? $pivot['type']              : null,
                'total'             => isset($pivot['total'])             ? $pivot['total']             : 0,
                'is_vlk'            => $pivot['is_vlk'],
                'progress'          => $progress,
            ];
        })->toArray();

        foreach ($pivotData as $supply) 
        {
            $progressSum += (float) $supply['progress'];
        }
        $inputs['progress'] = $progressSum/count($pivotData);

        list($inputs, $pivotData) = $this->postSmooth($inputs, $pivotData);

        return [$inputs, $pivotData, $pivotName];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'item');
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'item');
    }

    public function updateSpec(&$entry, &$inputs, &$pivotData)
    {
        $inputs['created_by'] = auth()->id();
    }

    public function getNoticeBody($attributes)
    {
        return 'Vật tư bị đổi: ' . $attributes['name'] . ' [' . $attributes['code'] . ']';
    }

    public function getCumulativeQuantity($supplyId, $itemId, $projectId)
    {
        return $this->supplies->getCumulativeQuantity($supplyId, $itemId, $projectId);
    }
}
