<?php

namespace App\Services\Api;

use App\Models\ResourceType;
use App\Services\BaseService;
use Illuminate\Support\Facades\Log;

class ResourceTypeService extends BaseService
{
    public function __construct(ResourceType $resourceType)
    {
        $this->model = $resourceType;
    }

    public function getListItems($params)
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

        $query->orderBy('created_at', 'desc');

        return $query;
    }

    public function smoothDataBeforeSave($inputs, $fields = [])
    {
        $pivotName = getPivotName($inputs, 'input');

        return [$inputs, [], $pivotName];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'resource_types');
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'resource_types');
    }

    public function getNoticeBody($attributes)
    {
        return 'Loại nguồn lực bị đổi: ' . $attributes['name'];
    }

    public function getSpecNoticeUrl($attributes, $urlKey, $id)
    {
        return route($urlKey . '.edit', ['projectId' => $attributes['project_id'], 'id' => $id]);
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
