<?php

namespace App\Services\Api;

use App\Enums\CommonStatus;
use App\Enums\TaskStatus;
use App\Models\ProcessLog;
use App\Services\BaseService;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\RoleTree;

class TaskService extends BaseService
{
    public function __construct(Task $task)
    {
        $this->model = $task;
    }

    public static function createTask($model, $params)
    {
        list($inputsTask, $inputsNotification) = self::smoothDataBeforeCreateTask($model, $params);

        $taskKey = get_task_key($model);
        $inputsTask['name'] = __($taskKey . '.task_name');

        foreach ($params['forward_data']['to'] as $assigneeId)
        {
            $inputsTask['to_user']       = $assigneeId;
            $inputsTask['assignee_type'] = 'assignee';

            $task = Task::create($inputsTask);

            $notice = [
                'url'             => route('admin.projects.tasks.show', ['projectId' => $inputsTask['project_id'], 'id' => $task->id]),
                'body'            => '[' . $task->code . '] ' . __($taskKey . '.send_request', ['name' => auth()->user()->name]),
                'project_id'      => $inputsTask['project_id'],
                'targetable_id'   => $inputsTask['taskable_id'],
                'targetable_type' => $inputsTask['taskable_type'],
            ];

            $notice['content'] = $notice['body'];

            sendNotifications([$assigneeId], $notice);
        }

        foreach ($params['forward_data']['cc'] as $ccId)
        {
            $inputsTask['to_user']       = $ccId;
            $inputsTask['assignee_type'] = 'cc';

            $task = Task::create($inputsTask);

            $notice = [
                'url'             => route('admin.projects.tasks.show', ['projectId' => $inputsTask['project_id'], 'id' => $task->id]),
                'body'            => '[' . $task->code . '] ' . __($taskKey . '.send_request', ['name' => auth()->user()->name]),
                'project_id'      => $inputsTask['project_id'],
                'targetable_id'   => $inputsTask['taskable_id'],
                'targetable_type' => $inputsTask['taskable_type'],
            ];

            $notice['content'] = $notice['body'];

            sendNotifications([$ccId], $notice);
        }

        ProcessLog::create([
            'project_id' => $params['project_id'],
            'process_user_id' => auth()->id(),
            'status' => TaskStatus::CREATED,
            'table_id' => $model->id,
            'table_type' => get_class($model),
            'comment' => $params['forward_data']['comment'] ?? '',
            'name' => __('tasks.send_request'),
            'data_object' => $params,
        ]);

        // create comment
        $commentService = resolve(CommentService::class);
        $commentService->createCommentWhenHandleTask($inputsTask, $params['forward_data']['comment']);
    }

    public function getMyTasks($params)
    {

        $query       = $this->model;
        $roleIds     = array_column(auth()->user()->roles->toArray(), 'id');
        $isAdmin     = auth()->user()->hasRole('admin');
        $isMonitorer = in_array(9, $roleIds);

        if (!$isAdmin && !$isMonitorer)
        { //normal user
            $roleIds = array_unique(array_merge($roleIds, get_child_roles($roleIds)));

            $for = User::whereHas(
                'roles', function($q) use ($roleIds) {
                    $q->whereIn('id', $roleIds);
                }
            )->pluck('id')->toArray();

            $query = $query->whereIn('to_user', $for);
        }

        if (isset($params['search_option']['view_type'])) {
            if ($params['search_option']['view_type'] == 1) {
                $query = $query->where('status', TaskStatus::CREATED);
            }

            if ($params['search_option']['view_type'] == 2) {
                $query = $query->where('status', '!=', TaskStatus::CREATED);
            }
        }

        if (isset($params['search_option']['keyword'])) {
            $query = $query->whereNested(function($q) use ($params) {
                $q
                    ->where('name', 'like', '%' . $params['search_option']['keyword'] . '%')
                    ->orWhere('code', 'like', '%' . $params['search_option']['keyword'] . '%');
            });
        }

        if (isset($params['search_option']['create_date_from']) && isset($params['search_option']['create_date_till'])) {
            $query = $query
                ->where('created_at', '>=', carbon_date($params['search_option']['create_date_from'])->startOfDay())
                ->where('created_at', '<=', carbon_date($params['search_option']['create_date_till'])->endOfDay());
        }

        if (isset($params['search_option']['process_date_from']) && isset($params['search_option']['process_date_till'])) {
            $query = $query
                ->where('process_date', '>=', carbon_date($params['search_option']['process_date_from'])->startOfDay())
                ->where('process_date', '<=', carbon_date($params['search_option']['process_date_till'])->endOfDay());
        }

        if (isset($params['search_option']['project'])) {
            $query = $query->where('project_id', $params['search_option']['project']);
        }

        $query = $query->whereIn('tasks.project_id', function ($q) {
            $q->select('projects.id')
                ->from('projects')
                ->whereNull('projects.deleted_at');
        });

        if (isset($params['search_option']['sender'])) {
            $query = $query->where('from_user', $params['search_option']['sender']);
        }

        if (isset($params['search_option']['receiver'])) {
            $query = $query->where('to_user', $params['search_option']['receiver']);
        }

        if (isset($params['search_option']['role'])) 
        {
            $roleId = $params['search_option']['role'];
            $roleIds = array_unique(array_merge([$roleId], get_child_roles([$roleId])));

            $query = $query->whereIn('to_user', function ($subQuery) use ($roleIds) {
                $subQuery
                    ->select('model_has_roles.model_id')
                    ->from('model_has_roles')
                    ->whereIn('role_id', $roleIds)
                    ->where('model_type', User::class);
            });
        }

        $query->orderBy('tasks.id', 'desc');

        return $query->with('taskable', 'project');
    }

