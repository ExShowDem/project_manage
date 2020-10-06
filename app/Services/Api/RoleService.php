<?php

namespace App\Services\Api;

use App\Models\Role;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleService extends BaseService
{
    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    public function smoothDataBeforeSave($inputs, $fields = [])
    {
        $pivotName = 'permissions';
        $pivotData = $inputs['permissions'];

        $inputs = [
            'name' => $inputs['name']
        ];

        return [$inputs, $pivotData, $pivotName];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'role');
    }

    public function createSpec(&$entry, &$inputs, &$pivotData)
    {
        $attributes = $entry->getAttributes();

        DB::table('role_tree')->insert(['role_id' => $attributes['id'], 'parent_id' => 0]);
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'role');
    }

    public function getNoticeBody($attributes)
    {
        return 'Vai trò bị đổi: ' . $attributes['name'];
    }

    public function getSpecNoticeUrl($attributes, $urlKey, $id)
    {
        return route($urlKey . '.edit', ['projectId' => $attributes['project_id'], 'id' => $id]);
    }

    public function getList($params)
    {
        $query = $this->model;

        $query->orderBy('created_at', 'desc');

        return $query->with('roleTree');
    }
}
