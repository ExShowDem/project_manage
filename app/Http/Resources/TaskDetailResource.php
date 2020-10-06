<?php

namespace App\Http\Resources;

use App\Enums\TaskStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'comment' => $this->comment,
            'taskable_id' => $this->taskable_id,
            'taskable_type' => $this->taskable_type,
            'taskable' => $this->taskable,
            'can_action' => $this->status->value == TaskStatus::CREATED
        ];
    }
}
