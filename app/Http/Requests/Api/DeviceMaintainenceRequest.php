<?php
/**
 * Created by PhpStorm.
 * User: vohongquan
 * Date: 7/24/19
 * Time: 1:02 PM
 */

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

class DeviceMaintainenceRequest extends BaseRequest
{
    public function rules()
    {
        $rules =  [
            'code' => 'required', 
            'name' => 'required', 
            'project_id' => 'required', 
            'date' => 'required', 
            'creator_id' => 'required', 
            'result' => 'required', 
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
            'code' => 'Mã phiếu', 
            'name' => 'Tên phiếu bảo trì', 
            'project_id' => 'Dự án', 
            'date' => 'Ngày bảo trì', 
            'creator_id' => 'Người tạo', 
            'result' => 'Kết quả', 
        ];
    }
}
