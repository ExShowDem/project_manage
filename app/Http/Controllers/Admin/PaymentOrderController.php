<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\PaymentOrder;

class PaymentOrderController extends BaseController
{
    public function index(Request $request)
    {
//        if (!auth()->user()->can('payment_order.read')) {
//            return redirect(route('admin.error')); //abort(403);
//        }
        //this part is done by you right

        return view('admin.payment_order.index');
    }

    public function create(Request $request)
    {
        if (!auth()->user()->can('payment_order.create')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $canApprove = (int)auth()->user()->can('payment_order.approve');
        $code = generate_code_for_model(new PaymentOrder);

        return view('admin.payment_order.form', compact('canApprove', 'code'));
    }

    public function edit(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('payment_order.update')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $canApprove = (int)auth()->user()->can('payment_order.approve');

        return view('admin.payment_order.form', compact('id', 'canApprove'));
    }

    public function tracking($projectId, $id)
    {
        return view('admin.payment_order.tracking', compact('id'));
    }

    public function trackingDetail($projectId, $id, $log_id)
    {
        return view('admin.payment_order.tracking_detail', compact('log_id'));
    }

    public function show(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('payment_order.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $canApprove = (int)auth()->user()->can('payment_order.approve');

        $isShow = true;

        return view('admin.payment_order.form', compact('id', 'canApprove', 'isShow'));
    }
}
