<?php

namespace App\Services\Api;

use App\Models\DeviceMonthlyEstimateType;
use App\Services\BaseService;

class DeviceMonthlyEstimateTypeService extends BaseService
{
    public function __construct(DeviceMonthlyEstimateType $deviceMonthlyEstimateType)
    {
        $this->model = $deviceMonthlyEstimateType;
    }

    public function getListSelect2($params, $select = [])
    {
        $query = $this->model;

        if ($select) 
        {
            $query = $query->select($select);
        }

        return $query->apiPaginate($params['per_page'] ?? null);
    }
}
