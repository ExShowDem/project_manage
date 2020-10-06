<?php

namespace App\Services\Api;

use App\Models\DeviceTransferDirection;
use App\Services\BaseService;

class DeviceTransferDirectionService extends BaseService
{
    public function __construct(DeviceTransferDirection $deviceTransferDirection)
    {
        $this->model = $deviceTransferDirection;
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
