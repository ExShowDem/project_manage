<?php
namespace App\Services\Api;

use App\Models\MppTask;
use App\Models\MppTaskResource;
use App\Models\WorkPlan;
use App\Models\MppLink;
use App\Services\BaseService;
use Illuminate\Support\Arr;

class WorkPlanService extends BaseService
{
    public function __construct(WorkPlan $plan)
    {
        $this->model = $plan;
    }

    public function getWorkPlans($params)
    {
        $query = $this->model->newQuery();

        if (isset($params['project_id'])) {
            $query = $query->where('project_id', $params['project_id']);
        }

        return $query;
    }

    public function getGanttData($id)
    {
        $data = MppTask::where('work_plan_id', $id)
            ->with([
                'executor',
                'files',
                'comments.user',
                'resources.resource',
            ])
            ->get();
        $links = MppLink::where('work_plan_id', $id)->get();

        return compact('data', 'links');
    }

    public function addResourceForTask($taskId, $inputs)
    {
        $task = MppTask::findOrFail($taskId);

        $inputResourcesCreate = [];
        $inputResourcesUpdate = [];
        foreach ($inputs['resources'] as $resource) {
            if (isset($resource['mpp_task_resource_id'])) {
                $inputResourcesUpdate[$resource['mpp_task_resource_id']] = [
                    'quantity' => $resource['quantity'],
                    'unit_price' => $resource['unit_price'],
                ];
                continue;
            }
            $inputResourcesCreate[] = [
                'tracking_date' => carbon_date($inputs['tracking_date']),
                'resource_id' => $resource['id'],
                'resource_type_id' => $resource['resource_type_id'],
                'quantity' => $resource['quantity'],
                'unit_price' => $resource['unit_price'],
            ];
        }

        $result = $task->resources()->createMany($inputResourcesCreate);

        foreach ($inputResourcesUpdate as $id => $updateData) {
            $taskResource = MppTaskResource::find($id);
            $taskResource->update($updateData);
        }

        return $result;
    }
}