<?php

namespace App\Services\Api;

use App\Models\DeviceRoundRobin as Model;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Enums\CommonStatus;

class DeviceRoundRobinService extends BaseService
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
            //$query = $query->where('project_id', $params['project_id']); //@todo Unsure whether to do this
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

        if (isset($searchOption['from_project'])) 
        {
            $query = $query->where('from_project_id', '=', $searchOption['from_project']);
        }

        if (isset($searchOption['to_project'])) 
        {
            $query = $query->where('to_project_id', '=', $searchOption['to_project']);
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
                'existing_quantity'   => isset($pivot['existing_quantity']) ? $pivot['existing_quantity'] : 0,
                'quantity'            => isset($pivot['quantity'])          ? $pivot['quantity']          : 0,
                'unit_price'          => isset($pivot['unit_price'])        ? $pivot['unit_price']        : 0,
                'note'                => isset($pivot['note'])              ? $pivot['note']              : '',
            ];
        })->toArray();

        list($inputs, $pivotData) = $this->postSmooth($inputs, $pivotData);

        foreach ($pivotData as $pivotRow)
        {
            if ($pivotRow['quantity'] > $pivotRow['existing_quantity'])
            {
                throw new \Exception('Số lượng phải nhỏ hơn số lượng đang có.');
            }
        } 

        return [$inputs, $pivotData, $pivotName];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'device_round_robin');
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'device_round_robin');
    }

    public function getNoticeBody($attributes)
    {
        return 'Luân chuyển thiết bị bị đổi: ' . $attributes['name'] . ' [' . $attributes['code'] . ']';
    }
}