    public function forward($id, $params)
    {
        try {
            DB::beginTransaction();
            $task = $this->model->find($id);
            $task->status = TaskStatus::FORWARDED;
            $task->process_date = Carbon::now();
            $task->save();

            // create comment
            $commentService = resolve(CommentService::class);
            $commentService->createCommentWhenHandleTask($task, $params['forward_data']['comment']);

            $model = $task->taskable;
            list($inputsTask, $inputsNotification) = self::smoothDataBeforeCreateTask($model, $params);

            $taskKey = get_task_key($model);
            $inputsTask['name'] = __($taskKey . '.task_name');

            foreach ($params['forward_data']['to'] as $assigneeId)
            {
                $inputsTask['to_user']       = $assigneeId;
                $inputsTask['assignee_type'] = 'assignee';

                $task = Task::create($inputsTask);

                $notice = [
                    'url'             => route('admin.projects.tasks.show', ['projectId' => $inputsTask['project_id'], 'id' => $task->id]),
                    'body'            => '[' . $task->code . '] ' . __($taskKey . '.send_request', ['name' => auth()->user()->name]),
                    'project_id'      => $inputsTask['project_id'],
                    'targetable_id'   => $inputsTask['taskable_id'],
                    'targetable_type' => $inputsTask['taskable_type'],
                ];

                $notice['content'] = $notice['body'];

                sendNotifications([$assigneeId], $notice);
            }

            foreach ($params['forward_data']['cc'] as $ccId)
            {
                $inputsTask['to_user']       = $ccId;
                $inputsTask['assignee_type'] = 'cc';

                $task = Task::create($inputsTask);

                $notice = [
                    'url'             => route('admin.projects.tasks.show', ['projectId' => $inputsTask['project_id'], 'id' => $task->id]),
                    'body'            => '[' . $task->code . '] ' . __($taskKey . '.send_request', ['name' => auth()->user()->name]),
                    'project_id'      => $inputsTask['project_id'],
                    'targetable_id'   => $inputsTask['taskable_id'],
                    'targetable_type' => $inputsTask['taskable_type'],
                ];

                $notice['content'] = $notice['body'];

                sendNotifications([$ccId], $notice);
            }

            ProcessLog::create([
                'project_id' => $params['project_id'],
                'process_user_id' => auth()->id(),
                'status' => TaskStatus::FORWARDED,
                'table_id' => $model->id,
                'table_type' => get_class($model),
                'comment' => $params['forward_data']['comment'] ?? '',
                'name' => __('tasks.forward_request'),
            ]);


            DB::commit();
        } catch (\Exception $e) {
            Log::error(errorMessage($e));
            DB::rollBack();

            return false;
        }

        return true;
    }

