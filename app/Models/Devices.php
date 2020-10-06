<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Enums\CommonStatus;

class Devices extends BaseModel
{
    protected $table = 'devices';

    protected $fillable = [
        'name',
        'parent_id',
        'unit_id',
        'code',
        'project_id',
        'description',
        'unit_price',
    ];

    protected $with = [
        'unit',
        'deviceDetail'
    ];

    public function parent()
    {
        return $this->hasOne(Devices::class, 'id', 'parent_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function deviceDetail()
    {
        return $this->hasOne(DeviceDetail::class, 'devices_id');
    }

    public function getExistingTally($searchOption = null)
    {
        $gains = DB::table('device_transfer_devices')
            ->join('device_transfers', 'device_transfers.id', '=', 'device_transfer_devices.device_transfer_id')
            ->join('projects', 'projects.id', '=', 'device_transfer_devices.to_project')
            ->join('devices', 'devices.id', '=', 'device_transfer_devices.devices_id')
            ->join('units', 'devices.unit_id', '=', 'units.id')
            ->where('device_transfers.status', CommonStatus::APPROVED)
            ->whereNull('device_transfers.deleted_at')
            ->select(
                'device_transfer_devices.id', 
                'device_transfer_devices.devices_id', 
                'device_transfer_devices.quantity AS quantity', 
                'device_transfers.name AS code', //change to name later
                'device_transfers.created_at AS date', 
                'devices.name AS device_name', 
                'devices.code AS device_code', 
                DB::raw("1 as type"),
                'projects.name AS project',
                'projects.id AS project_id',
                'units.name AS unit'
            );

        $losses = DB::table('device_transfer_devices')
            ->join('device_transfers', 'device_transfers.id', '=', 'device_transfer_devices.device_transfer_id')
            ->join('projects', 'projects.id', '=', 'device_transfer_devices.from_project')
            ->join('devices', 'devices.id', '=', 'device_transfer_devices.devices_id')
            ->join('units', 'devices.unit_id', '=', 'units.id')
            ->where('device_transfers.status', CommonStatus::APPROVED)
            ->whereNull('device_transfers.deleted_at')
            ->select(
                'device_transfer_devices.id', 
                'device_transfer_devices.devices_id', 
                'device_transfer_devices.quantity AS quantity', 
                'device_transfers.name AS code', //change to name later
                'device_transfers.created_at AS date', 
                'devices.name AS device_name', 
                'devices.code AS device_code', 
                DB::raw("0 as type"),
                'projects.name AS project',
                'projects.id AS project_id',
                'units.name AS unit'
            );

        $inputs = DB::table('device_input_devices')
            ->join('devices_input', 'devices_input.id', '=', 'device_input_devices.device_input_id')
            ->join('projects', 'projects.id', '=', 'devices_input.project_id')
            ->join('devices', 'devices.id', '=', 'device_input_devices.devices_id')
            ->join('units', 'devices.unit_id', '=', 'units.id')
            ->where('devices_input.status', CommonStatus::APPROVED)
            ->whereNull('devices_input.deleted_at')
            ->select(
                'device_input_devices.id', 
                'device_input_devices.devices_id', 
                'device_input_devices.quantity AS quantity', 
                'devices_input.code AS code', 
                'devices_input.created_at AS date', 
                'devices.name AS device_name', 
                'devices.code AS device_code', 
                DB::raw("1 as type"),
                'projects.name AS project',
                'projects.id AS project_id',
                'units.name AS unit'
            );

        $deletes = DB::table('device_clearance_devices')
            ->join('device_clearances', 'device_clearances.id', '=', 'device_clearance_devices.device_clearance_id')
            ->join('projects', 'projects.id', '=', 'device_clearances.project_id')
            ->join('devices', 'devices.id', '=', 'device_clearance_devices.devices_id')
            ->join('units', 'devices.unit_id', '=', 'units.id')
            ->where('device_clearances.status', CommonStatus::APPROVED)
            ->whereNull('device_clearances.deleted_at')
            ->select(
                'device_clearance_devices.id', 
                'device_clearance_devices.devices_id', 
                'device_clearance_devices.quantity AS quantity', 
                'device_clearances.code AS code', 
                'device_clearances.created_at AS date', 
                'devices.name AS device_name', 
                'devices.code AS device_code', 
                DB::raw("0 as type"),
                'projects.name AS project',
                'projects.id AS project_id',
                'units.name AS unit'
            );

        if ($searchOption) 
        {
            if (isset($searchOption['keyword'])) 
            {
                $keyword = $searchOption['keyword'];

                $losses = $losses->whereNested(function($l) use ($keyword) {
                    $l
                        ->where('devices.code', 'like', '%' . $keyword . '%')
                        ->orWhere('devices.name', 'like', '%' . $keyword . '%');
                });

                $gains = $gains->whereNested(function($g) use ($keyword) {
                    $g
                        ->where('devices.code', 'like', '%' . $keyword . '%')
                        ->orWhere('devices.name', 'like', '%' . $keyword . '%');
                });

                $inputs = $inputs->whereNested(function($i) use ($keyword) {
                    $i
                        ->where('devices.code', 'like', '%' . $keyword . '%')
                        ->orWhere('devices.name', 'like', '%' . $keyword . '%');
                });

                $deletes = $deletes->whereNested(function($d) use ($keyword) {
                    $d
                        ->where('devices.code', 'like', '%' . $keyword . '%')
                        ->orWhere('devices.name', 'like', '%' . $keyword . '%');
                });
            }

            if (isset($searchOption['project'])) 
            {
                $project = $searchOption['project'];
                
                $losses  = $losses->where('projects.id', '=', $project);
                $gains   = $gains->where('projects.id', '=', $project);
                $inputs  = $inputs->where('projects.id', '=', $project);
                $deletes = $deletes->where('projects.id', '=', $project);
            }
        }

        $raw = $gains->union($losses)
            ->union($inputs)
            ->union($deletes)
            ->orderBy('devices_id', 'desc')
            ->orderBy('project_id', 'desc')
            ->get()
            ->toArray();

        $tally1 = [];

        foreach ($raw as $key => $value) 
        {
            if (isset($tally1[$value->devices_id]))
            {
                if (isset($tally1[$value->devices_id]['projects'][$value->project_id]))
                {
                    if ($value->type === 1)
                    { // increase
                        $tally1[$value->devices_id]['total_quantity'] += $value->quantity;
                        $tally1[$value->devices_id]['projects'][$value->project_id]['quantity'] += $value->quantity;
                    }
                    else
                    { // decrease
                        $tally1[$value->devices_id]['total_quantity'] -= $value->quantity;
                        $tally1[$value->devices_id]['projects'][$value->project_id]['quantity'] -= $value->quantity;
                    }
                }
                else
                {
                    $tally1[$value->devices_id]['projects'][$value->project_id] = [
                        'name' => $value->project,
                        'quantity' => 0,
                    ];

                    if ($value->type === 1)
                    { // increase
                        $tally1[$value->devices_id]['total_quantity'] += $value->quantity;
                        $tally1[$value->devices_id]['projects'][$value->project_id]['quantity'] += $value->quantity;
                    }
                    else
                    { // decrease
                        $tally1[$value->devices_id]['total_quantity'] -= $value->quantity;
                        $tally1[$value->devices_id]['projects'][$value->project_id]['quantity'] -= $value->quantity;
                    }
                }
            }
            else
            {
                $tally1[$value->devices_id] = [
                    'device_name' => $value->device_name,
                    'device_code' => $value->device_code,
                    'total_quantity' => 0,
                    'unit' => $value->unit,
                    'projects'    => [],
                ];

                $tally1[$value->devices_id]['projects'][$value->project_id] = [
                    'name' => $value->project,
                    'quantity' => 0,
                ];

                if ($value->type === 1)
                { // increase
                    $tally1[$value->devices_id]['total_quantity'] += $value->quantity;
                    $tally1[$value->devices_id]['projects'][$value->project_id]['quantity'] += $value->quantity;
                }
                else
                { // decrease
                    $tally1[$value->devices_id]['total_quantity'] -= $value->quantity;
                    $tally1[$value->devices_id]['projects'][$value->project_id]['quantity'] -= $value->quantity;
                }
            }
        }

        // Megaon.xxx."SL đang có" 
        list($inputDevices, $projectDevices, $companyDevices, $clearanceDevices) = get_existing_device_quantities();

        foreach ($tally1 as $key1 => $value1) 
        {
            if (isset($inputDevices[$key1]))
            {
                $tally1[$key1]['existing_quantity'] = $inputDevices[$key1]->sum_quantity;
            }
            else
            {
                $tally1[$key1]['existing_quantity'] = 0;
            }

            if (isset($projectDevices[$key1]) && $tally1[$key1]['existing_quantity'] > 0)
            {
                $tally1[$key1]['existing_quantity'] -= $projectDevices[$key1]->sum_quantity;
            }

            if (isset($companyDevices[$key1]))
            {
                $tally1[$key1]['existing_quantity'] += $companyDevices[$key1]->sum_quantity_returned;
            }

            if (isset($clearanceDevices[$key1]) && $tally1[$key1]['existing_quantity'] > 0)
            {
                $tally1[$key1]['existing_quantity'] -= $clearanceDevices[$key1]->sum_quantity;
            }

            // Readjust sums
            $tally1[$key1]['total_quantity'] += $tally1[$key1]['existing_quantity'];

            // Readjust sums for Luan chuyen thiet bi
            if (isset($tally1[$key1]['projects'][1]))
            {
                $tally1[$key1]['existing_quantity'] += $tally1[$key1]['projects'][1]['quantity'];
                unset($tally1[$key1]['projects'][1]);
            }
        }

        return $tally1;
    }

    public function includeExisting($devices, $tally, $projectId)
    {
        foreach ($devices as $key => $device) 
        {
            if (isset($tally[$device->pivot->devices_id]))
            {
                if ($projectId)
                {
                    if ($projectId === 1)
                    {
                        $devices[$key]->pivot->existing_quantity = $tally[$device->pivot->devices_id]['existing_quantity'] ?? 0;
                    }
                    else
                    {
                        if (isset($tally[$device->pivot->devices_id]['projects'][$projectId]))
                        {
                            $devices[$key]->pivot->existing_quantity = $tally[$device->pivot->devices_id]['projects'][$projectId]['quantity'] ?? 0;
                        }
                        else
                        {
                            $devices[$key]->pivot->existing_quantity = 0;
                        }
                    }
                }
                else
                {
                    $devices[$key]->pivot->existing_quantity = 0;
                }
            }
            else
            {
                $devices[$key]->pivot->existing_quantity = 0;
            }
        }

        return $devices;
    }
}
