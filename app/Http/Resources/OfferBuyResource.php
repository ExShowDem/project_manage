<?php

namespace App\Http\Resources;

use App\Enums\CommonStatus;

class OfferBuyResource extends BaseResource
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
            'date_offer' => $this->date_offer,
            'ticket_number' => $this->ticket_number,
            'project_id' => $this->project_id,
            'supplier_id' => $this->supplier_id,
            'request_id' => $this->request_id,
            'created_by' => $this->created_by,
            'status' => $this->status->value,
            'status_label' => $this->status->description,
            'status_label_class' => CommonStatus::getLabelClass($this->status->value),
            'project' => $this->project ? : getSubstituteForDeleted(),
            'supplier' => $this->supplier ? : getSubstituteForDeleted(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'creator' => $this->creator ? : getSubstituteForDeleted(),
            'files'    => $this->files,
            'comments' => $this->comments,
            'request' => $this->request ? : getSubstituteForDeleted(),
        ];
    }
}
