<?php

namespace App\Services\Api;

use App\Models\DeviceRental as Model;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Enums\CommonStatus;

class DeviceRentalService extends BaseService
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

        $inputs = $this->preSmooth($inputs, $pivotName, $fields);

        $pivotData = collect($inputs[$pivotName])->keyBy('id')->map(function ($pivot) {

            return [
                'quantity'   => isset($pivot['quantity'])   ? $pivot['quantity']   : 0,
                'unit_price' => isset($pivot['unit_price']) ? $pivot['unit_price'] : 0,
                'start_date' => Carbon::createFromFormat('d/m/Y', $pivot['start_date'])->format('Y-m-d'),
                'end_date'   => Carbon::createFromFormat('d/m/Y', $pivot['end_date'])->format('Y-m-d'),
                'days_used'  => isset($pivot['days_used'])  ? $pivot['days_used']  : 0,
            ];
        })->toArray();

        list($inputs, $pivotData) = $this->postSmooth($inputs, $pivotData);

        return [$inputs, $pivotData, $pivotName];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'device_rental');
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'device_rental');
    }

    public function getNoticeBody($attributes)
    {
        return 'Cho thuê thiết bị bị đổi: ' . $attributes['name'] . ' [' . $attributes['code'] . ']';
    }
}
