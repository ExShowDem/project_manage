<?php
namespace App\Http\Controllers\Api;


use App\Http\Requests\Api\TaskResourceRequest;
use App\Http\Requests\Api\WorkPlanRequest;
use App\Http\Resources\WorkPlanResource;
use App\Services\Api\WorkPlanService;
use Illuminate\Http\Request;

class WorkPlanController extends BaseController
{
    protected $service;

    public function __construct(WorkPlanService $service)
    {
        $this->service = $service;
    }

    public function store(WorkPlanRequest $request)
    {
        $inputs = $request->all();
        $inputs['created_by'] = auth()->id();

        $plan = $this->service->create($inputs);

        if ($plan) {
            return $this->responseSuccess(compact('plan'));
        }

        return $this->responseError('api.code.common.create_failed');
    }

    public function index(Request $request)
    {
        $params = $request->only('per_page', 'search_option', 'project_id');

        $plans = WorkPlanResource::apiPaginate($this->service->getWorkPlans($params), $request);

        return $this->responseSuccess($plans);
    }

    public function show(Request $request, $id)
    {
        $result = $this->service->getGanttData($id);

        return response()->json($result);
    }

    public function addResourceForTask(TaskResourceRequest $request, $workPlanId, $taskId)
    {
        $result = $this->service->addResourceForTask($taskId, $request->all());

        if ($result) {
            return $this->responseSuccess([]);
        }

        return $this->responseError('api.code.common.request_error');
    }
}