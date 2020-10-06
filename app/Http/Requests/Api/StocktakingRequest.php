<?php
/**
 * Created by PhpStorm.
 * User: vohongquan
 * Date: 7/24/19
 * Time: 1:02 PM
 */

namespace App\Http\Requests\Api;

class StocktakingRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'code' => 'required',
            'created_date' => 'required',
            'project_id' => 'required',
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
            'name' => 'Tên phiếu',
            'code' => 'Mã phiếu',
            'created_date' => 'Ngày tạo',
            'project_id' => 'Dự án',
            'supplies' => 'Vật tư'
        ];
    }
}
