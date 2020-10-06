<?php

namespace App\Http\Controllers\Admin;

use App\Models\ReceiptOutput;
use Illuminate\Http\Request;

class ReceiptOutputController extends BaseController
{
    public function index()
    {
        if (!auth()->user()->can('receipt_outputs.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.inventories.receipt_outputs.index');
    }

    public function create(Request $request)
    {
        if (!auth()->user()->can('receipt_outputs.create')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $supplyEachRequestIds = explode_ids($request->supply_each_request_ids);
        $offerSupplyIds       = explode_ids($request->offer_supply_ids);
        $code                 = generate_code_for_model(new ReceiptOutput);
        $canApprove           = (int) auth()->user()->can('receipt_outputs.approve');

        return view('admin.inventories.receipt_outputs.create', compact([
            'canApprove',
            'code',
            'supplyEachRequestIds',
            'offerSupplyIds'
        ]));
    }

    public function edit(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('receipt_outputs.update')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $canApprove = (int)auth()->user()->can('receipt_outputs.approve');

        return view('admin.inventories.receipt_outputs.create', compact('id', 'canApprove'));
    }

    public function tracking($projectId, $id)
    {
        return view('admin.inventories.receipt_outputs.tracking', compact('id'));
    }

    public function trackingDetail($projectId, $id, $log_id)
    {
        return view('admin.inventories.receipt_outputs.tracking_detail', compact('id', 'log_id'));
    }

    public function show(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('receipt_outputs.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $canApprove = (int)auth()->user()->can('receipt_outputs.approve');

        $isShow = true;

        return view('admin.inventories.receipt_outputs.create', compact('id', 'canApprove', 'isShow'));
    }
}
