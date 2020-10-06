<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DeviceToProject;
use App\Models\DeviceReturnToCompany;
use Carbon\Carbon;
use App\Enums\CommonStatus;

class DeviceBillController extends BaseController
{
    protected $module = 'device_bill';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
                'device_transfers.name AS code', //@todo change to name later
                'device_transfers.created_at AS date', 
                'devices.name AS device_name', 
                'devices.code AS device_code', 
                DB::raw("1 as type"),
                'projects.name AS project',
                'projects.id AS project_id',
                'units.name AS unit',
                'devices.unit_price AS unit_price'
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
                'device_transfers.name AS code', //@todo change to name later
                'device_transfers.created_at AS date', 
                'devices.name AS device_name', 
                'devices.code AS device_code', 
                DB::raw("0 as type"),
                'projects.name AS project',
                'projects.id AS project_id',
                'units.name AS unit',
                'devices.unit_price AS unit_price'
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
                'units.name AS unit',
                'devices.unit_price AS unit_price'
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
                'units.name AS unit',
                'devices.unit_price AS unit_price'
            );

        $params = $request->only('search_option');

        if (isset($params['search_option']['keyword'])) 
        {
            $losses = $losses->whereNested(function($l) use ($params) {
                $l
                    ->where('devices.code', 'like', '%' . $params['search_option']['keyword'] . '%')
                    ->orWhere('devices.name', 'like', '%' . $params['search_option']['keyword'] . '%');
            });

            $gains = $gains->whereNested(function($g) use ($params) {
                $g
                    ->where('devices.code', 'like', '%' . $params['search_option']['keyword'] . '%')
                    ->orWhere('devices.name', 'like', '%' . $params['search_option']['keyword'] . '%');
            });

            $inputs = $inputs->whereNested(function($i) use ($params) {
                $i
                    ->where('devices.code', 'like', '%' . $params['search_option']['keyword'] . '%')
                    ->orWhere('devices.name', 'like', '%' . $params['search_option']['keyword'] . '%');
            });

            $deletes = $deletes->whereNested(function($d) use ($params) {
                $d
                    ->where('devices.code', 'like', '%' . $params['search_option']['keyword'] . '%')
                    ->orWhere('devices.name', 'like', '%' . $params['search_option']['keyword'] . '%');
            });
        }

        if (isset($params['search_option']['from_date']) && isset($params['search_option']['till_date'])) 
        {
            $losses = $losses
                ->where('device_transfers.created_at', '>=', carbon_date($params['search_option']['from_date'])->startOfDay())
                ->where('device_transfers.created_at', '<=', carbon_date($params['search_option']['till_date'])->endOfDay());
            $gains = $gains
                ->where('device_transfers.created_at', '>=', carbon_date($params['search_option']['from_date'])->startOfDay())
                ->where('device_transfers.created_at', '<=', carbon_date($params['search_option']['till_date'])->endOfDay());
            $inputs = $inputs
                ->where('devices_input.created_at', '>=', carbon_date($params['search_option']['from_date'])->startOfDay())
                ->where('devices_input.created_at', '<=', carbon_date($params['search_option']['till_date'])->endOfDay());
            $deletes = $deletes
                ->where('device_clearances.created_at', '>=', carbon_date($params['search_option']['from_date'])->startOfDay())
                ->where('device_clearances.created_at', '<=', carbon_date($params['search_option']['till_date'])->endOfDay());
        }

        if (isset($params['search_option']['project'])) 
        {
            $losses = $losses->where('projects.id', '=', $params['search_option']['project']);
            $gains  = $gains->where('projects.id', '=', $params['search_option']['project']);
            $inputs = $inputs->where('projects.id', '=', $params['search_option']['project']);
            $deletes = $deletes->where('projects.id', '=', $params['search_option']['project']);
        }

        $transfers = $gains->union($losses)
            ->orderBy('devices_id', 'desc')
            ->orderBy('project_id', 'desc')
            ->orderBy('date', 'asc')
            ->orderBy('type', 'desc')
            ->get()
            ->toArray();

        $inputs = $inputs
            ->orderBy('devices_id', 'desc')
            ->orderBy('project_id', 'desc')
            ->orderBy('date', 'asc')
            ->orderBy('type', 'desc')
            ->get()
            ->toArray();

        $deletes = $deletes
            ->orderBy('devices_id', 'desc')
            ->orderBy('project_id', 'desc')
            ->orderBy('date', 'asc')
            ->orderBy('type', 'desc')
            ->get()
            ->toArray();

        $tally = [];

        foreach ($transfers as $transfer) 
        {
            if (isset($tally[$transfer->devices_id]))
            {
                if (isset($tally[$transfer->devices_id]['transfers']['projects'][$transfer->project_id]))
                {
                    $tally[$transfer->devices_id]['transfers']['projects'][$transfer->project_id]['info'][] = [
                        'type' => $transfer->type,
                        'type_label' => ($transfer->type === 1) ? 'Tăng' : 'Giảm',
                        'unit' => $transfer->unit,
                        'date' => $transfer->date,
                        'quantity' => $transfer->quantity,
                        'unit_price' => $transfer->unit_price,
                        'total_price' => $transfer->unit_price * $transfer->quantity,
                    ];

                    if ($transfer->type === 1)
                    { // increase
                        $tally[$transfer->devices_id]['total_quantity'] += $transfer->quantity;
                    }
                    else
                    { // decrease
                        $tally[$transfer->devices_id]['total_quantity'] -= $transfer->quantity;
                    }
                }
                else
                {
                    $tally[$transfer->devices_id]['transfers']['projects'][$transfer->project_id] = [
                        'name' => $transfer->project,
                        'info' => [],
                    ];

                    $tally[$transfer->devices_id]['transfers']['projects'][$transfer->project_id]['info'][] = [
                        'type' => $transfer->type,
                        'type_label' => ($transfer->type === 1) ? 'Tăng' : 'Giảm',
                        'unit' => $transfer->unit,
                        'date' => $transfer->date,
                        'quantity' => $transfer->quantity,
                        'unit_price' => $transfer->unit_price,
                        'total_price' => $transfer->unit_price * $transfer->quantity,
                    ];

                    if ($transfer->type === 1)
                    { // increase
                        $tally[$transfer->devices_id]['total_quantity'] += $transfer->quantity;
                    }
                    else
                    { // decrease
                        $tally[$transfer->devices_id]['total_quantity'] -= $transfer->quantity;
                    }
                }
            }
            else
            {
                $tally[$transfer->devices_id] = [
                    'device_name' => $transfer->device_name,
                    'device_code' => $transfer->device_code,
                    'total_quantity' => 0,
                ];

                $tally[$transfer->devices_id]['transfers']['projects'][$transfer->project_id] = [
                    'name' => $transfer->project,
                    'info' => [],
                ];

                $tally[$transfer->devices_id]['transfers']['projects'][$transfer->project_id]['info'][] = [
                    'type' => $transfer->type,
                    'type_label' => ($transfer->type === 1) ? 'Tăng' : 'Giảm',
                    'unit' => $transfer->unit,
                    'date' => $transfer->date,
                    'quantity' => $transfer->quantity,
                    'unit_price' => $transfer->unit_price,
                    'total_price' => $transfer->unit_price * $transfer->quantity,
                ];

                if ($transfer->type === 1)
                { // increase
                    $tally[$transfer->devices_id]['total_quantity'] += $transfer->quantity;
                }
                else
                { // decrease
                    $tally[$transfer->devices_id]['total_quantity'] -= $transfer->quantity;
                }
            }
        }

        foreach ($inputs as $input) 
        {
            if (isset($tally[$input->devices_id]))
            {
                if (isset($tally[$input->devices_id]['inputs']['projects'][$input->project_id]))
                {
                    $tally[$input->devices_id]['inputs']['projects'][$input->project_id]['info'][] = [
                        'type' => $input->type,
                        'type_label' => ($input->type === 1) ? 'Tăng' : 'Giảm',
                        'unit' => $input->unit,
                        'date' => $input->date,
                        'quantity' => $input->quantity,
                        'unit_price' => $input->unit_price,
                        'total_price' => $input->unit_price * $input->quantity,
                    ];

                    $tally[$input->devices_id]['total_quantity'] += $input->quantity;
                }
                else
                {
                    $tally[$input->devices_id]['inputs']['projects'][$input->project_id] = [
                        'name' => $input->project,
                        'info' => [],
                    ];

                    $tally[$input->devices_id]['inputs']['projects'][$input->project_id]['info'][] = [
                        'type' => $input->type,
                        'type_label' => ($input->type === 1) ? 'Tăng' : 'Giảm',
                        'unit' => $input->unit,
                        'date' => $input->date,
                        'quantity' => $input->quantity,
                        'unit_price' => $input->unit_price,
                        'total_price' => $input->unit_price * $input->quantity,
                    ];

                    $tally[$input->devices_id]['total_quantity'] += $input->quantity;
                }
            }
            else
            {
                $tally[$input->devices_id] = [
                    'device_name' => $input->device_name,
                    'device_code' => $input->device_code,
                    'total_quantity' => 0,
                ];

                $tally[$input->devices_id]['inputs']['projects'][$input->project_id] = [
                    'name' => $input->project,
                    'info' => [],
                ];

                $tally[$input->devices_id]['inputs']['projects'][$input->project_id]['info'][] = [
                    'type' => $input->type,
                    'type_label' => ($input->type === 1) ? 'Tăng' : 'Giảm',
                    'unit' => $input->unit,
                    'date' => $input->date,
                    'quantity' => $input->quantity,
                    'unit_price' => $input->unit_price,
                    'total_price' => $input->unit_price * $input->quantity,
                ];

                $tally[$input->devices_id]['total_quantity'] += $input->quantity;
            }
        }

        foreach ($deletes as $deletion) 
        {
            if (isset($tally[$deletion->devices_id]))
            {
                if (isset($tally[$deletion->devices_id]['deletes']['projects'][$deletion->project_id]))
                {
                    $tally[$deletion->devices_id]['deletes']['projects'][$deletion->project_id]['info'][] = [
                        'type' => $deletion->type,
                        'type_label' => 'Thanh Lý',
                        'unit' => $deletion->unit,
                        'date' => $deletion->date,
                        'quantity' => $deletion->quantity,
                        'unit_price' => $deletion->unit_price,
                        'total_price' => $deletion->unit_price * $deletion->quantity,
                    ];

                    $tally[$deletion->devices_id]['total_quantity'] += $deletion->quantity;
                }
                else
                {
                    $tally[$deletion->devices_id]['deletes']['projects'][$deletion->project_id] = [
                        'name' => $deletion->project,
                        'info' => [],
                    ];

                    $tally[$deletion->devices_id]['deletes']['projects'][$deletion->project_id]['info'][] = [
                        'type' => $deletion->type,
                        'type_label' => 'Thanh Lý',
                        'unit' => $deletion->unit,
                        'date' => $deletion->date,
                        'quantity' => $deletion->quantity,
                        'unit_price' => $deletion->unit_price,
                        'total_price' => $deletion->unit_price * $deletion->quantity,
                    ];

                    $tally[$deletion->devices_id]['total_quantity'] += $deletion->quantity;
                }
            }
            else
            {
                $tally[$deletion->devices_id] = [
                    'device_name' => $deletion->device_name,
                    'device_code' => $deletion->device_code,
                    'total_quantity' => 0,
                ];

                $tally[$deletion->devices_id]['deletes']['projects'][$deletion->project_id] = [
                    'name' => $deletion->project,
                    'info' => [],
                ];

                $tally[$deletion->devices_id]['deletes']['projects'][$deletion->project_id]['info'][] = [
                    'type' => $deletion->type,
                    'type_label' => 'Thanh Lý',
                    'unit' => $deletion->unit,
                    'date' => $deletion->date,
                    'quantity' => $deletion->quantity,
                    'unit_price' => $deletion->unit_price,
                    'total_price' => $deletion->unit_price * $deletion->quantity,
                ];

                $tally[$deletion->devices_id]['total_quantity'] += $deletion->quantity;
            }
        }

        foreach ($tally as $key1 => &$value1) 
        {
            if (isset($value1['transfers']))
            {
                foreach ($value1['transfers']['projects'] as &$tproject) 
                {
                    foreach ($tproject['info'] as $tinfoKey => &$tinfoItem) 
                    {
                        if ($tinfoItem['type'] === 1)
                        {
                            $tinfoItem['days_used'] = Carbon::now()->diffInDays(Carbon::parse($tinfoItem['date']));
                        }
                        else
                        {
                            if ($tproject['info'][0]['type'] === 1)
                            {
                                $borrowedDate = Carbon::parse($tproject['info'][0]['date']);
                                $returnedDate = Carbon::parse($tinfoItem['date']);

                                $tinfoItem['days_used'] = $returnedDate->diffInDays($borrowedDate);
                            }
                            else
                            {
                                $tinfoItem['days_used'] = '';
                            }
                        }
                    }
                }
            }

            if (isset($value1['inputs']))
            {
                foreach ($value1['inputs']['projects'] as &$iproject) 
                {
                    foreach ($iproject['info'] as &$iinfoItem) 
                    {
                        $iinfoItem['days_used'] = 0;
                    }
                }
            }

            if (isset($value1['deletes']))
            {
                foreach ($value1['deletes']['projects'] as &$iproject) 
                {
                    foreach ($iproject['info'] as &$iinfoItem) 
                    {
                        $iinfoItem['days_used'] = 0;
                    }
                }
            }
        }

        $urlPath   = 'api/devices/bill';
        $projectId = $request->input('project_id') ? : 1;
        $page      = (int) $request->input('page') ? : 1;
        $pageSize  = ($pageSizeInput = (int) $request->input('page_size') > 0) ? min($pageSizeInput, config('api.pagination.max_per_page')) : config('api.pagination.per_page');
        $noOfPages = ceil((float) count($tally) / (float) $pageSize);

        $prevPageNo = ($page - 1 === 0) ? null : $page - 1;
        $nextPageNo = ($page + 1 > $noOfPages) ? null : $page + 1;
        $prevPageLink = is_null($prevPageNo) ? null : config('app.url') . $urlPath . '?project_id=' . $projectId . '&page=' . $prevPageNo;
        $nextPageLink = is_null($nextPageNo) ? null : config('app.url') . $urlPath . '?project_id=' . $projectId . '&page=' . $nextPageNo;

        $links = [
            'first' => config('app.url') . $urlPath . '?project_id=' . $projectId . '&page=1',
            'last'  => config('app.url') . $urlPath . '?project_id=' . $projectId . '&page=' . $noOfPages,
            'prev'  => ($noOfPages === 1) ? null : $prevPageLink,
            'next'  => ($noOfPages === 1) ? null : $nextPageLink,
        ];

        $meta = [
            'from' => ($noOfPages === 1) ? 1 : (($page - 1) * $pageSize) + 1,
            'to'   => ($noOfPages === 1) ? count($tally) : ($page * $pageSize > count($tally)) ? count($tally) : $page * $pageSize,
            'current_page' => $page,
            'last_page'    => $noOfPages,
            'path'         => config('app.url') . $urlPath,
            'per_page'     => $pageSize,
            'total'        => ($noOfPages === 1) ? count($tally) % $pageSize : ($page * $pageSize > count($tally)) ? count($tally) % $pageSize : $pageSize,
        ];

        $response          = [];
        $response['data']  = array_slice($tally, $meta['from'] - 1, ($meta['to'] - $meta['from'] + 1), false);        
        $response['links'] = (object) $links;        
        $response['meta']  = (object) $meta;        

        return $this->responseSuccess($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
