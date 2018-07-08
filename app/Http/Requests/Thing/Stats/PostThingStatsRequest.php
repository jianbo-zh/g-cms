<?php

namespace App\Http\Requests\Thing\Stats;

use Illuminate\Foundation\Http\FormRequest;

class PostThingStatsRequest extends FormRequest
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

            'group.0.fieldId'       => ['required', 'numeric'],
            'group.0.fieldName'     => ['required', 'max:60'],
            'group.0.type'          => ['required', 'max:60'],
            'group.0.operation'     => ['required', 'max:60'],

            'group.1.fieldId'       => ['required', 'numeric'],
            'group.1.fieldName'     => ['required', 'max:60'],
            'group.1.type'          => ['required', 'max:60'],
            'group.1.operation'     => ['required', 'max:60'],

            'chartType'             => ['required', 'max:60'],
            'chartValue'            => ['required', 'max:60'],

            'chartOption'            => ['nullable', 'json', 'max:1000'],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'                 => '请填写状态名称！',
            'name.max:60'                   => '状态名称最多60个字符组成！',

            'group.0.fieldId.required'      => '请选择维度字段！',
            'group.0.fieldId.numeric'       => '维度字段必须是数字！',
            'group.0.fieldName.required'    => '请填写维度字段别名！',
            'group.0.fieldName.max:60'      => '维度字段别名不能超过60个字符！',
            'group.0.type.required'         => '请选择维度操作类型！',
            'group.0.type.max:60'           => '操作类型不能超过60个字符！',
            'group.0.operation.required'    => '请选择维度操作！',
            'group.0.operation.max:60'      => '维度操作不能超过60个字符！',

            'group.1.fieldId.required'      => '请选择维度字段！',
            'group.1.fieldId.numeric'       => '维度字段必须是数字！',
            'group.1.fieldName.required'    => '请填写维度字段别名！',
            'group.1.fieldName.max:60'      => '维度字段别名不能超过60个字符！',
            'group.1.type.required'         => '请选择维度操作类型！',
            'group.1.type.max:60'           => '操作类型不能超过60个字符！',
            'group.1.operation.required'    => '请选择维度操作！',
            'group.1.operation.max:60'      => '维度操作不能超过60个字符！',

            'chartType.required'            => '请选择图表类别！',
            'chartType.max:60'              => '图表类别不能超过60个字符！',

            'chartValue.required'           => '请选择图表子类！',
            'chartValue.max:60'             => '图表子类不能超过60个字符！',

            'chartOption.json'              => '图表选项配置必须是Json格式！',
            'chartOption.max:1000'          => '图表选项配置不能超过1000个字符！',
        ];
    }
}
