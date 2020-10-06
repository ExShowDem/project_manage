<?php

namespace App\Http\Controllers\Admin;

use App\Models\OfferBuy;
use Illuminate\Http\Request;

class OfferBuyController extends BaseController
{
    public function index()
    {
        if (!auth()->user()->can('offer_buys.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.offer_buys.index');
    }

    public function create(Request $request)
    {
        if (!auth()->user()->can('offer_buys.create')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $supplyEachRequestIds = explode_ids($request->supply_each_request_ids);
        $offerSupplyIds       = explode_ids($request->offer_supply_ids);
        $code                 = generate_code_for_model(new OfferBuy);
        $canApprove           = (int) auth()->user()->can('offer_buys.approve');

        return view('admin.offer_buys.create', compact([
            'canApprove',
            'code',
            'supplyEachRequestIds',
            'offerSupplyIds'
        ]));
    }

    public function edit(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('offer_buys.update')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $canApprove = (int)auth()->user()->can('offer_buys.approve');

        return view('admin.offer_buys.create', compact('id', 'canApprove'));
    }

    public function createInvoice($projectId, $offerBuyId)
    {
        $offerBuy = OfferBuy::findOrFail($offerBuyId);

        $offerBuyName = $offerBuy->name;

        $canApprove = (int)auth()->user()->can('offer_buys.approve');

        return view('admin.invoices.create', compact([
            'offerBuyId',
            'offerBuyName',
            'canApprove'
        ]));
    }

    public function tracking($projectId, $id)
    {
        return view('admin.offer_buys.tracking', compact('id'));
    }

    public function trackingDetail($projectId, $id, $log_id)
    {
        return view('admin.offer_buys.tracking_detail', compact('id', 'log_id'));
    }

    public function show(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('offer_buys.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $canApprove = (int)auth()->user()->can('offer_buys.approve');

        $isShow = true;

        return view('admin.offer_buys.create', compact('id', 'canApprove', 'isShow'));
    }
}
