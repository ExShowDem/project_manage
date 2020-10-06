<?php

namespace App\Http\Requests\Api;

class CategorySupplyRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'code' => 'required',
            'project_id' => 'required',
            'parent_id' => 'nullable',
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
            'name' => 'Tên nhóm vật tư',
            'code' => 'Mã nhóm vật tư',
            'project_id' => 'Dự án',
            'parent_id' => 'Danh mục cha',
        ];
    }
}
