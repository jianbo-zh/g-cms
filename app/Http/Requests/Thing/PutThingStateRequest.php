<?php

namespace App\Http\Requests\Thing;

use Illuminate\Foundation\Http\FormRequest;

class PutThingStateRequest extends FormRequest
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
            'name'                  => ['required', 'max:60'],
            'cond.0.fieldId'        => ['required', 'numeric'],
            'cond.0.symbol'         => ['required', 'max:60'],
            'cond.0.value'          => ['nullable', 'max:255'],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'         => '请填写状态名称！',
            'name.max:60'           => '状态名称最多60个字符组成！',
        ];
    }
}
