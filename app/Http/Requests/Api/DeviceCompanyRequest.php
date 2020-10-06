<?php
/**
 * Created by PhpStorm.
 * User: vohongquan
 * Date: 7/24/19
 * Time: 1:02 PM
 */

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

class DeviceCompanyRequest extends BaseRequest
{
    public function rules()
    {
        $rules =  [
            'code' => 'required', 
            'devices_to_project_id' => 'required', 
            'project_id' => 'required', 
            'company' => '', 
            'user_id' => 'required', 
            'return_date' => 'required', 
        ];

        return $rules;
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
            'code' => 'Số phiếu giao nhận', 
            'devices_to_project_id' => 'Chứng từ xuất', 
            'project_id' => 'Nơi trả', 
            'company' => 'Nơi nhập ', 
            'user_id' => 'Người nhập', 
            'return_date' => 'Ngày trả', 
        ];
    }
}
