<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\RequestSupply;
use Illuminate\Http\Request;

class RequestController extends BaseController
{
    public function requestSupplies(Request $request, $projectId, $target = 'project')
    {
        if (!auth()->user()->can('supplies.request_from_project.read')) {
            //return redirect(route('admin.error')); //abort(403);
            return redirect(route('admin.error'));
        }

        $companyId = null;

        if ($request->route('target') === 'company') {
            $companyId = config('company_info.id');
        }

        return view('admin.requests.supplies.index', compact('companyId'));
    }

    public function createRequestSupplies(Request $request, $projectId, $target)
    {
        if (!auth()->user()->can('supplies.request_from_project.create')) {
            return redirect(route('admin.error')); //abort(403);
            return redirect(route('admin.projects.dashboard'));
        }

        $code = generate_code_for_model(new RequestSupply, $projectId);
        $canApprove = (int)auth()->user()->can('supplies.request_from_project.approve');

        $projectId = $request->route('target') === 'company' ? config('company_info.id') : $projectId;

        $project = Project::findOrFail($projectId);
        $projectName = $project->name;

        return view('admin.requests.supplies.create', compact('code', 'target', 'projectId', 'projectName', 'canApprove'));
    }

    public function editRequestSupplies(Request $request, $projectId, $target, $id)
    {

        if (!auth()->user()->can('supplies.request_from_project.update')) {
            return redirect(route('admin.error')); //abort(403);
            return redirect(route('admin.projects.dashboard'));
        }

        $canApprove = (int)auth()->user()->can('supplies.request_from_project.approve');
        $projectId = $request->route('target') === 'company' ? config('company_info.id') : $projectId;
        $project = Project::findOrFail($projectId);
        $projectName = $project->name;

        return view('admin.requests.supplies.create', compact('id', 'target', 'projectId', 'projectName', 'canApprove'));
    }

    public function tracking(Request $request, $projectId, $target, $id)
    {
        $projectId = $request->route('target') === 'company' ? config('company_info.id') : $projectId;

        return view('admin.requests.supplies.tracking', compact('id', 'target', 'projectId'));
    }

    public function trackingDetail($projectId, $target, $id, $log_id)
    {
        return view('admin.requests.supplies.tracking_detail', compact('id', 'projectId', 'target', 'log_id'));
    }


    public function show(Request $request, $projectId, $target, $id)
    {
        if (!auth()->user()->can('supplies.request_from_project.read')) {
            return redirect(route('admin.error')); //abort(403);
        }

        $canApprove = (int)auth()->user()->can('supplies.request_from_project.approve');

        $projectId = $request->route('target') === 'company' ? config('company_info.id') : $projectId;
        $project = Project::findOrFail($projectId);
        $projectName = $project->name;

        $isShow = true;

        return view('admin.requests.supplies.create', compact('id', 'target', 'projectId', 'projectName', 'canApprove', 'isShow'));
    }
}
