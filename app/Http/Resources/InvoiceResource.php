<?php

namespace App\Http\Resources;

use App\Enums\CommonStatus;

class InvoiceResource extends BaseResource
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
            'code' => $this->code,
            'supplier_id' => $this->supplier_id,
            'contract_date' => $this->contract_date,
            'project_id' => $this->project_id,
            'contract_number' => $this->contract_number,
            'status' => $this->status->value,
            'status_label' => $this->status->description,
            'status_label_class' => CommonStatus::getLabelClass($this->status->value),
            'project' => $this->project ? : getSubstituteForDeleted(),
            'supplier' => $this->supplier ? : getSubstituteForDeleted(),
            'request' => $this->request ? : getSubstituteForDeleted(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'creator' => $this->creator ? : getSubstituteForDeleted(),
            'files'    => $this->files,
            'comments' => $this->comments,
        ];
    }
}
