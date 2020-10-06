<?php

namespace App\Services\Api;

use App\Models\ReceiverType;
use App\Services\BaseService;

class ReceiverTypeService extends BaseService
{
    public function __construct(ReceiverType $receiverType)
    {
        $this->model = $receiverType;
    }

    public function getListSelect2($params, $select = [])
    {
        $query = $this->model;

        if ($select) {
            $query = $query->select($select);
        }

        if (isset($params['search_option']['keyword'])) {
            $query = $query->where('name', 'like', '%' . $params['search_option']['keyword'] . '%');
        }

        return $query->apiPaginate($params['per_page'] ?? null);
    }
}
