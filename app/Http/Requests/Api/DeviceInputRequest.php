<?php
/**
 * Created by PhpStorm.
 * User: vohongquan
 * Date: 7/24/19
 * Time: 1:02 PM
 */

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

class DeviceInputRequest extends BaseRequest
{
    public function rules()
    {
        $rules =  [
            'code' => 'required', 
            'company' => '', 
            'project_id' => 'required', 
            'creator_id' => 'required', 
            'created_date' => 'required', 
            'purchase_request_id' => '', 
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
            'code' => 'Số phiếu', 
            'company' => 'Nơi xuất', 
            'project_id' => 'Nơi đến', 
            'creator_id' => 'Người nhập', 
            'created_date' => 'Ngày nhập', 
            'purchase_request_id' => 'Yêu cầu mua thiết bị', 
        ];
    }
}
