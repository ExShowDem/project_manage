<?php

namespace App\Http\Resources;

use App\Enums\CommonStatus;

class PlanResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'status' => $this->status->value,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'status_label' => $this->status->description,
            'status_label_class' => CommonStatus::getLabelClass($this->status->value),
            'project_id' => $this->project_id,
            'created_by' => $this->created_by,
            'project' => $this->project ? : getSubstituteForDeleted(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'creator' => $this->creator ? : getSubstituteForDeleted(),
            'files'    => $this->files,
            'comments' => $this->comments,
        ];
    }
}
