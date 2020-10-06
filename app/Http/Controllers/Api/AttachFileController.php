<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AttachFileResource;
use App\Services\Api\AttachFileService;
use Illuminate\Http\Request;

class AttachFileController extends BaseController
{
    protected $service;

    public function __construct(AttachFileService $service)
    {
        $this->service = $service;
    }

    public function uploadFile(Request $request)
    {
        return $this->service->create($request);
    }

    public function deleteFile(Request $request, $id)
    {
        if ($this->service->delete($id)) {
            return $this->responseSuccess([]);
        }

        return $this->responseError('api.code.common.delete_failed');
    }

    public function history(Request $request)
    {
        $listFile = $this->service->history($request);

        return AttachFileResource::collection($listFile);
    }
}
