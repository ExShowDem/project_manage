<?php

namespace App\Http\Requests\Api;

class RoleRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
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
            'name' => 'Tên Vai trò/Chức vụ',
        ];
    }
}
