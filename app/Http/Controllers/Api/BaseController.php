<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;

class BaseController extends Controller
{
    protected $module = '';

    public function __construct()
    {

    }

    protected function genericSave($request, $inputs, $type, $id = 0)
    {
        if ($id === 0)
        {
            $result = (object) $this->service->$type($inputs);
        }
        else
        {
            $result = (object) $this->service->$type($id, $inputs);
        }

        if (isset($result->error) && $result->error === true)
        {
            $response = $this->responseError($result->key, $result->data);
        }
        elseif ($result->response) 
        {
            $response = $this->responseSuccess($result->response);

            $fileService    = resolve('App\Services\Api\AttachFileService');
            $commentService = resolve('App\Services\Api\CommentService');

            if (!empty($files = $request->get('files')) && is_array($files)) 
            {
                $fileIds = Arr::pluck($files, 'id');
                $fileService->updateAbleType($fileIds, $result->response);
            }

            if (!empty($comments = $request->get('comments')) && is_array($comments)) 
            {
                $commentIds = Arr::pluck($comments, 'id');
                $commentService->updateAbleType($commentIds, $result->response);
            }
        }
        else
        {
            $type = (stripos($type, 'store') !== false) ? 'create' : $type;
            $type = (stripos($type, 'update') !== false) ? 'update' : $type;

            $response = $this->responseError('api.code.common.' . $type . '_failed');
        }

        return $response;
    }

    public function responseError($apiCodeKey, $errors = [], $customData = [])
    {
        return api_error($apiCodeKey, $errors, $customData);
    }

    public function responseSuccess($data = [], $statusCode = 200)
    {
        return api_success($data, $statusCode, $this->module);
    }
}
