<?php
/**
 * Created by PhpStorm.
 * User: vohongquan
 * Date: 7/24/19
 * Time: 1:02 PM
 */

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

class DeviceEstimatesRequest extends BaseRequest
{
    public function rules()
    {
        $rules =  [
            'code' => 'required', 
            'name' => 'required', 
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
            'code' => 'Số dự trù', 
            'name' => 'Tên dự trù', 
            'project_id' => 'Dự án', 
            'date' => 'Ngày nhập', 
            'creator_id' => 'Người nhập', 
        ];
    }
}
