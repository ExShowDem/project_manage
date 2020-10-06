<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\DevicePurchaseResource as Resource;
use App\Services\Api\DevicePurchaseService as Service;
use App\Http\Resources\ProcessLogResource;
use App\Http\Requests\Api\DevicePurchaseRequest;
use App\Models\DevicePurchase;
use Illuminate\Support\Facades\DB;

class DevicePurchaseController extends BaseController
{
    protected $service;
    protected $module = 'device_purchase';

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
    public function store(DevicePurchaseRequest $request)
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
    public function update(DevicePurchaseRequest $request, $id)
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

    public function getDevicesByDevicesPurchaseId($devicesPurchaseId)
    {
        $devicePurchase = DevicePurchase::findOrFail($devicesPurchaseId);
        $purchaseDevices = $devicePurchase->devices;

        $relatedContractIds = DB::table('device_contracts')
            ->where('purchase_id', $devicesPurchaseId)
            ->pluck('id')
            ->toArray();

        $contracted = DB::table('device_contract_devices')
            ->whereIn('device_contract_id', $relatedContractIds)
            ->select('devices_id', DB::raw("SUM(quantity) AS quantity"))
            ->groupBy('devices_id')
            ->get()
            ->keyBy('devices_id')
            ->toArray();

        foreach ($purchaseDevices as $key => $purchaseDevice) 
        {
            $currDevId = (int) $purchaseDevice->pivot->devices_id;

            if (isset($contracted[$currDevId]))
            {
                $contractedQuantity = (int) $contracted[$currDevId]->quantity;
                $purchaseDevices[$key]->pivot->quantity -= $contractedQuantity;
            }
        }

        return $this->responseSuccess($purchaseDevices);
    }

    public function tracking($id)
    {
        $items = $this->service->tracking($id);

        return $this->responseSuccess(ProcessLogResource::collection($items));
    }
}
