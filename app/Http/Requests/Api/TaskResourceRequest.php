<?php

namespace App\Http\Requests\Api;

class TaskResourceRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'tracking_date' => 'required',
            'resources' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Bạn chưa nhập :attribute.',
        ];
    }

    public function attributes()
    {
        return [
            'tracking_date' => 'Ngày theo dõi',
            'resources' => 'Nguồn lực',
        ];
    }
}