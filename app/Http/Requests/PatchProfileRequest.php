<?php

namespace App\Http\Requests;

use App\Rules\Mobile;
use Illuminate\Foundation\Http\FormRequest;

class PatchProfileRequest extends FormRequest
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
            'nickname'  => ['required', 'max:255'],
            'avatar'    => ['nullable', 'max:255'],
            'phone'     => ['nullable', new Mobile(), 'max:255'],
            'email'     => ['nullable', 'email', 'max:255'],
            'password'  => ['nullable', 'max:255', 'confirmed'],
        ];
    }
}
