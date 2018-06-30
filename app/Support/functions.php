<?php

if(! function_exists('build_detail_html')){
    function build_detail_html($params, $default=array())
    {
        $name = $params['name'];
        $comment = $params['comment'];
        $showType = $params['showType'];
        $showOptions = $params['showOptions'];
        $updateType = $params['updateType'];

        $defaultValue = isset($default[$name]) ? $default[$name] : null;
        $defaultValueToShow = htmlspecialchars($defaultValue);

        if($updateType === 'user_input'){
            $labelFor = " for=\"{$name}\"";
            $idStr = " id=\"{$name}\"";
            $nameStr = " name=\"{$name}\"";
        }else{
            $labelFor = " ";
            $idStr = " ";
            $nameStr = "disabled=\"disabled\"";
        }

        switch ($showType){
            case 'select':
                $options = '';
                foreach ($showOptions as $value){
                    $selected = '';
                    if((string)$value['value'] === (string)$defaultValue){
                        $selected = 'selected="selected"';
                    }
                    $options .= "<option value=\"{$value['value']}\" {$selected}>{$value['name']}</option>";
                }
                $html = <<<EOF
                <div class="form-group row">
                    <label class="col-md-1 col-form-label text-right" {$labelFor}>{$comment}</label>
                    <div class="col-md-10">
                        <select class="form-control" {$nameStr} {$idStr}>
                            {$options}
                        </select>
                    </div>
                </div>
EOF;
                break;
            case 'radio':
                $radios = '';
                foreach ($showOptions as $key => $value){
                    $checked = '';
                    if((string)$value['value'] === (string)$defaultValue){
                        $checked = 'checked="checked"';
                    }
                    $radios .= <<<EOF
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" type="radio" id="{$name}{$key}" value="{$value['value']}" {$nameStr} {$checked} />
                        <label class="form-check-label" for="{$name}{$key}">{$value['name']}</label>
                    </div>
EOF;
                }
                $html = <<<EOF
                <div class="form-group row">
                    <label class="col-md-1 col-form-label text-right">{$comment}</label>
                    <div class="col-md-10">
                        <div class="col-form-label">
                            {$radios}
                        </div>
                    </div>
                </div>
EOF;
                break;
            case 'checkbox':
                $checked = '';
                if((string)$defaultValue === (string)$showOptions['value']){
                    $checked = 'checked="checked"';
                }
                $html = <<<EOF
                <div class="form-group row">
                    <label class="col-md-1 col-form-label text-right" {$labelFor}>{$comment}</label>
                    <div class="col-md-10">
                        <label class="switch switch-label switch-outline-primary-alt">
                            <input type="checkbox" class="switch-input" {$idStr} {$nameStr} {$checked} value="{$showOptions['value']}">
                            <span class="switch-slider" data-checked="On" data-unchecked="Off"></span>
                        </label>
                    </div>
                </div>
EOF;
                break;
            case 'input':
                $html = <<<EOF
                <div class="form-group row">
                    <label class="col-md-1 col-form-label text-right" {$labelFor}>{$comment}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" {$idStr} {$nameStr} value="{$defaultValueToShow}" />
                    </div>
                </div>
EOF;
                break;
            case 'textarea':
                $html = <<<EOF
                <div class="form-group row">
                    <label class="col-md-1 col-form-label text-right" {$labelFor}>{$comment}</label>
                    <div class="col-md-10">
                        <textarea rows="3" class="row-span form-control" {$idStr} {$nameStr}>{$defaultValueToShow}</textarea>
                    </div>
                </div>
EOF;
                break;
            case 'richtext':
                $html = <<<EOF
                <div class="form-group row">
                    <label class="col-md-1 col-form-label text-right" {$labelFor}>{$comment}</label>
                    <div class="col-md-10">
                        <textarea class="row-span form-control" {$idStr} {$nameStr}>{$defaultValueToShow}</textarea>
                    </div>
                </div>
EOF;
                break;
            default:
                $html = '';
                break;
        }

        return $html;
    }
}

if(! function_exists('build_query_html')){
    function build_query_html($params, $query=array())
    {
        $name = $params['name'];
        $comment = $params['comment'];
        $showType = $params['showType'];
        $showOptions = $params['showOptions'];

        $defaultValue = isset($query[$name]) ? $query[$name] : null;

        switch ($showType){
            case 'select':
            case 'radio':
                $options = '<option value="">请选择</option>';
                foreach ($showOptions as $value){
                    $selected = '';
                    if((string)$value['value'] === (string)$defaultValue){
                        $selected = 'selected="selected"';
                    }
                    $options .= "<option value=\"{$value['value']}\" {$selected}>{$value['name']}</option>";
                }
                $html = <<<EOF
                <div class="form-group">
                    <label for="{$name}">{$comment}</label>
                    <select id="{$name}" name="{$name}" class="form-control">
                    {$options}
                    </select>
                </div>
EOF;
                break;
            case 'checkbox':
                $checked = '';
                if((string)$defaultValue === (string)$showOptions['value']){
                    $checked = 'checked="checked"';
                }
                $html = <<<EOF
                <div class="form-group">
                    <label class="col-form-label">{$comment}</label>
                    <div class="col-form-label">
                        <div class="form-check form-check-inline mr-1">
                            <input class="form-check-input" type="checkbox" id="{$name}" name="{$name}" {$checked} value="{$showOptions['value']}">
                            <label class="form-check-label" for="{$name}">{$showOptions['name']}</label>
                        </div>
                    </div>
                </div>
EOF;
                break;
            case 'input':
            case 'textarea':
            case 'richtext':
            default:
                $html = <<<EOF
                <div class="form-group">
                    <label for="{$name}">{$comment}</label>
                    <input type="text" class="form-control" id="{$name}" name="{$name}" value="{$defaultValue}" />
                </div>
EOF;
                break;
        }

        return $html;
    }
}

if(!function_exists('web_api_token')) {
    function web_api_token()
    {
        return ($user = Auth::user()) ? $user->apiToken : '';
    }
}

if(!function_exists('array_snake_to_camel')){
    /**
     * 把数组的Key从下划线转换成小驼峰
     *
     * @param $array
     * @return mixed
     */
    function array_snake_to_camel($array)  {
        $newArray = [];
        foreach ($array as $key => $value){
            if(is_string($key)){
                while(($pos = strpos($key , '_')) !== false){
                    $key = substr($key , 0 , $pos) . ucfirst(substr($key , $pos+1));
                }
            }
            if (is_array($value)){
                $value = array_snake_to_camel($value);
            }
            $newArray[$key] = $value;
        }
        return $newArray;
    };
}

if(!function_exists('bind_operation_param')){

    function bind_operation_param(string $str)
    {
        return \App\Http\Libraries\OperationContext::bindParams($str);
    }
}
