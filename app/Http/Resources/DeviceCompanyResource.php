<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Enums\CommonStatus;

class DeviceCompanyResource extends BaseResource
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
            'company'        => $this->company,
            'deviceToProject' => $this->deviceToProject ? : getSubstituteForDeleted(),
            'project' => $this->project ? : getSubstituteForDeleted(),
            'user' => $this->user ? : getSubstituteForDeleted(),
			'return_date'   => $this->return_date ? Carbon::parse($this->return_date)->format('d/m/Y') : '--/--/----',
            'status'             => $this->status->value,
            'status_label'       => $this->status->description,
            'status_label_class' => CommonStatus::getLabelClass($this->status->value),
            'files'    => $this->files,
            'comments' => $this->comments,
            'devices'  => $this->devices,
        ];
    }
}