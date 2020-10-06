<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
class SubContractorController extends BaseController
{
    public function index(Request $request)
    {

        if (!auth()->user()->can('sub_contractors.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.sub_contractor.index');
    }

    public function create(Request $request)
    {

        if (!auth()->user()->can('sub_contractors.create')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.sub_contractor.form');
    }

    public function edit(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('sub_contractors.update')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.sub_contractor.form', compact('id'));
    }

    public function tracking($projectId, $id)
    {
        return view('admin.sub_contractor.tracking', compact('id'));
    }

    public function trackingDetail($projectId, $id, $log_id)
    {
        return view('admin.sub_contractor.tracking_detail', compact('log_id'));
    }

    public function show(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('sub_contractors.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $isShow = true;

        return view('admin.sub_contractor.form', compact('id', 'isShow'));
    }
}
