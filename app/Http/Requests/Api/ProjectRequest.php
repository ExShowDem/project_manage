<?php
/**
 * Created by PhpStorm.
 * User: vohongquan
 * Date: 7/24/19
 * Time: 1:02 PM
 */

namespace App\Http\Requests\Api;

class ProjectRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'code' => 'required',
            'description' => 'required',
            'image_base64' => [
                'base64image',
                'base64mimes:' . config('image.validate.mimes'),
                'base64max:' . config('image.validate.max_size'),
            ]
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
            'name' => 'Tên dự án',
            'description' => 'Mô tả',
            'code' => 'Mã dự án',
            'image_base64' => 'Ảnh đại diện'
        ];
    }
}
