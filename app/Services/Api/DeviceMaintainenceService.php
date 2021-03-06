<?php

namespace App\Services\Api;

use App\Models\DeviceMaintainence as Model;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Enums\CommonStatus;

class DeviceMaintainenceService extends BaseService
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

        if (isset($searchOption['keyword'])) {
            $query = $query->whereNested(function($q) use ($searchOption) {
                $q
                ->where('code', 'like', '%' . $searchOption['keyword'] . '%')
                ->orWhere('name', 'like', '%' . $searchOption['keyword'] . '%')
                ->orWhere('result', 'like', '%' . $searchOption['keyword'] . '%');
            });
        }

        if (isset($searchOption['from_date']) && isset($searchOption['till_date'])) {
            $query = $query
                ->where('date', '>=', carbon_date($searchOption['from_date'])->startOfDay())
                ->where('date', '<=', carbon_date($searchOption['till_date'])->endOfDay());
        }

        if (isset($searchOption['project'])) {
            $query = $query->where('project_id', '=', $searchOption['project']);
        }

        if (isset($searchOption['creator'])) {
            $query = $query->where('creator_id', '=', $searchOption['creator']);
        }

        if (isset($searchOption['status']))
        {
            $query = $query
                ->where('status', '=', $searchOption['status']);
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

        $pivotData = collect($inputs[$pivotName])->keyBy('id')->map(function ($pivot) {

            return [
                'quantity'   => isset($pivot['quantity'])   ? $pivot['quantity']   : 0,
                'unit_price' => isset($pivot['unit_price']) ? $pivot['unit_price'] : 0,
            ];
        })->toArray();

        list($inputs, $pivotData) = $this->postSmooth($inputs, $pivotData);

        return [$inputs, $pivotData, $pivotName];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'device_maintainence');
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'device_maintainence');
    }

    public function getNoticeBody($attributes)
    {
        return 'Bảo trì, sửa chữa thiết bị bị đổi: ' . $attributes['name'] . ' [' . $attributes['code'] . ']';
    }
}
