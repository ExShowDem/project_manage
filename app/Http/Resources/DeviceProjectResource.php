<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Enums\CommonStatus;

class DeviceProjectResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->project)
        {
            $borrowed = DB::table('device_project_devices')
                ->join('devices_to_project', 'devices_to_project.id', '=', 'device_project_devices.device_project_id')
                ->where('device_project_devices.device_project_id', $this->id)
                ->where('devices_to_project.project_id', $this->project->id)
                ->pluck('quantity')
                ->toArray();

            $totalBorrowed = 0;

            foreach ($borrowed as $borrowedQuantity) 
            {
                $totalBorrowed += $borrowedQuantity;
            }

            $returned = DB::table('device_company_devices')
                ->join('devices_return_to_company', 'devices_return_to_company.id', '=', 'device_company_devices.device_company_id')
                ->where('devices_return_to_company.devices_to_project_id', $this->id)
                ->where('devices_return_to_company.project_id', $this->project->id)
                ->pluck('quantity_returned')
                ->toArray();

            $totalReturned = 0;

            foreach ($returned as $returnedQuantity) 
            {
                $totalReturned += $returnedQuantity;
            }

            $progress = 100 - (($totalBorrowed - $totalReturned) / $totalBorrowed * 100);
            $progress = number_format((float) $progress, 2, '.', '');
        }
        else
        { // When the project is deleted, all above logic is non-applicable
            $progress = 0;
        }

        return [
            'id'             => $this->id,
            'code'           => $this->code,
            'company'        => $this->company,
            'project'        => $this->project ? : getSubstituteForDeleted(),
            'creator'        => $this->creator ? : getSubstituteForDeleted(),
			'created_date'   => $this->created_date ? Carbon::parse($this->created_date)->format('d/m/Y') : '--/--/----',
            'status'             => $this->status->value,
            'status_label'       => $this->status->description,
            'status_label_class' => CommonStatus::getLabelClass($this->status->value),
            'files'    => $this->files,
            'comments' => $this->comments,
            'devices'  => $this->devices,
            'progress' => $progress,
        ];
    }
}