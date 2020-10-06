<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class CategorySuppliesController extends BaseController
{
    public function index()
    {
        if (!auth()->user()->can('category_supplies.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.supplies.category_supplies.index');
    }

    public function create()
    {
        if (!auth()->user()->can('category_supplies.create')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.supplies.category_supplies.create');
    }

    public function edit(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('category_supplies.update')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.supplies.category_supplies.create', compact('id'));
    }
}
