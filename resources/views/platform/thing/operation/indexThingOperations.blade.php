@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> 操作定义
                        </div>
                        <div class="card-body">
                            <div class="table-search">
                                <a href="{{ route('createThingOperation', ['appId'=>$appId, 'thingId'=>$thingId]) }}" class="btn btn-primary">新增操作</a>
                            </div>

                            <table class="table table-responsive-sm table-bordered">
                                <thead>
                                <tr>
                                    <th>名称</th>
                                    <th>操作类型</th>
                                    <th>操作形式</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($operations as $operation)
                                    <tr>
                                        <td>{{ $operation['name'] }}</td>
                                        <td>{{ $operation['operationType'] }}</td>
                                        <td>{{ $operation['operationForm'] }}</td>
                                        <td>
                                            <a href="{{ route('editThingOperation', ['appId'=>$appId, 'thingId'=>$thingId, 'operationId'=>$operation['id']]) }}" class="btn btn-primary">编辑</a>
                                            <button class="btn btn-danger btn-ladda ladda-button destroyThingOperationButton" data-style="zoom-out" data-id="{{ $operation['id'] }}">刪除</button>
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
            $('.destroyThingOperationButton').click(function () {
                let operationId = $(this).attr('data-id');
                let $that = $(this);
                if(confirm('确认删除该操作？')){
                    let ladda = Ladda.create(this).start();
                    let turl = '{{ route('destroyThingOperation', ['appId' => $appId, 'thingId' => $thingId, 'operationId'=>'[operationId]']) }}';
                    z_ajax('delete', z_bind_url_params(turl, {'[operationId]':operationId}), null, function (response) {
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