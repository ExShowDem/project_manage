<?php
/**
 * Created by PhpStorm.
 * User: vohongquan
 * Date: 7/24/19
 * Time: 1:02 PM
 */

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

class DeviceContractRequest extends BaseRequest
{
    public function rules()
    {
        $rules =  [
            'code' => 'required', 
            'purchase_id' => 'required', 
            'place_id' => 'required', 
            'project_id' => 'required', 
            'date' => 'required', 
            'creator_id' => 'required', 
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
            'code' => 'Số hóa đơn', 
            'purchase' => 'Tên phiếu', 
            'place' => 'Nơi mua', 
            'project_id' => 'Dự án', 
            'date' => 'Ngày mua', 
            'creator_id' => 'Người nhập', 
        ];
    }
}
