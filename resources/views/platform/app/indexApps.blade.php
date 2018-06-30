@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> 应用列表
                        </div>
                        <div class="card-body">
                            <form method="get" action="" class="form-inline table-search">
                                <div class="form-group">
                                    <label for="name">名称</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $query['name'] }}" />
                                </div>
                                <div class="form-group">
                                    <label for="state">状态</label>
                                    <select id="state" name="state" class="form-control">
                                        <option value="">请选择</option>
                                        <option value="1" @if($query['state']==='1') selected @endif>启用</option>
                                        <option value="0" @if($query['state']==='0') selected @endif>禁用</option>
                                    </select>
                                </div>
                                <div class="form-actions">
                                    <button class="btn btn-primary" type="submit">搜 索</button>
                                    <a href="{{ route('createApp') }}" class="btn btn-success">新增</a>
                                </div>
                            </form>
                            <table class="table table-responsive-sm table-bordered">
                                <thead>
                                <tr>
                                    <th>编号</th>
                                    <th>管理员</th>
                                    <th>名称</th>
                                    <th>描述</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($apps as $app)
                                    <tr>
                                        <td>{{ $app['id'] }}</td>
                                        <td>{{ $app['username'] }}</td>
                                        <td>{{ $app['name'] }}</td>
                                        <td>{{ $app['description'] }}</td>
                                        <td>
                                            @if($app['state']==1)
                                                <span class="badge badge-success">启用</span>
                                            @else
                                                <span class="badge badge-light">禁用</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('editApp', ['appId'=>$app['id']]) }}" class="btn btn-primary">编辑</a>
                                            <button class="btn btn-danger btn-ladda ladda-button destroyApp" data-style="zoom-out" data-id="{{ $app['id'] }}">刪除</button>

                                            <div class="btn-group">
                                                <a href="{{ route('manageApp', ['appId'=>$app['id']]) }}" class="btn btn-primary">管理</a>
                                                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(71px, 34px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                    <a class="dropdown-item" href="{{ route('indexAppRoles', ['appId'=>$app['id']]) }}">角色列表</a>
                                                    <a class="dropdown-item" href="{{ route('indexAppUsers', ['appId'=>$app['id']]) }}">用户列表</a>
                                                    <a class="dropdown-item" href="{{ route('indexThings', ['appId'=>$app['id']]) }}">事物列表</a>
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
            $('.destroyApp').click(function () {
                let appId = $(this).attr('data-id');
                let $that = $(this);
                if(confirm('确认删除该应用？')){
                    let ladda = Ladda.create(this).start();
                    let turl = '{{ route('destroyApp', ['appId' => '[appId]']) }}';
                    z_ajax('delete', z_bind_url_params(turl, {'[appId]':appId}), null, function (response) {
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