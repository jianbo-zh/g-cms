@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="card card-accent-info">
                        <div class="card-header">
                            编辑事物状态
                        </div>
                        <div class="card-body row">
                            <div class="col-md-8">
                                <form id="updateThingStateForm">
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label text-right" for="name">名称</label>
                                        <div class="col-md-9">
                                            <input class="form-control" id="name" name="name" value="{{ $state['name'] }}" />
                                        </div>
                                    </div>
                                    @foreach($state['conditions'] as $condition)
                                        <div class="form-group row">
                                            <label class="col-md-2 col-form-label text-right" for="comment">@if ($loop->first) 条件 @else 并且 @endif </label>
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <select class="form-control" name="cond[{{ $loop->index }}][fieldId]">
                                                            @foreach($fields as $field)
                                                                <option value="{{ $field['id'] }}" @if($condition['fieldId']==$field['id']) selected="selected" @endif >{{ $field['comment'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <select class="form-control" name="cond[{{ $loop->index }}][symbol]">
                                                            @foreach($symbols as $key => $value)
                                                                <option value="{{ $key }}" @if($condition['symbol']==$key) selected="selected" @endif >{{ $value }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control" name="cond[{{ $loop->index }}][value]" placeholder="值" value="{{ $condition['value'] }}" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="form-group row">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-9">
                                            <button id="addOneCondition" type="button" class="btn btn-primary">添加一个条件</button>
                                            <button id="deleteOneCondition" @if(count($state['conditions'])==1) style="display:none;" @endif type="button" class="btn btn-danger">删除一个条件</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="form-group-actions col-md-9">
                                    <button class="btn btn-success btn-ladda ladda-button" data-style="zoom-out" id="updateThingStateSubmit">
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
            $('#addOneCondition').click(function () {
                let $pGroup = $(this).parents('div.form-group').first();
                let count = $pGroup.siblings('div.form-group').length - 1;
                $pGroup.before('<div class="form-group row">\n' +
                    '                                        <label class="col-md-2 col-form-label text-right" for="comment">并且</label>\n' +
                    '                                        <div class="col-md-9">\n' +
                    '                                            <div class="row">\n' +
                    '                                                <div class="col-md-4">\n' +
                    '                                                    <select class="form-control" name="cond['+ count +'][fieldId]">\n' +
                        @foreach($fields as $field)
                            '                                                        <option value="{{ $field['id'] }}">{{ $field['comment'] }}</option>\n' +
                        @endforeach
                            '                                                    </select>\n' +
                    '                                                </div>\n' +
                    '                                                <div class="col-md-4">\n' +
                    '                                                    <select class="form-control" name="cond['+ count +'][symbol]">\n' +
                        @foreach($symbols as $key => $value)
                            '                                                        <option value="{{ $key }}">{{ $value }}</option>\n' +
                        @endforeach
                            '                                                    </select>\n' +
                    '                                                </div>\n' +
                    '                                                <div class="col-md-4">\n' +
                    '                                                    <input type="text" class="form-control" name="cond['+ count +'][value]" placeholder="值" />\n' +
                    '                                                </div>\n' +
                    '                                            </div>\n' +
                    '                                        </div>\n' +
                    '                                    </div>');

                if(count > 0){
                    $('#deleteOneCondition').show();
                }
                return false;
            });

            $('#deleteOneCondition').click(function(){
                let $pGroup = $(this).parents('div.form-group').first();
                let count = $pGroup.siblings('div.form-group').length - 1;

                if(count > 1){
                    $pGroup.prev('div.form-group').remove();
                    if(count === 2){
                        $(this).hide();
                    }
                }
                return false;
            });

            $('#updateThingStateSubmit').click(function(){
                let ladda = Ladda.create(this).start();
                let data = $('#updateThingStateForm').serializeJSON();
                z_ajax('put', '{{ route('updateThingState', ['appId'=>$appId, 'thingId'=>$thingId, 'stateId'=>$state['id']]) }}', data, function (response) {
                    let data = response.data;
                    if(data.code === 0){
                        z_notify_success(data.message ? data.message : '操作成功！', function () {
                            location.href = '{{ route('indexThingStates', ['appId'=>$appId, 'thingId'=>$thingId]) }}';
                        });
                    }else{
                        z_notify_fail(data.message ? data.message : '操作失败！');
                    }
                })
            });
        })
    </script>
@endpush