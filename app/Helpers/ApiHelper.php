<?php

function api_response($returnCode, $data, $statusCode = 200, $module = '')
{
    $roleIds = array_column(auth()->user()->roles->toArray(), 'id');

    return response()->json([
        'code' => $returnCode,
        'data' => $data,
        'role_action' => [
            'can_create' => auth()->user()->can($module . '.create'),
            'can_update' => auth()->user()->can($module . '.update'),
            'can_delete' => auth()->user()->can($module . '.delete'),
            'can_approve' => auth()->user()->can($module . '.approve'),
            'can_see_price' => auth()->user()->can($module . '.see_price'),
            'is_admin' => auth()->user()->hasRole('admin'),
            'is_monitor' => in_array(9, $roleIds),//Giám Sát
        ],
        'module' => $module ?? null,
    ], $statusCode);
}

function api_error($apiCodeKey, $errors = [], $customData = [])
{
    $returnCode = config($apiCodeKey);
    $message = trans($apiCodeKey);

    $data = array_merge(['message' => $message], $customData);
    if ($errors) {
        $data['errors'] = $errors;
    }

    return api_response($returnCode, array_merge($data, $customData));
}

function api_success($data, $statusCode = 200, $module = '')
{
    return api_response(config('api.code.common.request_success'), $data, $statusCode, $module);
}
