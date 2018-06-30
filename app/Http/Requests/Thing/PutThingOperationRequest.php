<?php

namespace App\Http\Requests\Thing;

use Illuminate\Foundation\Http\FormRequest;

class PutThingOperationRequest extends FormRequest
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
            'name'                      => ['required', 'max:60'],
            'operationType'             => ['required', 'max:60'],
            'operationForm'             => ['required', 'max:60'],
            'fields.0.id'               => ['nullable', 'numeric'],
            'fields.0.fieldId'          => ['required', 'numeric'],
            'fields.0.updateType'       => ['required', 'alpha_dash', 'max:255'],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'                 => '请填写事物名称！',
            'name.max:60'                   => '事物名称最多60个字符组成！',
            'operationType.required'        => '请选择操作类型！',
            'operationType.max:60'          => '操作类型最多60个字符组成！',
            'operationForm.required'        => '请选择操作形式！',
            'operationForm.max:60'          => '操作形式最多60个字符组成！',
        ];
    }
}
