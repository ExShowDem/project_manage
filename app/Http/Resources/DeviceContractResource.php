<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Enums\CommonStatus;

class DeviceContractResource extends BaseResource
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
            'purchase' => $this->purchase ? : getSubstituteForDeleted(),
            'place' => $this->place ? : getSubstituteForDeleted(),
            'project' => $this->project ? : getSubstituteForDeleted(),
			'date'           => $this->created_at ? Carbon::parse($this->created_at)->format('d/m/Y') : '--/--/----',
            'creator' => $this->creator ? : getSubstituteForDeleted(),
            'status' => $this->status->value,
            'status_label' => $this->status->description,
            'status_label_class' => CommonStatus::getLabelClass($this->status->value),
            'files'    => $this->files,
            'comments' => $this->comments,
            'devices'  => $this->devices,
        ];
    }
}