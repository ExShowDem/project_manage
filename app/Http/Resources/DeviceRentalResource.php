<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Enums\CommonStatus;

class DeviceRentalResource extends BaseResource
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
            'id'             => $this->id,
            'code'           => $this->code,
            'name'           => $this->name,
            'project' => $this->project ? : getSubstituteForDeleted(),
            'borrower_type_id'   => $this->borrower_type,
            'borrower_type_name' => $this->borrowerType->name,
            'borrower_id'        => $this->borrower_id,
            'borrower_name'      => $this->borrower_name,
            'creator' => $this->creator ? : getSubstituteForDeleted(),
            'status'             => $this->status->value,
            'status_label'       => $this->status->description,
            'status_label_class' => CommonStatus::getLabelClass($this->status->value),
            'files'    => $this->files,
            'comments' => $this->comments,
            'devices'  => $this->devices,
        ];
    }
}