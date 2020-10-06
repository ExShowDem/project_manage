<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Services\Api\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends BaseController
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function getCountDashboard(Request $request)
    {

        $params = $request->only('per_page', 'search_option', 'project_id');
        $dashboard = $this->dashboardService->getCountDashboard($params);
        return $this->responseSuccess($dashboard);
    }

}
