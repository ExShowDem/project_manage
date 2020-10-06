<?php

namespace App\Http\Controllers\Admin;

use App\Models\ReceiptTransfer;
use Illuminate\Http\Request;

class ReceiptTransferController extends BaseController
{
    public function index()
    {
        if (!auth()->user()->can('receipt_transfers.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.inventories.receipt_transfers.index');
    }

    public function create()
    {
        if (!auth()->user()->can('receipt_transfers.create')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $code = generate_code_for_model(new ReceiptTransfer);

        $canApprove = (int)auth()->user()->can('receipt_transfers.approve');

        return view('admin.inventories.receipt_transfers.create', compact('code', 'canApprove'));
    }

    public function edit(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('receipt_transfers.update')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $canApprove = (int)auth()->user()->can('receipt_transfers.approve');

        return view('admin.inventories.receipt_transfers.create', compact('id', 'canApprove'));
    }

    public function tracking($projectId, $id)
    {
        return view('admin.inventories.receipt_transfers.tracking', compact('id'));
    }

    public function trackingDetail($projectId, $id, $log_id)
    {
        return view('admin.inventories.receipt_transfers.tracking_detail', compact('id', 'log_id'));
    }

    public function show(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('receipt_transfers.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $canApprove = (int)auth()->user()->can('receipt_transfers.approve');

        $isShow = true;

        return view('admin.inventories.receipt_transfers.create', compact('id', 'canApprove', 'isShow'));
    }
}
