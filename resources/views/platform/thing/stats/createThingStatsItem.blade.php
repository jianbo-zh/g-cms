@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="card card-accent-info">
                        <div class="card-header">
                            创建事物统计
                        </div>
                        <div class="card-body row">
                            <div class="col-md-10">
                                <form id="createThingStatsForm">
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label text-right" for="name">名称</label>
                                        <div class="col-md-6">
                                            <input class="form-control" id="name" name="name" value="" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label text-right" for="comment">条件</label>
                                        <div class="col-md-9">
                                            <div class="form-group row">
                                                <div class="col-md-3"><strong>字段</strong></div>
                                                <div class="col-md-3"><strong>操作</strong></div>
                                                <div class="col-md-3"><strong>值</strong></div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <select class="form-control" name="cond[0][fieldId]">
                                                        @foreach($fields as $field)
                                                            <option value="{{ $field['id'] }}">{{ $field['comment'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <select class="form-control" name="cond[0][symbol]">
                                                        @foreach($symbols as $key => $value)
                                                            <option value="{{ $key }}">{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="cond[0][value]" placeholder="值" />
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-9">
                                                    <button id="addOneCondition" type="button" class="btn btn-primary">添加一个条件</button>
                                                    <button id="deleteOneCondition" style="display:none;" type="button" class="btn btn-danger">删除一个条件</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label text-right" for="comment">维度</label>
                                        <div class="col-md-9">
                                            <div class="form-group row">
                                                <div class="col-md-3"><strong>字段</strong></div>
                                                <div class="col-md-3"><strong>别名</strong></div>
                                                <div class="col-md-3"><strong>类型</strong></div>
                                                <div class="col-md-3"><strong>操作</strong></div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <select class="form-control" name="group[0][fieldId]">
                                                        @foreach($fields as $field)
                                                            <option value="{{ $field['id'] }}">{{ $field['comment'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" name="group[0][name]" placeholder="别名" value="" />
                                                </div>
                                                <div class="col-md-3">
                                                    <select class="form-control groupType" name="group[0][type]">
                                                        <option value=""></option>
                                                        @foreach($groupTypes as $key => $value)
                                                            <option value="{{ $key }}">{{ $value['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <select class="form-control" name="group[0][operation]">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <select class="form-control" name="group[1][fieldId]">
                                                        @foreach($fields as $field)
                                                            <option value="{{ $field['id'] }}">{{ $field['comment'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" name="group[1][name]" placeholder="别名" value="" />
                                                </div>
                                                <div class="col-md-3">
                                                    <select class="form-control groupType" name="group[1][type]">
                                                        <option value=""></option>
                                                        @foreach($groupTypes as $key => $value)
                                                            <option value="{{ $key }}">{{ $value['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <select class="form-control" name="group[1][operation]">
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-9">
                                                    <button id="addOneGroup" type="button" class="btn btn-primary">添加一个维度</button>
                                                    <button id="deleteOneGroup" style="display:none;" type="button" class="btn btn-danger">删除一个维度</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label text-right" for="comment">图表</label>
                                        <div class="col-md-10">
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <select class="form-control" name="chartType" id="chartType">
                                                        <option value=""></option>
                                                        <option value="line">折线图</option>
                                                        <option value="bar">柱图</option>
                                                        <option value="pie">饼图</option>
                                                        <option value="scatter">散列图</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <select class="form-control" name="chartValue" id="chartValue">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-10">
                                                    <textarea class="form-control" rows="5" placeholder="图表Option配置（JSON格式）" name="chartOption"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="form-group-actions col-md-9">
                                    <button class="btn btn-success btn-ladda ladda-button" data-style="zoom-out" id="createThingStatsSubmit">
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
                    '         <div class="col-md-3">\n' +
                    '             <select class="form-control" name="cond['+ count +'][fieldId]">\n' +
                    @foreach($fields as $field)
                    '                     <option value="{{ $field['id'] }}">{{ $field['comment'] }}</option>\n' +
                    @endforeach
                    '             </select>\n' +
                    '         </div>\n' +
                    '         <div class="col-md-3">\n' +
                    '             <select class="form-control" name="cond['+ count +'][symbol]">\n' +
                    @foreach($symbols as $key => $value)
                    '                     <option value="{{ $key }}">{{ $value }}</option>\n' +
                    @endforeach
                    '             </select>\n' +
                    '         </div>\n' +
                    '         <div class="col-md-4">\n' +
                    '             <input type="text" class="form-control" name="cond['+ count +'][value]" placeholder="值" />\n' +
                    '         </div>\n' +
                    '     </div>');

                if(count > 0){
                    $('#deleteOneCondition').show();
                }
                return false;
            });

            $('#deleteOneCondition').click(function(){
                let $pGroup = $(this).parents('div.form-group').first();
                let count = $pGroup.siblings('div.form-group').length - 1;

                if(count >= 2){
                    $pGroup.prev('div.form-group').remove();
                    if(count === 2){
                        $(this).hide();
                    }
                }
                return false;
            });

            $('#addOneGroup').click(function () {
                let $pGroup = $(this).parents('div.form-group').first();
                let count = $pGroup.siblings('div.form-group').length - 1;
                $pGroup.before('<div class="form-group row">\n' +
                    '    <div class="col-md-3">\n' +
                    '        <select class="form-control" name="group['+ count +'][fieldId]">\n' +
                        @foreach($fields as $field)
                            '                <option value="{{ $field['id'] }}">{{ $field['comment'] }}</option>\n' +
                        @endforeach
                            '        </select>\n' +
                    '    </div>\n' +
                    '    <div class="col-md-3">\n' +
                    '        <input type="text" class="form-control" name="group['+ count +'][name]" value="" />\n' +
                    '    </div>\n' +
                    '    <div class="col-md-3">\n' +
                    '        <select class="form-control groupType" name="group['+ count +'][type]">\n' +
                    '            <option values=""></option>\n'+
                        @foreach($groupTypes as $key => $value)
                            '                <option value="{{ $key }}">{{ $value['name'] }}</option>\n' +
                        @endforeach
                            '        </select>\n' +
                    '    </div>\n' +
                    '    <div class="col-md-3">\n' +
                    '        <select class="form-control" name="group['+ count +'][operation]">\n' +
                    '            <option values=""></option>\n'+
                            '        </select>\n' +
                    '    </div>\n' +
                    '</div>');

                if(count > 1){
                    $('#deleteOneGroup').show();
                }

                $('.groupType').off('change').on('change', cbGroupTypeChange);

                return false;
            });

            $('#deleteOneGroup').click(function(){
                let $pGroup = $(this).parents('div.form-group').first();
                let count = $pGroup.siblings('div.form-group').length - 1;

                if(count >= 2){
                    $pGroup.prev('div.form-group').remove();
                    if(count === 3){
                        $(this).hide();
                    }
                }
                return false;
            });

            $('#chartType').change(function (event) {
                let typeMap = {
                    line : [
                        {name: 'Basic Line Chart', value: 'basicLine'},
                        {name: 'Basic Area Chart', value: 'basicArea'},
                        {name: 'Smoothed Line Chart', value: 'smoothLine'},
                        {name: 'Stacked Line Chart', value: 'stackedLine'},
                        {name: 'Stacked Area Chart', value: 'stackedArea'},
                    ],
                    bar : [
                        {name: 'Basic Bar', value: 'basicBar'},
                        {name: 'Basic Bar Y-Axis', value: 'basicBarYAxis'},
                        {name: 'Stacked Bar', value: 'stackedBar'},
                        {name: 'Stacked Bar Y-Axis', value: 'stackedBarYAxis'},
                        {name: 'Polar Bar', value: 'polarBar'},
                        {name: 'Polar Stacked Bar', value: 'polarStackedBar'},
                    ],
                    pie : [
                        {name: 'Basic Pie', value: 'basicPie'},
                        {name: 'Radius Pie', value: 'radiusPie'},
                    ],
                    scatter : [
                        {name: 'Basic Scatter', value: 'basicScatter'}
                    ]
                };
                let options = '';
                switch ($(this).val()){
                    case 'line':
                        for( let i = 0; i < typeMap.line.length; i++){
                            let value = typeMap.line[i].value,
                                text = typeMap.line[i].name;

                            options += '<option value="'+ value +'">'+ text +'</option>';
                        }
                        break;
                    case 'bar':
                        for( let i = 0; i < typeMap.bar.length; i++){
                            let value = typeMap.bar[i].value,
                                text = typeMap.bar[i].name;

                            options += '<option value="'+ value +'">'+ text +'</option>';
                        }
                        break;
                    case 'pie':
                        for( let i = 0; i < typeMap.pie.length; i++){
                            let value = typeMap.pie[i].value,
                                text = typeMap.pie[i].name;

                            options += '<option value="'+ value +'">'+ text +'</option>';
                        }
                        break;
                    case 'scatter':
                        for( let i = 0; i < typeMap.scatter.length; i++){
                            let value = typeMap.scatter[i].value,
                                text = typeMap.scatter[i].name;

                            options += '<option value="'+ value +'">'+ text +'</option>';
                        }
                        break;
                    default:
                        break;
                }

                $('#chartValue').html(options);
            });

            let cbGroupTypeChange = function(event){
                let $that = $(event.target);
                let groupTypeMap = JSON.parse('{!! $groupTypeMapJson !!}');
                let $groupOperation = $that.parent('div').next('div').find('select.form-control');
                let options = '';

                switch ($that.val()){
                    case 'timeGroup':
                        for( let i = 0; i < groupTypeMap.timeGroup.subs.length; i++){
                            let value = groupTypeMap.timeGroup.subs[i].value,
                                text = groupTypeMap.timeGroup.subs[i].name;
                            options += '<option value="'+ value +'">'+ text +'</option>';
                        }
                        break;
                    case 'commonGroup':
                        for( let i = 0; i < groupTypeMap.commonGroup.subs.length; i++){
                            let value = groupTypeMap.commonGroup.subs[i].value,
                                text = groupTypeMap.commonGroup.subs[i].name;
                            options += '<option value="'+ value +'">'+ text +'</option>';
                        }
                        break;
                    case 'calculate':
                        for( let i = 0; i < groupTypeMap.calculate.subs.length; i++){
                            let value = groupTypeMap.calculate.subs[i].value,
                                text = groupTypeMap.calculate.subs[i].name;
                            options += '<option value="'+ value +'">'+ text +'</option>';
                        }
                        break;
                    default:
                        break;
                }

                $groupOperation.html(options);
            };

            $('.groupType').on('change', cbGroupTypeChange);

            $('#createThingStatsSubmit').click(function(){
                let ladda = Ladda.create(this).start();
                let data = $('#createThingStatsForm').serializeJSON();
                z_ajax('post', '{{ route('storeThingStatsItem', ['appId'=>$appId, 'thingId'=>$thingId]) }}', data, function (response) {
                    let data = response.data;
                    if(data.code === 0){
                        z_notify_success(data.message ? data.message : '操作成功！', function () {
                            location.href = '{{ route('indexThingStatsItems', ['appId'=>$appId, 'thingId'=>$thingId]) }}';
                        });
                    }else{
                        z_notify_fail(data.message ? data.message : '操作失败！');
                    }
                })
            });
        })
    </script>
@endpush