<?php

namespace App\Http\Requests\Api;

class ResourceTypeRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
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
            'name' => 'Loại nguồn lực',
        ];
    }
}
