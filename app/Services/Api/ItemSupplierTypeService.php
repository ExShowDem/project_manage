<?php

namespace App\Services\Api;

use App\Models\ItemSupplierType;
use App\Services\BaseService;

class ItemSupplierTypeService extends BaseService
{
    public function __construct(ItemSupplierType $itemSupplierType)
    {
        $this->model = $itemSupplierType;
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
