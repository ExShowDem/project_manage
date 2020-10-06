<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\DeviceProjectResource as Resource;
use App\Services\Api\DeviceProjectService as Service;
use App\Http\Resources\ProcessLogResource;
use App\Http\Requests\Api\DeviceProjectRequest;
use App\Models\DeviceToProject;
use Illuminate\Support\Facades\DB;

class DeviceToProjectController extends BaseController
{
    protected $service;
    protected $module = 'device_project';

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
    public function store(DeviceProjectRequest $request)
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
    public function update(DeviceProjectRequest $request, $id)
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

    public function tracking($id)
    {
        $items = $this->service->tracking($id);

        return $this->responseSuccess(ProcessLogResource::collection($items));
    }

    public function getDevicesByDevicesToProjectId($devicesToProjectId)
    {
        $devicesToProject = DeviceToProject::findOrFail($devicesToProjectId);
        $projectDevices = $devicesToProject->devices;

        // Returned
        $companyDevices = collect(
            DB::select('SELECT devices_id, SUM(quantity_returned) as sum_quantity_returned FROM device_company_devices JOIN devices_return_to_company ON devices_return_to_company.id = device_company_devices.device_company_id AND devices_return_to_company.deleted_at IS NULL WHERE devices_return_to_company.project_id = '.$devicesToProject->project_id.' GROUP BY devices_id')
        )
            ->keyBy('devices_id')
            ->toArray();

        foreach ($projectDevices as &$projectDevice)
        {
            if (isset($companyDevices[ $projectDevice->pivot->devices_id ]))
            {
                $projectDevice->pivot->quantity -= $companyDevices[ $projectDevice->pivot->devices_id ]->sum_quantity_returned;
            }
        }

        return $this->responseSuccess($projectDevices);
    }
}
