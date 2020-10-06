<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

class DeviceRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name'       => ['required', Rule::unique('devices')->ignore($this->id ?? null)],
            'project_id' => 'nullable',
            'unit_id'    => 'required',
            'code'       => ['required', Rule::unique('devices')->ignore($this->id ?? null)],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Bạn chưa nhập :attribute.',
            'unique'   => ':attribute đã tồn tại.',
        ];
    }

    public function attributes()
    {
        return [
            'name'                => 'Tên thiết bị',
            'project_id'          => 'Dự án',
            'code'                => 'Mã thiết bị',
            'unit_id'             => 'Đơn vị tính',
        ];
    }
}
