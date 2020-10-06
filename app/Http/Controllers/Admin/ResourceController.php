<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class ResourceController extends BaseController
{
    public function index()
    {
        if (!auth()->user()->can('resources.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.resources.index');
    }

    public function create()
    {
        if (!auth()->user()->can('resources.create')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.resources.create');
    }

    public function edit(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('resources.update')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.resources.create', compact('id'));
    }
}
