<?php

namespace App\Services\Api;

use App\Models\CategorySupplies;
use App\Services\BaseService;

class CategorySupplyService extends BaseService
{
    public function __construct(CategorySupplies $categorySupplies)
    {
        $this->model = $categorySupplies;
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

    public function smoothDataBeforeSave($inputs, $fields = [])
    {
        $pivotName = getPivotName($inputs, 'input');

        return [$inputs, [], $pivotName];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'category_supply');
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'category_supply');
    }

    public function getNoticeBody($attributes)
    {
        return 'Nhóm vật tư bị đổi: ' . $attributes['name'] . ' [' . $attributes['code'] . ']';
    }

    public function getSpecNoticeUrl($attributes, $urlKey, $id)
    {
        return route($urlKey . '.edit', ['projectId' => $attributes['project_id'], 'id' => $id]);
    }

    public function getList($params, $relations = [])
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

        if (isset($searchOption['keyword'])) 
        {
            $query = $query->whereNested(function($q) use ($searchOption) {
                $q
                    ->where('name', 'like', '%' . $searchOption['keyword'] . '%')
                    ->orWhere('code', 'like', '%' . $searchOption['keyword'] . '%');
            });
        }

        $query->orderBy('created_at', 'desc');

        return $query = $query->with($relations);
    }
}
