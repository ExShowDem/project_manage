<?php

namespace App\Http\Requests\Api;

class ReceiptInputRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'request_id' => 'required',
            'output_id' => 'required',
            'input_id' => 'required',
            'date_input' => 'required',
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
            'request_id' => 'Số yêu cầu',
            'output_id' => 'Bên xuất',
            'input_id' => 'Kho nhập',
            'date_input' => 'Ngày nhập',
            'code' => 'Số phiếu',
        ];
    }
}
