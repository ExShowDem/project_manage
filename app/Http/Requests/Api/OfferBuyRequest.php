<?php

namespace App\Http\Requests\Api;

class OfferBuyRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'date_offer' => 'required',
            'ticket_number' => 'required',
            'project_id' => 'required',
            'supplier_id' => 'required',
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
            'name' => 'Tên đề xuất',
            'date_offer' => 'Ngày đề xuất',
            'ticket_number' => 'Số phiếu',
            'project_id' => 'Dự án nhập',
            'supplier_id' => 'Nhà cung cấp',
        ];
    }
}
