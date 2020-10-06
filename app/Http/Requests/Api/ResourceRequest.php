<?php

namespace App\Http\Requests\Api;

class ResourceRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'resource_type_id' => 'required',
            'unit_id' => 'required',
            'unit_price' => ['required'/*, 'integer'*/],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Bạn chưa nhập :attribute.',
            //'integer' => 'Giá trị của :attribute phải là số nguyên.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Tên nguồn lực',
            'resource_type_id' => 'Loại',
            'unit_id' => 'ĐVT',
            'unit_price' => 'Đơn giá',
        ];
    }
}