    public function approve($id, $inputs)
    {
        try 
        {
            DB::beginTransaction();

            $task = $this->model->find($id);
            $task->status = TaskStatus::APPROVED;
            $task->process_date = Carbon::now();
            $task->save();
            $task->taskable->update(['status' => CommonStatus::APPROVED]);

            $taskKey          = get_task_key($task->taskable_type, false);
            $type             = typeToModelLookup($task->taskable_type, false);
            list(, , $urlKey) = detectModel($type, false);

            $processLog = new ProcessLog([
                'project_id'      => $task->project_id,
                'process_user_id' => auth()->id(),
                'status'          => TaskStatus::APPROVED,
                'table_id'        => $task->taskable_id,
                'table_type'      => $task->taskable_type,
                'comment'         => $inputs['approve_data']['comment'] ?? '',
                'name'            => __($taskKey . '.task_name')
            ]);

            $processLog->save();

            if ($urlKey === 'admin.projects.requests.supplies')
            {
                $taskable     = $task->taskable_type::find($task->taskable_id);
                $taskableAttr = $taskable->getAttributes();
                $notifUrl     = route($urlKey . '.show', ['projectId' => $task->project_id, 'target' => $taskableAttr['target'], 'id' => $task->taskable_id]);
            }
            else
            {
                $notifUrl = route($urlKey . '.show', ['projectId' => $task->project_id, 'id' => $task->taskable_id]);
            }

            $notice = [
                'project_id'      => $task->project_id,
                'targetable_id'   => $task->taskable_id,
                'targetable_type' => $task->taskable_type,
                'body'            => __($taskKey . '.approve', ['code' => $task->taskable->code, 'name' => auth()->user()->name]),
                'url'             => $notifUrl,
            ];

            $notice['content'] = $notice['body'];

            $relatedUser   = $this->processTaskForRelatedUser($task, TaskStatus::APPROVED);
            $userNotifiIds = array_unique(array_merge($inputs['approve_data']['cc'], $relatedUser, [$task->from_user]));

            sendNotifications($userNotifiIds, $notice);

            // create comment
            $commentService = resolve(CommentService::class);
            $commentService->createCommentWhenHandleTask($task, $inputs['approve_data']['comment']);

            DB::commit();

            return true;
        } 
        catch (\Exception $e) 
        {
            Log::error(errorMessage($e));
            DB::rollBack();

            return false;
        }
    }

    public function return($id, $inputs)
    {
        try 
        {
            DB::beginTransaction();

            $task = $this->model->find($id);
            $task->status = TaskStatus::REJECTED;
            $task->process_date = Carbon::now();
            $task->save();
            $task->taskable->update(['status' => CommonStatus::REJECTED]);

            $taskKey          = get_task_key($task->taskable_type, false);
            $type             = typeToModelLookup($task->taskable_type, false);
            list(, , $urlKey) = detectModel($type, false);

            // create comment
            $commentService = resolve(CommentService::class);
            $commentService->createCommentWhenHandleTask($task, $inputs['return_data']['comment']);

            $processLog = new ProcessLog([
                'project_id'      => $task->project_id,
                'process_user_id' => auth()->id(),
                'status'          => TaskStatus::REJECTED,
                'table_id'        => $task->taskable_id,
                'table_type'      => $task->taskable_type,
                'comment'         => $inputs['return_data']['comment'] ?? '',
                'content'         => __('tasks.reject_request'),
                'name'            => __($taskKey . '.task_name')
            ]);

            $processLog->save();
			
			if ($urlKey === 'admin.projects.requests.supplies')
            {
                $taskable     = $task->taskable_type::find($task->taskable_id);
                $taskableAttr = $taskable->getAttributes();
                $notifUrl     = route($urlKey . '.show', ['projectId' => $task->project_id, 'target' => $taskableAttr['target'], 'id' => $task->taskable_id]);
            }
            else
            {
                $notifUrl = route($urlKey . '.show', ['projectId' => $task->project_id, 'id' => $task->taskable_id]);
            }
			
            $notice = [
                'project_id'      => $task->project_id,
                'targetable_id'   => $task->taskable_id,
                'targetable_type' => $task->taskable_type,
                'body'            => __($taskKey . '.reject', ['code' => $task->taskable->code, 'name' => auth()->user()->name]),
                'url'             => $notifUrl,
            ];

            $notice['content'] = $notice['body'];

            $relatedUser   = $this->processTaskForRelatedUser($task, TaskStatus::REJECTED);
            $userNotifiIds = array_unique(array_merge($inputs['return_data']['cc'], $relatedUser, [$task->from_user]));

            sendNotifications($userNotifiIds, $notice);

            DB::commit();
            return true;
        } 
        catch (\Exception $e) 
        {
            Log::error(errorMessage($e));
            DB::rollBack();

            return false;
        }
    }

