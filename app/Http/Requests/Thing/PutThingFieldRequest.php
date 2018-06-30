<?php

namespace App\Http\Requests\Thing;

use Illuminate\Foundation\Http\FormRequest;

class PutThingFieldRequest extends FormRequest
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
            'name'              => ['required', 'alpha_dash', 'max:60'],
            'storageType'       => ['required', 'max:60'],
            'showType'          => ['required', 'max:60'],
            'showOptions'    => ['nullable', 'json', 'max:255'],
            'comment'           => ['required', 'max:255'],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'             => '请填写字段名！',
            'name.alpha_dash'           => '字段名必须是字母下划线数字！',
            'name.max:60'               => '字段名最多60个字符组成！',
            'storageType.required'      => '请选择存储类型！',
            'storageType.max:60'        => '存储类型最多60个字符组成！',
            'showType.required'         => '请选择展示类型！',
            'showType.max:60'           => '展示类型最多60个字符组成！',
            'showOptions.json'       => '展示选项必须是JSON格式！',
            'showOptions.max:255'    => '展示选项最多255个字符组成！',
            'comment.required'          => '请填写字段备注！',
            'comment.max:60'            => '字段备注最多255个字符组成！',
        ];
    }
}
