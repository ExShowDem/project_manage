<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\RoleRequest;
use App\Services\Api\RoleService as Service;
use Illuminate\Http\Request;
use App\Http\Resources\RoleResource as Resource;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class RoleController extends BaseController
{
    protected $service;
    protected $module = 'roles';

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function store(RoleRequest $request)
    {
        $inputs = $request->all();

        return parent::genericSave($request, $inputs, 'create');
    }

    public function index(Request $request)
    {
        $params     = $request->only('per_page', 'search_option', 'current_page');
        $items      = Resource::apiPaginate($this->service->getList($params), $request);
        $linearData = [];

        foreach ($items->data as $role) 
        {
            $role->text     = $role->name;
            $role->value    = $role->name;
            $role->opened   = true;
            $role->icon     = "";
            $role->selected = false;
            $role->disabled = false;
            $role->loading  = false;
            $role->children = [];
            $role->role_id  = $role->id;

            $linearData[] = $role;
        }

        $items->data = $this->prepareTree($linearData);

        return $this->responseSuccess($items);
    }

    public function destroy($id)
    {
        $result = $this->service->delete($id);

        if ($result) 
        {
            return $this->responseSuccess();
        }

        return $this->responseError('api.code.common.delete_failed');
    }

    public function show($id)
    {
        $item = $this->service->find($id);

        if ($item)
        {
            return $this->responseSuccess(compact('item'));
        }
        else
        {
            return $this->responseError('api.code.common.show_failed');
        }
    }

    public function update(RoleRequest $request, $id)
    {

        //dd($request->all());
        $inputs = $request->all();

        return parent::genericSave($request, $inputs, 'update', $id);
    }

    public function rearrange(Request $request)
    {
        $inputs     = $request->all();
        $oldRoleIds = DB::table('role_tree')->pluck('role_id')->toArray();
        $newRoleIds = array_column($inputs, 'role_id');
        $diff       = array_diff($oldRoleIds, $newRoleIds);

        if (!empty($diff))
        {
            foreach ($diff as $roleId) 
            {
                $this->service->delete($roleId);
            }
        }

        DB::table('role_tree')->truncate();
        DB::table('role_tree')->insert($inputs);

        $updatedRoles = DB::table('role_tree')->get()->all();
        $treeData     = $this->prepareTree($updatedRoles);

        $this->updatePermission($treeData);
    }

    protected function prepareTree($data)
    {
        $parentId = array_column($data, 'parent_id');
        array_multisort($parentId, SORT_DESC, $data);

        foreach ($data as $key => $item)
        {
            if ($item->parent_id !== 0)
            {
                foreach ($data as $key1 => $item1) 
                {
                    if ($item->parent_id === $item1->role_id)
                    {
                        $data[$key1]->children[] = $item;
                    }
                }

                unset($data[$key]);
            }
        }

        $data = array_values($data);

        return $data;
    }

    protected function updatePermission($treeData)
    {
        if (count($treeData) > 1)
        { //branch with multiple children
            $childrensPermissionIds = [];

            foreach ($treeData as $branch) 
            {
                $thisChildsPermissionIds = $this->updatePermission([$branch]);
                $childrensPermissionIds  = array_unique(array_merge($childrensPermissionIds, $thisChildsPermissionIds));
            }

            return $childrensPermissionIds;
        }
        elseif (count($treeData) === 1)
        {
            if (empty($treeData[0]->children))
            { //leaf
                $permissionIds = DB::table('role_has_permissions')
                    ->where('role_id', $treeData[0]->role_id)
                    ->pluck('permission_id')
                    ->toArray();

                return $permissionIds;
            }
            else
            { //one child with children
                $childsPermissionIds = $this->updatePermission($treeData[0]->children);
                $roleId              = $treeData[0]->role_id;
                $ownPermissionIds    = DB::table('role_has_permissions')
                    ->where('role_id', $roleId)
                    ->pluck('permission_id')
                    ->toArray();

                $combinedPermissionIds = array_unique(array_merge($childsPermissionIds, $ownPermissionIds));
                $role                  = Role::find($roleId);
                
                $this->service->logPivotAction('sync', $role, 'permissions', $combinedPermissionIds);

                return $combinedPermissionIds;
            }
        }
        else
        {
            return [];
        }
    }
}
