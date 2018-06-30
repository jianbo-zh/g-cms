@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> 应用用户列表
                        </div>
                        <div class="card-body">
                            <form method="get" action="{{ route('indexContentUsers', ['appId'=>$appId]) }}" class="form-inline table-search">
                                <div class="form-group">
                                    <label for="username">账号</label>
                                    <input type="text" class="form-control" id="username" name="username" value="{{ $query['username'] }}" />
                                </div>
                                <div class="form-group">
                                    <label for="state">状态</label>
                                    <select id="state" name="state" class="form-control">
                                        <option value="">请选择</option>
                                        <option value="1" @if($query['state']===true) selected @endif>启用</option>
                                        <option value="0" @if($query['state']===false) selected @endif>禁用</option>
                                    </select>
                                </div>
                                <div class="form-actions">
                                    <button class="btn btn-primary" type="submit">搜 索</button>
                                    <a href="{{ route('createContentUser', ['appId'=>$appId]) }}" class="btn btn-success">新增</a>
                                </div>
                            </form>
                            <table class="table table-responsive-sm table-bordered">
                                <thead>
                                <tr>
                                    <th>编号</th>
                                    <th>头像</th>
                                    <th>账户</th>
                                    <th>昵称</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user['id'] }}</td>
                                        <td><img src="{{ $user['avatar'] }}" /></td>
                                        <td>{{ $user['username'] }}</td>
                                        <td>{{ $user['nickname'] }}</td>
                                        <td>
                                            @if($user['state']==1)
                                                <span class="badge badge-success">启用</span>
                                            @else
                                                <span class="badge badge-light">禁用</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user['id'] !== 1)
                                                <a href="{{ route('editContentUser', ['appId'=>$appId, 'userId'=>$user['id']]) }}" class="btn btn-primary">编辑</a>
                                                <button class="btn btn-danger btn-ladda ladda-button destroyContentUser" data-style="zoom-out" data-id="{{ $user['id'] }}">刪除</button>
                                            @endif
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
            $('.destroyContentUser').click(function () {
                let userId = $(this).attr('data-id');
                let $that = $(this);
                if(confirm('确认删除该用户？')){
                    let ladda = Ladda.create(this).start();
                    let turl = '{{ route('destroyContentUser', ['appId'=>$appId, 'userId'=>'[uid]']) }}';
                    z_ajax('delete', z_bind_url_params(turl, {'[uid]':userId}), null, function (response) {
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