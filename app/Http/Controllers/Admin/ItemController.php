<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends BaseController
{
    public function index()
    {
        if (!auth()->user()->can('items.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.items.index');
    }

    public function create()
    {
        if (!auth()->user()->can('items.create')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $code = generate_code_for_model(new Item);
        $canApprove = (int)auth()->user()->can('items.approve');

        return view('admin.items.create', compact('code', 'canApprove'));
    }

    public function edit(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('items.update')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $canApprove = (int)auth()->user()->can('items.approve');

        return view('admin.items.create', compact('id', 'canApprove'));
    }

    public function tracking($projectId, $id)
    {
        return view('admin.items.tracking', compact('id'));
    }

    public function trackingDetail($projectId, $id, $log_id)
    {
        return view('admin.items.tracking_detail', compact('id', 'log_id'));
    }

    public function show(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('items.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $canApprove = (int)auth()->user()->can('items.approve');

        $isShow = true;

        return view('admin.items.create', compact('id', 'canApprove', 'isShow'));
    }
}
