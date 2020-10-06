<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\DeviceRequest;
use App\Http\Resources\DevicesResource;
use App\Services\Api\DeviceService;
use Illuminate\Http\Request;

class DevicesController extends BaseController
{
    protected $deviceService;
    protected $module = 'devices';

    public function __construct(DeviceService $deviceService)
    {
        $this->deviceService = $deviceService;
    }

    public function divideRequest(DeviceRequest $request)
    {
        $basicInputs = $request->only([
            'name',
            'parent_id',
            'unit_id',
            'code',
            'project_id',
            'description',
            'unit_price',
        ]);

        $detailInputs = $request->only([
            'material_code',
            'size',
            'specification',
            'supplier',
            'color',
            'intensity',
            'density',
            'standard',
            'source',
        ]);

        $inputs = [
            'basicInputs' => $basicInputs,
            'detailInputs' => $detailInputs,
        ];

        return $inputs;
    }

    public function index(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $devices = DevicesResource::apiPaginate($this->deviceService->getList($params), $request);

        return $this->responseSuccess($devices);
    }

    public function store(DeviceRequest $request)
    {
        $result = (object) $this->deviceService->createItem($this->divideRequest($request));

        if (isset($result->error) && $result->error === true)
        {
            $response = $this->responseError($result->key, $result->data);
        }
        elseif ($result->response) 
        {
            $response = $this->responseSuccess($result->response);
        }
        else
        {
            $response = $this->responseError('api.code.common.create_failed');
        }

        return $response;
    }

    public function show($id)
    {
        $item = $this->deviceService->find($id, ['parent', 'unit']);

        if ($item)
        {
            return $this->responseSuccess(new DevicesResource($item));
        }
        else
        {
            return $this->responseError('api.code.common.show_failed');
        }
    }


    public function update(DeviceRequest $request, $id)
    {
        $result = (object) $this->deviceService->updateItem($this->divideRequest($request), $id);

        if (isset($result->error) && $result->error === true)
        {
            $response = $this->responseError($result->key, $result->data);
        }
        elseif ($result->response) 
        {
            $response = $this->responseSuccess($result->response);
        }
        else
        {
            $response = $this->responseError('api.code.common.update_failed');
        }

        return $response;
    }


    public function destroy($id)
    {
        $result = $this->deviceService->delete($id);

        if ($result) 
        {
            return $this->responseSuccess();
        }

        return $this->responseError('api.code.common.delete_failed');
    }

    public function getExistingQuantity($projectId, $deviceId)
    {
        $deviceModel = resolve('App\Models\Devices');
        $tally       = $deviceModel->getExistingTally();
        $projectId   = (int) $projectId;

        if ($projectId === 1)
        {
            return $tally[$deviceId]['existing_quantity'] ?? 0;
        }
        else
        {
            return $tally[$deviceId]['projects'][$projectId]['quantity'] ?? 0;
        }
    }
}
