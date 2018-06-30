<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PutRoleRequest extends FormRequest
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
            'id'            => ['required', 'numeric'],
            'name'          => ['required', 'max:255'],
            'description'   => ['required', 'max:255'],
            'perms'         => ['array'],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'id.required'               => '角色编号不能为空！',
            'id.numeric'                => '角色编号必须是数字',
            'name.required'             => '请填写角色名称！',
            'description.required'      => '请填写角色描述！',
        ];
    }
}
