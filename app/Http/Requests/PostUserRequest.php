<?php

namespace App\Http\Requests;

use App\Rules\Mobile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostUserRequest extends FormRequest
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
            'userType'          => ['required', 'max:20', Rule::in(['platform', 'app_developer', 'app_manager'])],
            'username'          => ['required', 'max:255'],
            'nickname'          => ['required', 'max:255'],
            'phone'             => ['required', new Mobile()],
            'email'             => ['required', 'email'],
            'password'          => ['required', 'max:255', 'confirmed' ],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'userType.required'     => '用户类型不能为空！',
            'userType.max:20'       => '用户类型长度不能超过20个字符！',
            'username.required'     => '账号不能为空！',
            'nickname.required'     => '昵称不能为空！',
            'phone.required'        => '手机必须填写！',
            'email.required'        => '邮箱必须填写！',
            'password.required'     => '请输入密码！',
            'password.confirmed'    => '两次输入密码不一致！',
        ];
    }
}
