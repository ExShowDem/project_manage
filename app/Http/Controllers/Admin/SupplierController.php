<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class SupplierController extends BaseController
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('suppliers.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $type = $request->get('type', 'supplier');

        return view('admin.suppliers.index', compact('type'));
    }

    public function create(Request $request)
    {
        if (!auth()->user()->can('suppliers.create')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $type = $request->get('type', 'supplier');

        return view('admin.suppliers.create', compact('type'));
    }

    public function edit(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('suppliers.update')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $type = $request->get('type', 'supplier');

        return view('admin.suppliers.create', compact('id', 'type'));
    }

    public function tracking($projectId, $id)
    {
        return view('admin.suppliers.tracking', compact('id'));
    }

    public function trackingDetail($projectId, $id, $log_id)
    {
        return view('admin.suppliers.tracking_detail', compact('id', 'log_id'));
    }
}
