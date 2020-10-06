<?php

namespace App\Http\Controllers\Admin;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends BaseController
{
    public function planSupplies()
    {
        if (!auth()->user()->can('plans.supplies.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.plans.supplies.index');
    }

    public function create()
    {
        if (!auth()->user()->can('plans.supplies.create')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $code = generate_code_for_model(new Plan);
        $canApprove = (int) auth()->user()->can('plans.supplies.approve');

        return view('admin.plans.supplies.create', compact('code', 'canApprove'));
    }

    public function editPlanSupplies(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('plans.supplies.update')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $canApprove = (int) auth()->user()->can('plans.supplies.approve');

        return view('admin.plans.supplies.create', compact('id', 'canApprove'));
    }

    public function tracking($projectId, $id)
    {
        return view('admin.plans.supplies.tracking', compact('id'));
    }

    public function trackingDetail($projectId, $id, $log_id)
    {
        return view('admin.plans.supplies.tracking_detail', compact('id', 'log_id'));
    }

    public function show(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('plans.supplies.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $canApprove = (int)auth()->user()->can('plans.supplies.approve');

        $isShow = true;

        return view('admin.plans.supplies.create', compact('id', 'canApprove', 'isShow'));
    }
}
