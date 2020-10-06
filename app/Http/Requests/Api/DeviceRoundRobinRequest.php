<?php
/**
 * Created by PhpStorm.
 * User: vohongquan
 * Date: 7/24/19
 * Time: 1:02 PM
 */

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

class DeviceRoundRobinRequest extends BaseRequest
{
    public function rules()
    {
        $rules =  [
            'name' => 'required', 
            'code' => 'required', 
            'from_project_id' => 'required', 
            'to_project_id' => 'required', 
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
            'name' => 'Tên phiếu', 
            'code' => 'Số phiếu', 
            'from_project_id' => 'Nơi xuất', 
            'to_project_id' => 'Nơi đến', 
            'date' => 'Ngày xuất', 
            'creator_id' => 'Người xuất', 
        ];
    }
}
