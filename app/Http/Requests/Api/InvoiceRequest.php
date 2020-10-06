<?php

namespace App\Http\Requests\Api;

class InvoiceRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'supplier_id' => 'required',
            'contract_date' => 'required',
            'project_id' => 'required',
            'contract_number' => 'required',
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
            'supplier_id' => 'Nhà cung cấp',
            'contract_date' => 'Ngày HĐ',
            'request_id' => 'Mã yêu cầu',
            'project_id' => 'Dự án nhập',
            'contract_number' => 'Số HĐ',
        ];
    }
}
