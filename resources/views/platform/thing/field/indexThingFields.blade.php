@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> 结构定义
                        </div>
                        <div class="card-body">
                            <div class="table-search">
                                <a href="{{ route('createThingField', ['appId'=>$appId, 'thingId'=>$thingId]) }}" class="btn btn-primary">新增字段</a>
                                <button class="btn btn-success" id="migrateThingButton">迁移结构</button>
                            </div>

                            <table class="table table-responsive-sm table-bordered">
                                <thead>
                                <tr>
                                    <th>字段名(英文)</th>
                                    <th>中文名称</th>
                                    <th>存储类型</th>
                                    <th>展示类型</th>
                                    <th>是否列表显示</th>
                                    <th>是否查询条件</th>
                                    <th>是否迁移</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($fields as $field)
                                    <tr>
                                        <td>{{ $field['name'] }}</td>
                                        <td>{{ $field['comment'] }}</td>
                                        <td>{{ $field['storageType'] }}</td>
                                        <td>{{ $field['showType'] }}</td>
                                        <td>
                                            @if($field['isList'])
                                                <span class="badge badge-success">是</span>
                                            @else
                                                <span class="badge badge-light">否</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($field['isSearch'])
                                                <span class="badge badge-success">是</span>
                                            @else
                                                <span class="badge badge-light">否</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($field['state']==4)
                                                <span class="badge badge-success">是</span>
                                            @else
                                                <span class="badge badge-light">否</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('editThingField', ['appId'=>$appId, 'thingId'=>$thingId, 'fieldId'=>$field['id']]) }}" class="btn btn-primary">编辑</a>
                                            <button class="btn btn-danger btn-ladda ladda-button destroyThingFieldButton" data-style="zoom-out" data-id="{{ $field['id'] }}">刪除</button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">

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

            $('#migrateThingButton').click(function(){
                let ladda = Ladda.create(this).start();
                z_ajax('put', '{{ route('migrateThing', ['appId'=>$appId, 'thingId'=>$thingId]) }}', null, function (response) {
                    let data = response.data;
                    if(data.code === 0){
                        z_notify_success(data.message ? data.message : '操作成功！');
                        location.reload();
                    }else{
                        z_notify_fail(data.message ? data.message : '操作失败！');
                    }
                    ladda.stop();
                })
            });

            $('.destroyThingFieldButton').click(function () {
                let fieldId = $(this).attr('data-id');
                let $that = $(this);
                if(confirm('确认删除该事物？')){
                    let ladda = Ladda.create(this).start();
                    let turl = '{{ route('destroyThingField', ['appId' => $appId, 'thingId' => $thingId, 'fieldId'=>'[fieldId]']) }}';
                    z_ajax('delete', z_bind_url_params(turl, {'[fieldId]':fieldId}), null, function (response) {
                        let data = response.data;
                        if(data.code === 0){
                            z_notify_success(data.message ? data.message : '操作成功！');
                            $that.parents('tr').remove();
                        }else{
                            z_notify_fail(data.message ? data.message : '操作失败！');
                        }
                        ladda.stop();
                    })
                }
            });
        })
    </script>
@endpush