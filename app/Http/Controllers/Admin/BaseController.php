<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;

class BaseController extends Controller
{
    protected $viewData = [];

    protected $currentUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->currentUser = $request->user();
            $projectId = $request->route('projectId');

            if ($projectId && !$this->currentUser->can('project_'.$projectId.'.read'))
            {
                return redirect(route('admin.error')); //abort(403);
            }

            $project = Project::find($projectId);

            view()->share([
                'currentUser' => $this->currentUser,
                'token' => auth('api')->login($this->currentUser),
                'currentProjectName' => $project ? $project->name : '',
            ]);

            return $next($request);
        });
    }
}
