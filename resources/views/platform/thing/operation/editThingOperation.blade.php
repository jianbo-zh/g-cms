@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="card card-accent-info">
                        <div class="card-header">
                            编辑事物操作
                        </div>
                        <div class="card-body row">
                            <div class="col-md-8">
                                <form id="updateThingOperationForm">
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label text-right" for="name">名称</label>
                                        <div class="col-md-9">
                                            <input class="form-control" id="name" name="name" value="{{ $operation['name'] }}" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label text-right" for="name">类型</label>
                                        <div class="col-md-9">
                                            <select name="operationType" class="form-control">
                                                @foreach($operationTypes as $key => $value)
                                                    <option value="{{ $key }}" @if($operation['operationType']===$key) selected="selected" @endif >{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label text-right" for="name">形式</label>
                                        <div class="col-md-9">
                                            <select name="operationForm" class="form-control">
                                                @foreach($operationForms as $key => $value)
                                                    <option value="{{ $key }}" @if($operation['operationForm']===$key) selected="selected" @endif>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-2 col-form-label text-right" style="align-self: center">字段</label>
                                        <div class="col-md-9">
                                            <div class="form-group row">
                                                <div class="col-md-4"><strong>字段名称</strong></div>
                                                <div class="col-md-4"><strong>是否显示</strong></div>
                                                <div class="col-md-4"><strong>操作类型</strong></div>
                                            </div>
                                            @foreach($fields as $key => $field)
                                                <div class="form-group row">
                                                    <div class="col-md-4">
                                                        <label class="">{{ $field['comment'] }}</label>
                                                        <input type="hidden" name="fields[{{ $key }}][id]" value="@if(!empty($field['operationFieldId'])) {{ $field['operationFieldId'] }} @endif" />
                                                        <input type="hidden" name="fields[{{ $key }}][fieldId]" value="{{ $field['id'] }}" />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="switch switch-label switch-outline-primary-alt">
                                                            <input type="checkbox" class="switch-input" @if(!empty($field['isShow'])) checked="checked" @endif name="fields[{{ $key }}][isShow]" value="1" />
                                                            <span class="switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <select name="fields[{{ $key }}][updateType]" class="form-control">
                                                            @foreach($fieldOperationTypes as $key => $value)
                                                                <option value="{{ $key }}" @if(!empty($field['updateType']) && $key===$field['updateType']) selected @endif>{{ $value }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="form-group-actions col-md-9">
                                    <button class="btn btn-success btn-ladda ladda-button" data-style="zoom-out" id="updateThingOperationSubmit">
                                        <span class="ladda-label">提 交</span>
                                        <span class="ladda-spinner"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function(){
            $('#updateThingOperationSubmit').click(function(){
                let ladda = Ladda.create(this).start();
                let data = $('#updateThingOperationForm').serializeJSON();

                z_ajax('put', '{{ route('updateThingOperation', ['appId'=>$appId, 'thingId'=>$thingId, 'operationId'=>$operation['id']]) }}', data, function (response) {
                    let data = response.data;
                    if(data.code === 0){
                        z_notify_success(data.message ? data.message : '操作成功！', function () {
                            location.href = '{{ route('indexThingOperations', ['appId'=>$appId, 'thingId'=>$thingId]) }}';
                        });
                    }else{
                        z_notify_fail(data.message ? data.message : '操作失败！');
                    }
                })
            });
        })
    </script>
@endpush