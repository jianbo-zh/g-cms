<?php

namespace App\Http\Requests\Thing;

use Illuminate\Foundation\Http\FormRequest;

class PostThingStateOperationRequest extends FormRequest
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
            'operationId'          => ['required', 'numeric', 'min:1'],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'operationId.required' => '操作编号不能为空！',
            'operationId.numeric'  => '操作编号必须是数字！',
        ];
    }
}
