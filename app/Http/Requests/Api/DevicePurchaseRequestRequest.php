<?php
/**
 * Created by PhpStorm.
 * User: vohongquan
 * Date: 7/24/19
 * Time: 1:02 PM
 */

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

class DevicePurchaseRequestRequest extends BaseRequest
{
    public function rules()
    {
        $rules =  [
            'code' => 'required', 
            'name' => 'required', 
            'estimate_id' => 'required', 
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
            'code' => 'Số phiếu', 
            'name' => 'Tên phiếu', 
            'estimate_id' => 'Dự trù tổng', 
            'project_id' => 'Nơi đến', 
            'date' => 'Ngày nhập', 
            'creator_id' => 'Người nhập', 
        ];
    }
}
