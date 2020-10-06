<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Enums\CommonStatus;

class DeviceTransferResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $projectMap = DB::table('projects')
            ->select('id', 'name')
            ->get()
            ->keyBy('id')
            ->toArray();

        foreach ($this->devices as $key => $device) 
        {
            $this->devices[$key]->pivot->from_project_text = $projectMap[$device->pivot->from_project]->name;
            $this->devices[$key]->pivot->to_project_text = $projectMap[$device->pivot->to_project]->name;
        }

        return [
            'id'             => $this->id,
            'issuance'           => $this->issuance ? : getSubstituteForDeleted(),
            'name'           => $this->name,
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