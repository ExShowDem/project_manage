<?php

namespace App\Http\Requests\Api;

class ReceiptOutputRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'output_id' => 'required',
            'input_id' => 'required',
            'date_output' => 'required',
            'code' => 'required',
            'supplies' => 'required',
            'request_supply_id' => 'required',
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
            'output_id' => 'Kho xuất',
            'input_id' => 'Bên nhận',
            'date_output' => 'Ngày xuất',
            'code' => 'Số phiếu',
            'supplies' => 'Vật tư',
            'request_supply_id' => 'Mã yêu cầu',
        ];
    }
}
