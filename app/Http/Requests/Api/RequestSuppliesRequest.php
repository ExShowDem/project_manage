<?php
/**
 * Created by PhpStorm.
 * User: vohongquan
 * Date: 7/24/19
 * Time: 1:02 PM
 */

namespace App\Http\Requests\Api;

class RequestSuppliesRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'code' => 'required',
            'created_date' => 'required',
            'supplies' => 'required'
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
            'name' => 'Tên yêu cầu',
            'code' => 'Mã yêu cầu',
            'created_date' => 'Ngày tạo',
            'supplies' => 'Vật tư'
        ];
    }
}
