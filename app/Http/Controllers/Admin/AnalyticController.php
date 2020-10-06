<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class AnalyticController extends BaseController
{
    public function index()
    {
        if (!auth()->user()->can('projects.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('root.projects.index');
    }

    public function create()
    {
        if (!auth()->user()->can('projects.create')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('root.projects.form');
    }

    public function edit(Request $request, $id)
    {
        if (!auth()->user()->can('projects.update') || !auth()->user()->can('project_'.$id.'.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('root.projects.form', compact('id'));
    }
}
