<?php

namespace App\Http\Resources;

use App\Enums\CommonStatus;

class StocktakingResource extends BaseResource
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
            'created_date' => $this->created_date,
            'status' => $this->status->value,
            'status_label' => $this->status->description,
            'status_label_class' => CommonStatus::getLabelClass($this->status->value),
            'can_edit' => $this->status->value != CommonStatus::APPROVED,
            'project' => $this->project ? : getSubstituteForDeleted(),
            'creator' => $this->creator ? : getSubstituteForDeleted(),
            'files'    => $this->files,
            'comments' => $this->comments,
        ];
    }
}
