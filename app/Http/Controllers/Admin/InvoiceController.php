<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends BaseController
{
    public function index()
    {
        if (!auth()->user()->can('invoices.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.invoices.index');
    }

    public function create(Request $request)
    {
        if (!auth()->user()->can('invoices.create')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $canApprove = (int)auth()->user()->can('invoices.approve');
        $code = generate_code_for_model(new Invoice);
        $supplyEachRequestIds = explode_ids($request->supply_each_request_ids);

        return view('admin.invoices.create', compact('supplyEachRequestIds', 'canApprove', 'code'));
    }

    public function edit(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('invoices.update')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $canApprove = (int)auth()->user()->can('invoices.approve');

        return view('admin.invoices.create', compact('id', 'canApprove'));
    }

    public function createTicketImport(Request $request, $projectId, $id)
    {
        $invoice = Invoice::find($id);

        if ($invoice->status->value === InvoiceStatus::IMPORTED) {
            return redirect('/');
        }

        return view('admin.ticket-import.form', compact('id'));
    }

    public function tracking($projectId, $id)
    {
        return view('admin.invoices.tracking', compact('id'));
    }

    public function trackingDetail($projectId, $id, $log_id)
    {
        return view('admin.invoices.tracking_detail', compact('id', 'log_id'));
    }

    public function show(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('invoices.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $canApprove = (int)auth()->user()->can('invoices.approve');

        $isShow = true;

        return view('admin.invoices.create', compact('id', 'canApprove', 'isShow'));
    }
}
