<?php

namespace App\Http\Requests\Api;

class ReceiptTransferRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'output_id' => 'required',
            'input_id' => 'required',
            'date_transfer' => 'required',
            'code' => 'required',
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
            'output_id' => 'Bên xuất',
            'input_id' => 'Kho nhập',
            'date_transfer' => 'Ngày chuyển',
            'code' => 'Số phiếu',
        ];
    }
}
