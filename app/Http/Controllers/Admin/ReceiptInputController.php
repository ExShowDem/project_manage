<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\ReceiptInput;
use Illuminate\Http\Request;

class ReceiptInputController extends BaseController
{
    public function index()
    {
        if (!auth()->user()->can('receipt_inputs.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.inventories.receipt_inputs.index');
    }

    public function create()
    {
        if (!auth()->user()->can('receipt_inputs.create')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $code = generate_code_for_model(new ReceiptInput);

        $canApprove = (int)auth()->user()->can('receipt_inputs.approve');

        return view('admin.inventories.receipt_inputs.create', compact('code', 'canApprove'));
    }

    public function edit(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('receipt_inputs.update')) {
            return redirect(route('admin.error')); //abort(403);
        }
        $canApprove = (int)auth()->user()->can('receipt_inputs.approve');

        return view('admin.inventories.receipt_inputs.create', compact('id', 'canApprove'));
    }

    public function createTicketImport(Request $request, $projectId, $id)
    {
        $code = generate_code_for_model(new ReceiptInput);
        $invoice = Invoice::with('supplies', 'supplier', 'project', 'request')->find($id);

        if ($invoice->status->value === InvoiceStatus::IMPORTED) {
            return redirect('/');
        }

        $canApprove = (int)auth()->user()->can('receipt_inputs.approve');

        return view('admin.inventories.receipt_inputs.create', compact('invoice', 'code', 'canApprove'));
    }

    public function tracking($projectId, $id)
    {
        return view('admin.inventories.receipt_inputs.tracking', compact('id'));
    }

    public function trackingDetail($projectId, $id, $log_id)
    {
        return view('admin.inventories.receipt_inputs.tracking_detail', compact('id', 'log_id'));
    }

    public function show(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('receipt_inputs.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $canApprove = (int)auth()->user()->can('receipt_inputs.approve');

        $isShow = true;

        return view('admin.inventories.receipt_inputs.create', compact('id', 'canApprove', 'isShow'));
    }
}
