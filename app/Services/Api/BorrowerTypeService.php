<?php

namespace App\Services\Api;

use App\Models\BorrowerType;
use App\Services\BaseService;

class BorrowerTypeService extends BaseService
{
    public function __construct(BorrowerType $borrowerType)
    {
        $this->model = $borrowerType;
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
