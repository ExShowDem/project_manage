<?php

namespace App\Services\Api;

use App\Models\DeviceTransfer as Model;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Enums\CommonStatus;

class DeviceTransferService extends BaseService
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
            $query = $query->where('name', 'like', '%' . $searchOption['keyword'] . '%');
        }

        if (isset($searchOption['from_date']) && isset($searchOption['till_date'])) 
        {
            $query = $query
                ->where('created_at', '>=', carbon_date($searchOption['from_date'])->startOfDay())
                ->where('created_at', '<=', carbon_date($searchOption['till_date'])->endOfDay());
        }

        if (isset($searchOption['issuance'])) 
        {
            $query = $query->where('device_issuance_id', '=', $searchOption['issuance']);
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

        $inputs = $this->preSmooth($inputs, $pivotName, $fields);

        $pivotData = collect($inputs[$pivotName])->keyBy('id')->map(function ($pivot) {

            if (!isset($pivot['from_project']))
            {
                throw new \Exception('Bạn Chưa Nhập Nơi xuất.');
            }

            if (!isset($pivot['to_project']))
            {
                throw new \Exception('Bạn Chưa Nhập Nơi đến.');
            }

            $quantity = isset($pivot['quantity'])        ? $pivot['quantity']        : 0;
            $existingQuantity = isset($pivot['existing_quantity']) ? $pivot['existing_quantity'] : 0;

            if ($quantity > $existingQuantity)
            {
                throw new \Exception('Số lượng phải nhỏ hơn SL đang có.');
            }

            return [
                'issued_quantity' => isset($pivot['issued_quantity']) ? $pivot['issued_quantity'] : 0,
                'quantity'        => $quantity,
                'existing_quantity' => $existingQuantity,
                'carrier_type'    => isset($pivot['carrier_type'])    ? $pivot['carrier_type']    : '',
                'carrier_number'  => isset($pivot['carrier_number'])  ? $pivot['carrier_number']  : '',
                'transfer_unit'   => isset($pivot['transfer_unit'])   ? $pivot['transfer_unit']   : '',
                'from_project'    => $pivot['from_project'],
                'to_project'      => $pivot['to_project'],

                'sent'    => isset($pivot['sent'])    ? Carbon::createFromFormat('d/m/Y', $pivot['sent'])->format('Y-m-d H:i:s') : null,
                'arrived' => isset($pivot['arrived']) ? Carbon::createFromFormat('d/m/Y', $pivot['arrived'])->format('Y-m-d H:i:s') : null,
            ];
        })->toArray();

        list($inputs, $pivotData) = $this->postSmooth($inputs, $pivotData);

        foreach ($pivotData as $pivotRow)
        {
            if ($pivotRow['quantity'] > $pivotRow['issued_quantity'])
            {
                throw new \Exception('Số lượng phải nhỏ hơn số lượng đề nghị.');
            }
        } 

        return [$inputs, $pivotData, $pivotName];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'device_transfer');
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'device_transfer');
    }

    public function getNoticeBody($attributes)
    {
        return 'Kế hoạch điều chuyển thiết bị bị đổi: ' . $attributes['name'];
    }
}
