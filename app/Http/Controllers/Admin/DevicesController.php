<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class DevicesController extends BaseController
{
    public function index()
    {
        if (!auth()->user()->can('devices.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.devices.index');
    }

    public function create()
    {
        if (!auth()->user()->can('devices.create')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.devices.create');
    }

    public function edit(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('devices.update')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.devices.create', compact('id'));
    }
}
