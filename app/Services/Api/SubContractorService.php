<?php

namespace App\Services\Api;

use App\Models\Subcontractor as Model;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class SubContractorService extends BaseService
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getListSubContractor($params)
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
                    ->where('name', 'like', '%' . $searchOption['keyword'] . '%')
                    ->orWhere('type', 'like', '%' . $searchOption['keyword'] . '%')
                    ->orWhere('code', 'like', '%' . $searchOption['keyword'] . '%')
                    ->orWhere('tax_code', 'like', '%' . $searchOption['keyword'] . '%')
                    ->orWhere('representative', 'like', '%' . $searchOption['keyword'] . '%');
            });
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function smoothDataBeforeSave($inputs, $fields = [])
    {
        return [$inputs, [], getPivotName($inputs, 'input')];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'sub_contractor');
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'sub_contractor');
    }

    public function getNoticeBody($attributes)
    {
        return 'Danh sách nhà thầu phụ bị đổi: ' . $attributes['name'] . ' [' . $attributes['code'] . ']';
    }

    public function getListSelect2($params, $select = [])
    {
        $query = $this->model;

        if ($select) 
        {
            $query = $query->select($select);
        }

        if (isset($params['search_option']))
        {
            $searchOption = $params['search_option'];
        }

        if (isset($searchOption['keyword'])) 
        {
            $query = $query->where('name', 'like', '%' . $searchOption['keyword'] . '%');
        }

        return $query->apiPaginate($params['per_page'] ?? null);
    }
}
