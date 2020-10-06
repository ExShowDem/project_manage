<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\PlanSupplyRequest;
use App\Http\Resources\PlanResource;
use App\Http\Resources\ProcessLogDetailResource;
use App\Http\Resources\ProcessLogResource;
use App\Models\ProcessLog;
use App\Services\Api\AttachFileService;
use App\Services\Api\CommentService;
use App\Services\Api\PlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ProcessLogController extends BaseController
{
    public function show(ProcessLog $processLog)
    {
        return $this->responseSuccess(new ProcessLogDetailResource($processLog));
    }
}
