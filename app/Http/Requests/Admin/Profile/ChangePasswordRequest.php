<?php

namespace App\Http\Requests\Admin\Profile;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Bạn chưa nhập :attribute.',
            'confirmed' => 'Mật khẩu xác nhận không chính xác.',
            'min' => 'Mật khẩu mới phải có tối thiểu 6 ký tự.'
        ];
    }

    public function attributes()
    {
        return [
            'password' => 'Mật khẩu mới',
        ];
    }
}
