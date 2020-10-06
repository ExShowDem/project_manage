<?php

namespace App\Http\Requests\Api;

class SubContractorRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'type' => 'required',
            'code' => 'required',
            'tax_code' => 'required',
            'representative' => 'required',
            'bank' => 'required',
            'account_name' => 'required',
            'account_owner' => 'required',
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
            'name' => 'Tên nhà thầu phụ',
            'type' => 'Loại nhà thầu phụ',
            'code' => 'Mã số nhà thầu ',
            'tax_code' => 'Mã số thuế',
            'representative' => 'Người đại diện',
            'bank' => 'Ngân hàng',
            'account_name' => 'Số tài khoản',
            'account_owner' => 'Chủ tài khoản',
        ];
    }
}