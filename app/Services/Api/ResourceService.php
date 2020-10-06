<?php

namespace App\Services\Api;

use App\Models\Resource;
use App\Services\BaseService;

class ResourceService extends BaseService
{
    public function __construct(Resource $resource)
    {
        $this->model = $resource;
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

        if (isset($searchOption['exclude_ids'])) 
        {
            $query = $query->whereNotIn('id', $searchOption['exclude_ids']);
        }

        return $query->apiPaginate($params['per_page'] ?? null);
    }

    public function getList($params, $relations = [])
    {
        $query = $this->model;

        if (isset($params['search_option']))
        {
            $searchOption = $params['search_option'];
        }

        if (isset($searchOption['keyword'])) 
        {
            $query = $query->where('name', 'like', '%' . $searchOption['keyword'] . '%');
        }

        if (isset($searchOption['unit'])) 
        {
            $query = $query->where('unit_id', '=', $searchOption['unit']);
        }

        if (isset($searchOption['resource'])) 
        {
            $query = $query->where('resource_type_id', '=', $searchOption['resource']);
        }

        $query->orderBy('created_at', 'desc');

        return $query = $query->with($relations);
    }

    public function smoothDataBeforeSave($inputs, $fields = [])
    {
        $pivotName = getPivotName($inputs, 'input');

        return [$inputs, [], $pivotName];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'resources');
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'resources');
    }

    public function getNoticeBody($attributes)
    {
        return 'Nguồn lực bị đổi: ' . $attributes['name'];
    }

    public function getSpecNoticeUrl($attributes, $urlKey, $id)
    {
        return route($urlKey . '.edit', ['projectId' => $attributes['project_id'], 'id' => $id]);
    }
}
