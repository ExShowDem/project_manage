<?php
/**
 * Created by PhpStorm.
 * User: vohongquan
 * Date: 7/24/19
 * Time: 1:02 PM
 */

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

class PlanSupplyRequest extends BaseRequest
{
    public function rules()
    {
        $rules =  [
            'name' => [
                'required',
                Rule::unique('plans')->where(function ($query) {
                    return $query->where('project_id', $this->project_id);
                })
            ],
            'code' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'supplies' => 'required'
        ];

        if ($this->id) {
            $rules['name'] = [
                'required',
                Rule::unique('plans')->where(function ($query) {
                    return $query->where('project_id', $this->project_id);
                })->ignore($this->id)
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => 'Bạn chưa nhập :attribute.',
            'unique' => ':attribute không được trùng nhau'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Tên kế hoạch',
            'code' => 'Mã kế hoạch',
            'start_time' => 'Ngày bắt đầu',
            'end_time' => 'Ngày kết thúc',
            'supplies' => 'Vật tư'
        ];
    }
}
