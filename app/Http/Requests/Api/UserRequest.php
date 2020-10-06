<?php

namespace App\Http\Requests\Api;

class UserRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'password_confirm' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'image' => ['sometimes', 'image', 'mimes:jpeg,jpg,png'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Bạn chưa nhập :attribute.',
            'email' => ':attribute phải đúng định dạng.',
            'unique' => ':attribute này đã được đăng ký, xin vui lòng nhập email khác.'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Tên',
            'password' => 'Mật khẩu',
            'password_confirm' => 'Xác nhận mật khẩu',
            'email' => 'Email',
            'image' => 'Ảnh',
        ];
    }
}
