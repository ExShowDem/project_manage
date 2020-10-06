<?php

namespace App\Services\Api;

use App\Enums\SupplierType;
use App\Models\Supplier as Model;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class SupplierService extends BaseService
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getListSuppliers($params)
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
                    ->orWhere('email', 'like', '%' . $searchOption['keyword'] . '%')
                    ->orWhere('phone_number', 'like', '%' . $searchOption['keyword'] . '%')
                    ->orWhere('address', 'like', '%' . $searchOption['keyword'] . '%');
            });
        }

        if (isset($searchOption['date_from']) && isset($searchOption['date_till'])) 
        {
            $query = $query
                ->where('created_at', '>=', carbon_date($searchOption['date_from'])->startOfDay())
                ->where('created_at', '<=', carbon_date($searchOption['date_till'])->endOfDay());
        }

        $query = $query->where('type', $params['type'])->with(['project']);

        return $query->orderBy('created_at', 'desc');
    }

    public function smoothDataBeforeSave($inputs, $fields = [])
    {
        return [$inputs, [], getPivotName($inputs, 'input')];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'supplier');
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'supplier');
    }

    public function getNoticeBody($attributes)
    {
        return 'Nhà cung cấp bị đổi: [' . $attributes['name'] . ']';
    }

    public function getSpecNoticeUrl($attributes, $urlKey, $id)
    {
        $type = ($attributes['type'] == 1)  ? 'supplier' : 'customer';
        return route($urlKey . '.edit', ['projectId' => $attributes['project_id'], 'type' => $type, 'id' => $id]);
    }

    public function getListSelect2($params, $select = [], $supplierType = null)
    {
        $query = $this->model;

        if ($select) 
        {
            $query = $query->select($select);
        }

        if (!is_null($supplierType))
        {
            $query = $query->where('type', $supplierType);
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
