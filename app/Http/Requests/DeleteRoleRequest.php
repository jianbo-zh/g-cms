<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'          => ['required', 'numeric'],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'id.required'   => '角色编号不能为空！',
            'id.number'     => '角色编号类型错误！',
        ];
    }
}
