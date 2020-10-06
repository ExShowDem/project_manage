<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

class SupplyRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('supplies')->ignore($this->id ?? null)],
            'project_id' => 'nullable',
            'unit_id' => 'required',
            'code' => ['required', Rule::unique('supplies')->ignore($this->id ?? null)],
            'content_offer' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Bạn chưa nhập :attribute.',
            'unique' => ':attribute đã tồn tại.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Tên vật tư',
            'project_id' => 'Dự án',
            'code' => 'Mã vật tư',
            'category_supplies_id' => 'Nhóm vật tư',
            'unit_id' => 'Đơn vị tính',
        ];
    }
}
