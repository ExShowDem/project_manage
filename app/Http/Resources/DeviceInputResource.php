<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Enums\CommonStatus;

class DeviceInputResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        // $deviceModel   = resolve('App\Models\Devices');
        // $tally         = $deviceModel->getExistingTally();
        // $projectId     = $this->project->id ?? null;
        // $this->devices = $deviceModel->includeExisting($this->devices, $tally, $projectId);

        return [
            'id'             => $this->id,
            'code'           => $this->code,
            'company'        => $this->company,
            'project' => $this->project ? : getSubstituteForDeleted(),
            'creator' => $this->creator ? : getSubstituteForDeleted(),
			'created_date'   => $this->created_date ? Carbon::parse($this->created_date)->format('d/m/Y') : '--/--/----',
            'status'             => $this->status->value,
            'status_label'       => $this->status->description,
            'status_label_class' => CommonStatus::getLabelClass($this->status->value),
            'files'    => $this->files,
            'comments' => $this->comments,
            'devices'  => $this->devices,
            'reason'   => $this->reason,
            'purchaseRequest'   => $this->purchase_request_id ? ( $this->purchaseRequest ?? getSubstituteForDeleted() ) : [],
        ];
    }
}