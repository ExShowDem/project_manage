<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Enums\CommonStatus;

class DevicePurchaseRequestResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $accumulated = DB::table('device_input_devices')
            ->join('devices_input', 'devices_input.id', '=', 'device_input_devices.device_input_id');

        if ($request->input('search_option'))
        {
            $searchOption = $request->input('search_option');
        }

        if (isset($searchOption['current_project_id']))
        {
            $accumulated = $accumulated
                ->where('devices_input.project_id', '=', $searchOption['current_project_id'])
                ->where('devices_input.purchase_request_id', '=', $this->id);
        }

        $accumulated = $accumulated
            ->where('devices_input.status', CommonStatus::APPROVED)
            ->select('devices_id', DB::raw("SUM(quantity) AS input_cumulative"))
            ->groupBy('devices_id')
            ->get()
            ->keyBy('devices_id')
            ->toArray();

        foreach ($this->devices as $key => $device) 
        {
            if (isset($accumulated[$device->pivot->devices_id]))
            {
                $this->devices[$key]->pivot->input_cumulative = (int) $accumulated[$device->pivot->devices_id]->input_cumulative;
            }
            else
            {
                $this->devices[$key]->pivot->input_cumulative = 0;
            }
        }

        return [
            'id'             => $this->id,
            'code'           => $this->code,
            'name'           => $this->name,
            'estimate'       => $this->estimate ? : getSubstituteForDeleted(),
            'project'        => $this->project ? : getSubstituteForDeleted(),
			'date'           => $this->created_at ? Carbon::parse($this->created_at)->format('d/m/Y') : '--/--/----',
            'creator'        => $this->creator ? : getSubstituteForDeleted(),
            'status'         => $this->status->value,
            'status_label'   => $this->status->description,
            'status_label_class' => CommonStatus::getLabelClass($this->status->value),
            'progress' => $this->progress,
            'files'    => $this->files,
            'comments' => $this->comments,
            'devices'  => $this->devices,
        ];
    }
}