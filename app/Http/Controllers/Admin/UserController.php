<?php

namespace App\Http\Controllers\Admin;

class UserController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $layout = 'root';

        if (request()->projectId) {
            $layout = 'admin';
        }

        view()->share('layout', $layout);
    }

    public function index()
    {
        if (!auth()->user()->can('users.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('root.users.index');
    }

    public function create()
    {
        if (!auth()->user()->can('users.create')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('root.users.form');
    }

    public function edit($projectId, $id = null)
    {
        if (!auth()->user()->can('users.update')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $id = $id ? $id : $projectId;

        return view('root.users.form', compact('id'));
    }
}
