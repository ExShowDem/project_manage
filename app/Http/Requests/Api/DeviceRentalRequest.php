<?php
/**
 * Created by PhpStorm.
 * User: vohongquan
 * Date: 7/24/19
 * Time: 1:02 PM
 */

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

class DeviceRentalRequest extends BaseRequest
{
    public function rules()
    {
        $rules =  [
            'code' => 'required', 
            'name' => 'required', 
            'project_id' => 'required', 
            'borrower_type' => 'required', 
            'borrower_id' => 'required', 
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
            'code' => 'Mã cho thuê', 
            'name' => 'Tên phiếu cho thuê', 
            'project_id' => 'Dự án', 
            'borrower_type' => 'Loại đơn vị thuê', 
            'borrower_id' => 'Đơn vị thuê', 
            'creator_id' => 'Người tạo', 
        ];
    }
}
