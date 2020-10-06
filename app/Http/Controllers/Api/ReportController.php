<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Services\Api\ReportService;
use Illuminate\Http\Request;

class ReportController extends BaseController
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function getCountReport(Request $request)
    {

        $params = $request->only('per_page', 'search_option', 'project_id');
        //dd($params);
        $report = $this->reportService->getCountReport($params);
        return $this->responseSuccess($report);
    }

}
