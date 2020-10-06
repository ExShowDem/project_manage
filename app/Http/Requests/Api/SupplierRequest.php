<?php

namespace App\Http\Requests\Api;

class SupplierRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'project_id' => 'required',
            'email' => 'nullable|email',
            'phone_number' => 'nullable',
            'address' => 'nullable',
            'type' => 'nullable',
            'tax_code' => 'nullable',
            'branch' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Bạn chưa nhập :attribute.',
            'email' => 'Email không hợp lệ.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Tên',
            'project_id' => 'Dự án',
        ];
    }
}