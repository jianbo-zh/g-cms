@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> 平台角色列表
                        </div>
                        <div class="card-body">
                            <form method="get" action="" class="form-inline table-search">
                                <div class="form-group">
                                    <label for="state">状态</label>
                                    <select id="state" name="state" class="form-control">
                                        <option value="">请选择</option>
                                        <option value="1">启用</option>
                                        <option value="0">禁用</option>
                                    </select>
                                </div>
                                <div class="form-actions">
                                    <button class="btn btn-primary" type="submit">搜 索</button>
                                    <a href="{{ route('createPlatformRole') }}" class="btn btn-success">新增</a>
                                </div>
                            </form>
                            <table class="table table-responsive-sm table-bordered">
                                <thead>
                                <tr>
                                    <th>编号</th>
                                    <th>名称</th>
                                    <th>描述</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($roles as $role)
                                    <tr>
                                        <td>{{ $role['id'] }}</td>
                                        <td>{{ $role['name'] }}</td>
                                        <td>{{ $role['description'] }}</td>
                                        <td>
                                            @if($role['state']==1)
                                                <span class="badge badge-success">启用</span>
                                            @else
                                                <span class="badge badge-light">禁用</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('indexPlatformRoleUsers', ['rid'=>$role['id']]) }}" class="btn btn-primary">用户</a>
                                            <a href="{{ route('editPlatformRole', ['rid'=>$role['id']]) }}" class="btn btn-primary">编辑</a>
                                            <button class="btn btn-danger btn-ladda ladda-button destroyPlatformRole" data-style="zoom-out" data-id="{{ $role['id'] }}">刪除</button>
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
            $('.destroyPlatformRole').click(function () {
                let roleId = $(this).attr('data-id');
                let $that = $(this);
                if(confirm('确认删除该角色？')){
                    let ladda = Ladda.create(this).start();
                    z_ajax('delete', '{{ route('destroyPlatformRole') }}', {id:roleId}, function (response) {
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