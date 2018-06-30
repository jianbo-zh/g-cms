@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> 事物列表
                        </div>
                        <div class="card-body">
                            <form method="get" action="" class="form-inline table-search">
                                <div class="form-group">
                                    <label for="name">名称</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $query['name'] }}" />
                                </div>
                                <div class="form-actions">
                                    <button class="btn btn-primary" type="submit">搜 索</button>
                                    <a href="{{ route('createThing', ['appId'=>$appId]) }}" class="btn btn-success">新增</a>
                                </div>
                            </form>
                            <table class="table table-responsive-sm table-bordered">
                                <thead>
                                <tr>
                                    <th>编号</th>
                                    <th>应用编号</th>
                                    <th>名称</th>
                                    <th>描述</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($things as $thing)
                                    <tr>
                                        <td>{{ $thing['id'] }}</td>
                                        <td>{{ $thing['appId'] }}</td>
                                        <td>{{ $thing['name'] }}</td>
                                        <td>{{ $thing['description'] }}</td>
                                        <td>

                                            <a href="{{ route('editThing', ['appId'=>$appId, 'thingId'=>$thing['id']]) }}" class="btn btn-primary">编辑</a>
                                            <button class="btn btn-danger btn-ladda ladda-button destroyThingButton" data-style="zoom-out" data-id="{{ $thing['id'] }}">刪除</button>

                                            <div class="btn-group">
                                                <a href="{{ route('manageThing', ['appId'=>$appId, 'thingId'=>$thing['id']]) }}" class="btn btn-primary">管理</a>
                                                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(71px, 34px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                    <a class="dropdown-item" href="{{ route('indexThingFields', ['appId'=>$appId, 'thingId'=>$thing['id']]) }}">字段列表</a>
                                                    <a class="dropdown-item" href="{{ route('indexThingStates', ['appId'=>$appId, 'thingId'=>$thing['id']]) }}">状态列表</a>
                                                    <a class="dropdown-item" href="{{ route('indexThingOperations', ['appId'=>$appId, 'thingId'=>$thing['id']]) }}">操作列表</a>
                                                    <a class="dropdown-item" href="{{ route('indexThingMessages', ['appId'=>$appId, 'thingId'=>$thing['id']]) }}">消息列表</a>
                                                </div>
                                            </div>
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
            $('.destroyThingButton').click(function () {
                let thingId = $(this).attr('data-id');
                let $that = $(this);
                if(confirm('确认删除该事物？')){
                    let ladda = Ladda.create(this).start();
                    let turl = '{{ route('destroyThing', ['appId' => $appId, 'thingId' => '[thingId]']) }}';
                    z_ajax('delete', z_bind_url_params(turl, {'[thingId]':thingId}), null, function (response) {
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