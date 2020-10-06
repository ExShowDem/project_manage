<?php

namespace App\Http\Controllers\Api;

use App\Models\MppLink;
use App\Models\MppTask;
use App\Models\MppTaskResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;

class MppTaskController extends BaseController
{
    public function store(Request $request, $workPlanId)
    {
        $task = new MppTask();

        $task->work_plan_id = $workPlanId;
        $task->text = $request->text;
        $task->start_date = $request->start_date;
        $task->duration = $request->duration;
        $task->progress = $request->progress ?: 0;
        $task->parent = $request->parent;

        $task->save();

        return response()->json([
            "action" => "inserted",
            "tid" => $task->id
        ]);
    }

    public function update($workPlanId, $id, Request $request)
    {
        $task = MppTask::find($id);

        $task->text = $request->text;
        $task->start_date = $request->start_date;
        $task->duration = $request->duration;
        $task->progress = $request->progress ?: 0;
        $task->parent = $request->parent;

        $task->save();

        return response()->json([
            "action" => "updated"
        ]);
    }

    public function destroy($workPlanId, $id)
    {
        $task = MppTask::find($id);

        $task->delete();

        return response()->json([
            "action" => "deleted"
        ]);
    }

    public function import(Request $request, $workPlanId)
    {
        $tasks = collect($request->get('data'));

        $mapping = [];
        $tasks->each(function ($task) use (&$mapping, $workPlanId) {
            $inputs = Arr::only($task, ['text', 'duration', 'progress', 'parent']);

            $inputs['start_date'] = Carbon::parse($task['start_date']);
            $inputs['work_plan_id'] = $workPlanId;

            if ($inputs['parent'] != 0 && isset($mapping[$inputs['parent']])) {
                $inputs['parent'] = $mapping[$inputs['parent']];
            }
            $current = MppTask::create($inputs);

            $mapping[$task['id']] = $current->id;
        });

        $links = collect($request->get('links'));

        $links->each(function ($link) use ($mapping, $workPlanId) {
            $inputs = Arr::only($link, ['source', 'target', 'type']);
            if (isset($mapping[$inputs['source']]) && isset($mapping[$inputs['target']])) {
                $inputs['source'] = $mapping[$inputs['source']];
                $inputs['target'] = $mapping[$inputs['target']];
                $inputs['work_plan_id'] = $workPlanId;

                MppLink::create($inputs);
            }
        });

        return response()->json([
            'data' => MppTask::where('work_plan_id', $workPlanId)->get(),
            'links' => MppLink::where('work_plan_id', $workPlanId)->get(),
        ]);
    }

    public function show(Request $request, $id, $taskId)
    {
        $task = MppTask::with([
            'files',
            'comments.user',
            'resources.resource',
        ])->find($taskId);

        return $this->responseSuccess(compact('task'));
    }

    public function updateInfo(Request $request, $id, $taskId)
    {
        $task = MppTask::findOrFail($taskId);

        $task->update($request->all());

        return $this->responseSuccess([]);

    }

    public function deleteResource($workPlanId, $taskId, $resourceId)
    {
        $resource = MppTaskResource::findOrFail($resourceId);
        if ($resource->delete()) {
            return $this->responseSuccess([]);
        }

        return $this->responseError('api.code.common.delete_failed');
    }
}
