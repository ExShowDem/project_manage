<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;

class RoleController extends BaseController
{
    private $permissions;

    public function __construct(Permission $permissionModel)
    {
        parent::__construct();
        $layout = 'root';

        if (request()->projectId) {
            $layout = 'admin';
        }

        view()->share('layout', $layout);

        $permissions = config('permission.features');
        $permissionModel->includeProjects($permissions);
        $this->permissions = $permissions;
    }

    public function index()
    {

        if (!auth()->user()->can('roles.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('root.roles.index');
    }

    public function create()
    {
        if (!auth()->user()->can('roles.create')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $permissions = $this->permissions;

        return view('root.roles.form', compact('permissions'));
    }

    public function edit($projectId, $id = null)
    {
        $id = $id ? $id : $projectId;

        if (!auth()->user()->can('roles.update')) 
        {
            return redirect(route('admin.error')); //abort(403);
        }

        $permissions = $this->permissions;

        return view('root.roles.form', compact('id', 'permissions'));
    }
}
