<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Api\SupplierRequest;
use App\Http\Resources\SuppliesByRequestResource;
use App\Models\Task;
use App\Models\Plan;
use App\Models\Invoice;
use App\Models\DevicePurchase;
use App\Models\CategorySupplies;
use App\Models\RequestSupply;
use App\Models\OfferBuy;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DashboardController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($id)
    {
        if (!auth()->user()->can('dashboard.read')) {
            return redirect(route('admin.error')); //abort(403);
        }


        return view('admin.index');
    }
}
