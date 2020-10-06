<?php

namespace App\Http\Requests\Api;

class PaymentOrderRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'contract_subcontractor_id' => 'required',
            'subcontractor_id' => 'required',
            'project_id' => 'required',
            'payment_date' => 'required',
            'content' => 'required',
            'type_payment' => 'required',
            'contract_value' => 'required',
            'settlement_value' => 'required',
            'annex_value' => 'nullable',
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
            'subcontractor_id' => 'Tên nhà thầu phụ',
            'project_id' => 'Dự án',
            'contract_subcontractor_id' => 'Số hợp đồng',
            'payment_date' => 'Ngày thanh toán',
            'content' => 'Nội dung thanh toán',
            'type_payment' => 'Loại Thanh Toán',
            'contract_value' => 'Giá Trị Hợp Đồng',
            'settlement_value' => 'Giá Trị',
        ];
    }

}