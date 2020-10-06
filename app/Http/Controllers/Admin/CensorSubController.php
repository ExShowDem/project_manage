<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class CensorSubController extends BaseController
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('censor_sub.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.censor_sub.index');
    }

    public function create(Request $request)
    {
        if (!auth()->user()->can('censor_sub.create')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.censor_sub.form');
    }

    public function edit(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('censor_sub.update')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.censor_sub.form', compact('id'));
    }

    public function tracking($projectId, $id)
    {
        return view('admin.censor_sub.tracking', compact('id'));
    }

    public function trackingDetail($projectId, $id, $log_id)
    {
        return view('admin.censor_sub.tracking_detail', compact('log_id'));
    }

    public function show(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('censor_sub.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $isShow = true;

        return view('admin.censor_sub.form', compact('id', 'isShow'));
    }
}
