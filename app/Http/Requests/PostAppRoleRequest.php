<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostAppRoleRequest extends FormRequest
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
            'name.required' => '请填写角色名称！',
            'description.required'  => '请填写角色描述！',
        ];
    }
}
