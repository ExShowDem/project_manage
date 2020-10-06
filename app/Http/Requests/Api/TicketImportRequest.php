<?php

namespace App\Http\Requests\Api;

class TicketImportRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'supplier_id' => 'required',
            'date_import' => 'required',
            'project_id' => 'required',
            'number_ticket' => 'nullable',
            'reason' => 'nullable',
            'contract_number' => 'nullable',

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
            'supplier_id' => 'Bên xuất',
            'date_import' => 'Ngày nhập kho',
            'project_id' => 'Kho nhập',
        ];
    }
}
