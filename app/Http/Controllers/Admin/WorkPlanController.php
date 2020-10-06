<?php

namespace App\Http\Controllers\Admin;

class WorkPlanController extends BaseController
{
    public function index()
    {
        if (!auth()->user()->can('work_plan.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        return view('admin.work_plan.index');
    }
}
