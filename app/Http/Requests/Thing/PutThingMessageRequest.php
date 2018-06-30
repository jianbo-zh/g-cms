<?php

namespace App\Http\Requests\Thing;

use Illuminate\Foundation\Http\FormRequest;

class PutThingMessageRequest extends FormRequest
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
            'stateId'               => ['required', 'numeric'],
            'receiverValue'         => ['required', 'numeric'],
            'content'               => ['required', 'max:255'],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'stateId.required'          => '请选择状态！',
            'stateId.numeric'           => '状态值错误！',
            'receiverValue.required'    => '请选择接收者！',
            'receiverValue.numeric'     => '接收者值错误！',
            'content.required'          => '请填写内容！',
            'content.max:255'           => '发送内容不能超过255个字符！',
        ];
    }
}
