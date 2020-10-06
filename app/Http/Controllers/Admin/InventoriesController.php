<?php

namespace App\Http\Controllers\Admin;

use App\Models\Stocktaking;

class InventoriesController extends BaseController
{
    public function deliveryOnDemand()
    {
        if (!auth()->user()->can('delivery_on_demands.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.inventories.delivery_on_demand.index');
    }

    public function detail()
    {
        if (!auth()->user()->can('inventories_detail.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.inventories.detail.index');
    }

    public function createReceiptDeliveryOnDemand()
    {
        if (!auth()->user()->can('delivery_on_demands.create')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.inventories.delivery_on_demand.form');
    }

    public function stocktaking()
    {
        if (!auth()->user()->can('stocktaking.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.inventories.stocktaking.index');
    }

    public function createStocktaking()
    {
        if (!auth()->user()->can('stocktaking.create')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $code = generate_code_for_model(new Stocktaking);

        $canApprove = (int)auth()->user()->can('stocktaking.approve');

        return view('admin.inventories.stocktaking.create', compact('code', 'canApprove'));
    }

    public function editStocktaking($projectId, $id)
    {
        if (!auth()->user()->can('stocktaking.update')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $canApprove = (int)auth()->user()->can('stocktaking.approve');

        return view('admin.inventories.stocktaking.create', compact('id', 'canApprove'));
    }

    public function showStocktaking($projectId, $id)
    {
        if (!auth()->user()->can('stocktaking.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $canApprove = (int)auth()->user()->can('stocktaking.approve');

        $isShow = true;

        return view('admin.inventories.stocktaking.create', compact('id', 'canApprove', 'isShow'));
    }

    public function trackingStocktaking($projectId, $id)
    {
        return view('admin.inventories.stocktaking.tracking', compact('id'));
    }

    public function trackingStocktakingDetail($projectId, $id, $log_id)
    {
        return view('admin.inventories.stocktaking.tracking_detail', compact('id', 'log_id'));
    }
}
