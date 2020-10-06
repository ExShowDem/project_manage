<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Enums\CommonStatus;

class DeviceMonthlyEstimatesResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $typeMap = DB::table('device_monthly_estimate_types')
            ->select('id', 'name')
            ->get()
            ->keyBy('id')
            ->toArray();

        // $accumulated = DB::table('device_monthly_estimate_devices')
        //     ->join('device_monthly_estimates', 'device_monthly_estimates.id', '=', 'device_monthly_estimate_devices.device_monthly_estimate_id');

        // if ($request->input('search_option'))
        // {
        //     $searchOption = $request->input('search_option');
        // }

        // if (isset($searchOption['current_project_id']))
        // {
        //     $accumulated = $accumulated->where('device_monthly_estimates.project_id', '=', $searchOption['current_project_id']);
        // }

        // $accumulated = $accumulated
        //     ->where('device_monthly_estimates.status', CommonStatus::APPROVED)
        //     ->select('devices_id', DB::raw("SUM(quantity) AS accumulated_quantity"))
        //     ->groupBy('devices_id')
        //     ->get()
        //     ->keyBy('devices_id')
        //     ->toArray();

        foreach ($this->devices as $key => $device) 
        {
            $this->devices[$key]->pivot->type_text = $typeMap[$device->pivot->type]->name;

            // if (isset($accumulated[$device->pivot->devices_id]))
            // {
            //     $this->devices[$key]->pivot->accumulated_quantity = (int) $accumulated[$device->pivot->devices_id]->accumulated_quantity;
            // }
        }

        return [
            'id'             => $this->id,
            'code'           => $this->code,
            'name'           => $this->name,
            'intention'      => $this->intention,
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