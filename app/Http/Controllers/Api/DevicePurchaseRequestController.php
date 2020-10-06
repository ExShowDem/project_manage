<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\DevicePurchaseRequestResource as Resource;
use App\Services\Api\DevicePurchaseRequestService as Service;
use App\Http\Resources\ProcessLogResource;
use App\Http\Requests\Api\DevicePurchaseRequestRequest;
use App\Models\DevicePurchaseRequest;
use Illuminate\Support\Facades\DB;

class DevicePurchaseRequestController extends BaseController
{
    protected $service;
    protected $module = 'device_purchase_request';

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
    public function store(DevicePurchaseRequestRequest $request)
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
    public function update(DevicePurchaseRequestRequest $request, $id)
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

    public function getDevicesByDevicesPurchaseRequestId(Request $request, $devicesPurchaseRequestId)
    {
        $searchOptions = $request->only('search_option');
        $searchOptions = $searchOptions['search_option'] ?? null;

        $devicePurchaseRequest = DevicePurchaseRequest::findOrFail($devicesPurchaseRequestId);
        $purchaseRequestDevices = $devicePurchaseRequest->devices;

        $relatedPurchaseIds = DB::table('device_purchases')
            ->where('request_id', $devicesPurchaseRequestId)
            ->pluck('id')
            ->toArray();

        $bought = DB::table('device_purchase_devices')
            ->whereIn('device_purchase_id', $relatedPurchaseIds)
            ->select('devices_id', DB::raw("SUM(quantity) AS quantity"))
            ->groupBy('devices_id')
            ->get()
            ->keyBy('devices_id')
            ->toArray();

        foreach ($purchaseRequestDevices as $key => $purchaseRequestDevice) 
        {
            $currDevId = (int) $purchaseRequestDevice->pivot->devices_id;

            if (isset($bought[$currDevId]))
            {
                $boughtQuantity = (int) $bought[$currDevId]->quantity;
                $purchaseRequestDevices[$key]->pivot->quantity -= $boughtQuantity;
            }
        }

        if ($searchOptions && isset($searchOptions['for_device_input']))
        {
            $deviceModel   = resolve('App\Models\Devices');
            $tally         = $deviceModel->getExistingTally();
            $projectId     = $searchOptions['current_project_id'] ?? null ;
            $purchaseRequestDevices = $deviceModel->includeExisting($purchaseRequestDevices, $tally, $projectId);
        }

        return $this->responseSuccess($purchaseRequestDevices);
    }

    public function tracking($id)
    {
        $items = $this->service->tracking($id);

        return $this->responseSuccess(ProcessLogResource::collection($items));
    }
}
