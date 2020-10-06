<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class ContractSubController extends BaseController
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('contract_sub.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.contract_sub.index');
    }

    public function create(Request $request)
    {
        if (!auth()->user()->can('contract_sub.create')) {
            return redirect(route('admin.error')); //abort(403);
        }
        return view('admin.contract_sub.form');
    }

    public function edit(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('contract_sub.update')) {
            return redirect(route('admin.error')); //abort(403);
        }
        return view('admin.contract_sub.form', compact('id'));
    }

    public function tracking($projectId, $id)
    {
        return view('admin.contract_sub.tracking', compact('id'));
    }

    public function trackingDetail($projectId, $id, $log_id)
    {
        return view('admin.contract_sub.tracking_detail', compact('log_id'));
    }
    public function show(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('contract_sub.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $isShow = true;

        return view('admin.contract_sub.form', compact('id', 'isShow'));
    }
}
