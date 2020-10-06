<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Enums\CommonStatus;

class DeviceRoundRobinResource extends BaseResource
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
            'name'           => $this->name,
            'code'           => $this->code,
            'from_project' => $this->fromProject ? : getSubstituteForDeleted(),
            'to_project' => $this->toProject ? : getSubstituteForDeleted(),
            'creator' => $this->creator ? : getSubstituteForDeleted(),
			'date'   => $this->created_at ? Carbon::parse($this->created_at)->format('d/m/Y') : '--/--/----',
            'status'             => $this->status->value,
            'status_label'       => $this->status->description,
            'status_label_class' => CommonStatus::getLabelClass($this->status->value),
            'files'    => $this->files,
            'comments' => $this->comments,
            'devices'  => $this->devices,
        ];
    }
}