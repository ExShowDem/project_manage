<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class SuppliesController extends BaseController
{
    public function index()
    {
        if (!auth()->user()->can('supplies.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.supplies.index');
    }

    public function create()
    {
        //echo
        if (!auth()->user()->can('supplies.create')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.supplies.create');
    }

    public function edit(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('supplies.update')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.supplies.create', compact('id'));
    }
}
