<?php

namespace App\Http\Requests\Thing;

use Illuminate\Foundation\Http\FormRequest;

class PostThingRequest extends FormRequest
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
            'name'              => ['required', 'max:60'],
            'description'       => ['required', 'max:255'],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'         => '请填写事物名称！',
            'name.max:60'           => '事物名称最多60个字符组成！',
            'description.required'  => '请填写事物描述！',
            'description.max:60'    => '事物描述最多255个字符组成！',
        ];
    }
}
