<?php

namespace App\Http\Requests\Api;

class CensorSubRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'subcontractors' => 'required',
            'project_id' => 'required',
            'package' => 'required',
            'date_browsing' => 'required',
            'date_approve' => 'required',
            'type' => 'required',
            'link' => '',
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
            'subcontractors' => 'Tên nhà thầu phụ',
            'project_id' => 'Dự án',
            'package' => 'Gói thầu',
            'date_browsing' => 'Ngày trình duyệt',
            'date_approve' => 'Ngày phê duyệt',
            'type' => 'Loại',
            'link' => 'Link',
        ];
    }

}