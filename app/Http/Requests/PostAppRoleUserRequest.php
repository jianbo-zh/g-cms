<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostAppRoleUserRequest extends FormRequest
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
            'userId'          => ['required', 'numeric', 'min:1'],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'userId.required' => '用户编号不能为空！',
            'userId.numeric'  => '用户编号必须是数字！',
        ];
    }
}
