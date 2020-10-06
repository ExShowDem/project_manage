<?php

use App\Models\RoleTree;

function get_child_roles($ownRoleIds)
{
    $parentTree      = RoleTree::pluck('parent_id', 'role_id')->all();
    $childrenTree    = array_flip($parentTree);
    $childrenRoleIds = [];

    foreach ($childrenTree as $k => $v)
    {
        $childrenTree[$k] = array_keys($parentTree, $k);
    }

    foreach ($ownRoleIds as $roleId) 
    {
        $childrenRoleIds = array_merge($childrenRoleIds, get_child_roles_recursive($roleId, $childrenTree));
    }

    return $childrenRoleIds;
}

function get_child_roles_recursive($roleId, $childrenTree)
{
    if (isset($childrenTree[$roleId]))
    {
        $childRoleIds = [];

        foreach ($childrenTree[$roleId] as $childRoleId) 
        {
            $childRoleIds = array_merge($childRoleIds, [$childRoleId], get_child_roles_recursive($childRoleId, $childrenTree));
        }

        return $childRoleIds;
    }
    else
    {
        return [];
    }
}