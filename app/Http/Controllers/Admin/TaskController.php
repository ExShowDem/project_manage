<?php

namespace App\Http\Controllers\Admin;

class TaskController extends BaseController
{
    public function index()
    {

        if (!auth()->user()->can('tasks.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.tasks.index');
    }

    public function show($projectId, $id)
    {
        if (!auth()->user()->can('tasks.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.tasks.show', compact('id'));
    }
}
