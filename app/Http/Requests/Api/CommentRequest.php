<?php

namespace App\Http\Requests\Api;

class CommentRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'content' => ['required', 'string', 'max:255'],
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
            'content' => 'nội dung',
        ];
    }
}
