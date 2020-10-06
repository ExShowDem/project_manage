<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WorkPlanRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'name' => [
                'required',
                Rule::unique('work_plans')->where(function ($query) {
                    return $query->where('project_id', $this->project_id);
                })
            ]
        ];

        if ($this->is_limit_user) {
            $rules['user_ids'] = ['required'];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => 'Tên kế hoạch công việc'
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Bạn phải nhập :attribute',
            'user_ids.required' => 'Bạn phải chọn tài khoản',
            'unique' => ':attribute không được trùng nhau',
        ];
    }
}