    public function cancel($id, $inputs)
    {
        try 
        {
            DB::beginTransaction();

            $task = $this->model->find($id);
            $task->status = TaskStatus::CANCELED;
            $task->process_date = Carbon::now();
            $task->save();
            $task->taskable->update(['status' => CommonStatus::CANCELED]);

            $taskKey          = get_task_key($task->taskable_type, false);
            $type             = typeToModelLookup($task->taskable_type, false);
            list(, , $urlKey) = detectModel($type, false);

            // create comment
            $commentService = resolve(CommentService::class);
            $commentService->createCommentWhenHandleTask($task, $inputs['cancel_data']['comment']);

            $processLog = new ProcessLog([
                'project_id'      => $task->project_id,
                'process_user_id' => auth()->id(),
                'status'          => TaskStatus::CANCELED,
                'table_id'        => $task->taskable_id,
                'table_type'      => $task->taskable_type,
                'comment'         => $inputs['cancel_data']['comment'] ?? '',
                'content'         => __('tasks.cancel_request'),
                'name'            => __($taskKey . '.task_name')
            ]);

            $processLog->save();
			
			if ($urlKey === 'admin.projects.requests.supplies')
            {
                $taskable     = $task->taskable_type::find($task->taskable_id);
                $taskableAttr = $taskable->getAttributes();
                $notifUrl     = route($urlKey . '.show', ['projectId' => $task->project_id, 'target' => $taskableAttr['target'], 'id' => $task->taskable_id]);
            }
            else
            {
                $notifUrl = route($urlKey . '.show', ['projectId' => $task->project_id, 'id' => $task->taskable_id]);
            }
			
            $notice = [
                'project_id'      => $task->project_id,
                'targetable_id'   => $task->taskable_id,
                'targetable_type' => $task->taskable_type,
                'body'            => __($taskKey . '.cancel', ['code' => $task->taskable->code, 'name' => auth()->user()->name]),
                'url'             => $notifUrl,
            ];

            $notice['content'] = $notice['body'];

            $relatedUser   = $this->processTaskForRelatedUser($task, TaskStatus::CANCELED);
            $userNotifiIds = array_unique(array_merge($inputs['cancel_data']['cc'], $relatedUser, [$task->from_user]));

            sendNotifications($userNotifiIds, $notice);

            DB::commit();
            return true;
        } 
        catch (\Exception $e) 
        {
            Log::error(errorMessage($e));
            DB::rollBack();

            return false;
        }
    }

    private static function smoothDataBeforeCreateTask($model, $params)
    {
        $createdDate    = Carbon::now();
        $processingTime = $params['forward_data']['processing_time'] ? : 48;
        $dueDate        = $createdDate->addHours($processingTime);

        $inputsTask = [
            'taskable_id' => $model->id,
            'taskable_type' => get_class($model),
            'code' => generate_code_for_model(new Task),
            'project_id' => $params['project_id'],
            'created_date' => $createdDate,
            'status' => TaskStatus::CREATED,
            'from_user' => $params['created_by'],
            'comment' => $params['forward_data']['comment'],
            'due_date' => $dueDate,
        ];

        $inputsNotification = [
            'project_id' => $params['project_id'],
            'from_user' => $params['created_by'],
            'targetable_id' => $model->id,
            'targetable_type' => get_class($model),
        ];

        return [$inputsTask, $inputsNotification];
    }

    private function processTaskForRelatedUser($task, $status)
    {
        /**
         * Change status all related task
         */
        $this->model->getRelatedTask($task)
            ->update([
                'status' => $status,
                'process_date' => Carbon::now(),
            ]);


        $relatedUser = $this->model->getRelatedTask($task)->pluck('to_user')->toArray();
        return $relatedUser;
    }

    public function getHandledTask($task)
    {
        return in_array($task->status->value, [TaskStatus::APPROVED, TaskStatus::CANCELED, TaskStatus::REJECTED]);
    }
}
