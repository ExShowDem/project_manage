<?php

namespace App\Http\Resources;

use App\Enums\TaskStatus;
use Carbon\Carbon;

class TaskResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->due_date) {
            if ($this->process_date) {
                $processingHoursLeft = strtotime($this->due_date) - strtotime($this->process_date);
            } else {
                $processingHoursLeft = strtotime($this->due_date) - time();
            }

            $processingHoursLeft = $processingHoursLeft / 3600;
            $processingHoursLeft = round($processingHoursLeft, 1);
        } else {
            $processingHoursLeft = '';
        }

        $taskable = $this->taskable_type::where('id', $this->taskable_id)->first();
        $taskable = $taskable ? $taskable->getAttributes() : getSubstituteForDeleted();

        $taskName = isset($taskable['name']) ? $taskable['name'] : $taskable['code'];

        return [
            'id' => $this->id,
            'name' => $this->name,
            'task_name' => $taskName,
            'code' => $this->code,
            'comment' => $this->comment,
            'created_date' => $this->created_date ? Carbon::parse($this->created_date)->format('d/m/Y') : '',
            'process_date' => $this->process_date ? Carbon::parse($this->process_date)->format('d/m/Y') : '--/--/----',
            $this->mergeWhen($this->whenLoaded('project'), [
                'project_name' => $this->project ? $this->project->name : 'N/A'
            ]),
            $this->mergeWhen($this->whenLoaded('taskable'), [
                'sender' => $this->sender ? $this->sender->name : 'N/A'
            ]),
            $this->mergeWhen($this->whenLoaded('user'), [
                'receiver' => $this->receiver ? $this->receiver->name : 'N/A'
            ]),
            'assignee_type' => $this->assignee_type,
            'remaining_time' => $processingHoursLeft,
            'status_label' => $this->status->description,
            'status_label_class' => TaskStatus::getLabelClass($this->status->value),
            $this->mergeWhen($this->whenLoaded('user'), [
                'roles' => $this->receiver ? $this->receiver->getHierarchicalRoles() : null
            ]),

        ];
    }
}
