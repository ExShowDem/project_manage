<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

class ContractSubRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'subcontractor_id' => 'required',
            'project_id' => 'required',
            'contract_sign_date' => 'required',
            'process' => 'required',
            'contract_form' => 'required',
            'contract_number' => [
                'required',
                Rule::unique('contract_subcontractors')->ignore($this->id ?? null),
            ],
            'contract_annex_value' => 'required',
            'contract_value' => 'nullable',
            'contract_value_vat' => 'nullable',
            'value_custody_warranty' => 'required',
            'content' => 'required',
            'date_warranty' => 'required',
            'construction_items' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Bạn chưa nhập :attribute.',
            'unique' => ':attribute đã tồn tại',
        ];
    }

    public function attributes()
    {
        return [
            'subcontractor_id' => 'Tên nhà thầu phụ',
            'project_id' => 'Dự án',
            'contract_sign_date' => 'Ngày ký hợp đồng',
            'process' => 'Tiến độ',
            'contract_form' => 'Hình thức hợp đồng',
            'contract_number' => 'Số hợp đồng',
            'contract_annex_value' => 'Giá trị phụ lục hợp đồng',
            'contract_value' => 'Giá trị hợp đồng( chưa vat )(VND)',
            'contract_value_vat' => 'Giá trị hợp đồng( có vat )(VND)',
            'value_custody_warranty' => 'Giá Trị tạm giữ bảo hành',
            'content' => 'Nội dung hợp đồng',
            'date_warranty' => 'Thời Gian Bảo Hành',
            'construction_items' => 'Hạng mục thi công',
        ];
    }

}