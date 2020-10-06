<?php

namespace App\Services\Api;

use App\Models\DeviceReturnToCompany as Model;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Enums\CommonStatus;

class DeviceCompanyService extends BaseService
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getListItems($params)
    {
        $query = $this->model;

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

        if (isset($searchOption['from_return_date']) && isset($searchOption['till_return_date'])) 
        {
            $query = $query
                ->where('return_date', '>=', carbon_date($searchOption['from_return_date'])->startOfDay())
                ->where('return_date', '<=', carbon_date($searchOption['till_return_date'])->endOfDay());
        }

        if (isset($searchOption['devicesToProject'])) 
        {
            $query = $query->where('devices_to_project_id', '=', $searchOption['devicesToProject']);
        }

        if (isset($searchOption['project'])) 
        {
            $query = $query->where('project_id', '=', $searchOption['project']);
        }

        if (isset($searchOption['user'])) 
        {
            $query = $query->where('user_id', '=', $searchOption['user']);
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
            'quantity'  => 'quantity_returned',
        ];

        $fields['dates'][] = [
            'field'      => 'return_date', 
            'fromFormat' => 'd/m/Y', 
            'toFormat'   => 'Y-m-d H:i:s', 
        ];

        $inputs = $this->preSmooth($inputs, $pivotName, $fields);

        $pivotData = collect($inputs[$pivotName])->keyBy('id')->map(function ($pivot) {

            return [
                'quantity'          => isset($pivot['quantity'])          ? $pivot['quantity']          : 0,
                'quantity_returned' => isset($pivot['quantity_returned']) ? $pivot['quantity_returned'] : 0,
                'unit_price'        => isset($pivot['unit_price'])        ? $pivot['unit_price']        : 0,
                'note'              => isset($pivot['note'])              ? $pivot['note']              : '',

            ];
        })->toArray();

        list($inputs, $pivotData) = $this->postSmooth($inputs, $pivotData);

        foreach ($pivotData as $pivotRow)
        {
            if ($pivotRow['quantity_returned'] > $pivotRow['quantity'])
            {
                throw new \Exception('Số lượng trả về phải nhỏ hơn số lượng chứng từ.');
            }
        } 

        return [$inputs, $pivotData, $pivotName];
    }


    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'device_company');
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'device_company');
    }

    public function getNoticeBody($attributes)
    {
        return 'Trả thiết bị về công ty bị đổi: [' . $attributes['code'] . ']';
    }
}
