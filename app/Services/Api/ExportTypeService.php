<?php

namespace App\Services\Api;

use App\Models\ExportType;
use App\Services\BaseService;

class ExportTypeService extends BaseService
{
    public function __construct(ExportType $exportType)
    {
        $this->model = $exportType;
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
