<?php

namespace App\Http\Requests\Api;

class ItemRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'project_id' => 'required',
            'code' => 'required',
            'end_date' => 'nullable',
            'supplies' => 'nullable',
            'forward_data' => 'nullable',
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
            'name' => 'Tên hạng mục',
            'project_id' => 'Dự án',
            'code' => 'Mã hạng mục',
        ];
    }
}