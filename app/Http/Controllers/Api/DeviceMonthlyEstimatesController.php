<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\DeviceMonthlyEstimatesResource as Resource;
use App\Services\Api\DeviceMonthlyEstimatesService as Service;
use App\Http\Resources\ProcessLogResource;
use App\Http\Requests\Api\DeviceMonthlyEstimatesRequest;
use App\Models\DeviceMonthlyEstimate;
use Illuminate\Support\Facades\DB;
use App\Enums\CommonStatus;

class DeviceMonthlyEstimatesController extends BaseController
{
    protected $service;
    protected $module = 'device_monthly_estimates';

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->only('per_page', 'search_option', 'project_id');

        $items = Resource::apiPaginate($this->service->getListItems($params), $request);

        return $this->responseSuccess($items);
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
    public function store(DeviceMonthlyEstimatesRequest $request)
    {
        $inputs = $request->all();

        return parent::genericSave($request, $inputs, 'create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = $this->service->find($id, ['files', 'comments.user']);

        if ($item)
        {
            return $this->responseSuccess(new Resource($item));
        }
        else
        {
            return $this->responseError('api.code.common.show_failed');
        }
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
    public function update(DeviceMonthlyEstimatesRequest $request, $id)
    {
        $inputs = $request->all();

        return parent::genericSave($request, $inputs, 'update', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->service->delete($id);

        if ($result)
        {
            return $this->responseSuccess();
        }

        return $this->responseError('api.code.common.delete_failed');
    }

    public function getDevicesByDevicesMonthlyEstimatesId(Request $request, $monthlyEstimatesId)
    {
        $monthlyEstimates = DeviceMonthlyEstimate::findOrFail($monthlyEstimatesId);
        $monthlyEstimatesDevices = $monthlyEstimates->devices;

        $accumulated = DB::table('device_issuance_devices')
            ->join('device_issuances', 'device_issuances.id', '=', 'device_issuance_devices.device_issuance_id')
            ->whereNull('device_issuances.deleted_at')
            ->where('device_issuances.status', CommonStatus::APPROVED)
            ->where('device_issuances.project_id', $request->input('project_id'))
            ->select('devices_id', DB::raw("SUM(quantity) AS accumulated_quantity"))
            ->groupBy('devices_id')
            ->get()
            ->keyBy('devices_id')
            ->toArray();

        $total = DB::table('device_estimate_devices')
            ->join('device_estimates', 'device_estimates.id', '=', 'device_estimate_devices.device_estimate_id')
            ->whereNull('device_estimates.deleted_at')
            ->where('device_estimates.status', CommonStatus::APPROVED)
            ->where('device_estimates.project_id', $request->input('project_id'))
            ->select('devices_id', DB::raw("SUM(mass) AS total_quantity"))
            ->groupBy('devices_id')
            ->get()
            ->keyBy('devices_id')
            ->toArray();

        foreach ($monthlyEstimatesDevices as $key => $monthlyEstimatesDevice)
        {
            $monthlyEstimatesDevices[$key]->pivot->total_quantity = 0;

            if (isset($accumulated[$monthlyEstimatesDevice->pivot->devices_id]))
            {
                $monthlyEstimatesDevices[$key]->pivot->accumulated_quantity = (int) $accumulated[$monthlyEstimatesDevice->pivot->devices_id]->accumulated_quantity;
            }
            else
            {
                $monthlyEstimatesDevices[$key]->pivot->accumulated_quantity = 0;
            }

            if (isset($total[$monthlyEstimatesDevice->pivot->devices_id]))
            {
                $monthlyEstimatesDevices[$key]->pivot->total_quantity = (int) $total[$monthlyEstimatesDevice->pivot->devices_id]->total_quantity;
            }
        }

        return $this->responseSuccess($monthlyEstimatesDevices);
    }

    public function tracking($id)
    {
        $items = $this->service->tracking($id);

        return $this->responseSuccess(ProcessLogResource::collection($items));
    }
}
