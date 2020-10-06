<?php

namespace App\Http\Resources;

use App\Enums\CommonStatus;

class ReceiptTransferResource extends BaseResource
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
            'output_id' => $this->output_id,
            'input_id' => $this->input_id,
            'date_transfer' => $this->date_transfer,
            'code' => $this->code,
            'reason' => $this->reason,
            'status' => $this->status->value,
            'created_by' => $this->created_by,
            'status_label' => $this->status->description,
            'status_label_class' => CommonStatus::getLabelClass($this->status->value),
            'project' => $this->project ? : getSubstituteForDeleted(),
            'supplier' => $this->supplier ? : getSubstituteForDeleted(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'creator' => $this->creator ? : getSubstituteForDeleted(),
            'files'    => $this->files,
            'comments' => $this->comments,
        ];
    }
}
