<?php

namespace App\Http\Requests\Api;

class UpdateUserRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'image' => ['sometimes', 'image', 'mimes:jpeg,jpg,png'],
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
            'name' => 'Tên',
            'image' => 'Ảnh',
        ];
    }
}
