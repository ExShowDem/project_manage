<?php

namespace App\Http\Resources;

use App\Enums\CensorSubContractorType;
use App\Enums\CommonStatus;
use Carbon\Carbon;

class CensorSubResource extends BaseResource
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
            'subcontractors' => $this->subcontractors ? : getSubstituteForDeleted(),
            'project' => $this->project ? : getSubstituteForDeleted(),
            'package' => $this->package,
            'date_browsing' => $this->date_browsing ? Carbon::parse($this->date_browsing)->format(config('api.date_format')) : '',
            'date_approve' => $this->date_approve ? Carbon::parse($this->date_approve)->format(config('api.date_format')) : '',
            'type_label' => CensorSubContractorType::getDescription($this->type),
            'type' => $this->type,
            'link' => $this->link,
            'status_label' => CommonStatus::getDescription($this->status),
            'status' => $this->status,
            'files' => $this->files,
        ];
    }
}
