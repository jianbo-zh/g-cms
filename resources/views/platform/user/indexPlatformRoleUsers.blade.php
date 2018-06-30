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
                            <form method="post" action="" class="form-inline table-search" id="createRoleUserForm">
                                <div class="form-group">
                                    <label>用户</label>
                                    <select class="form-control select2" style="width: 10rem;" name="userId">
                                        @foreach($notBelongToRoleUsers as $user)
                                            <option value="{{ $user['id'] }}">{{ $user['username'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-actions">
                                    <button type="button" class="btn btn-primary ladda-button" id="createRoleUserSubmit">添加</button>
                                </div>
                            </form>
                            <table class="table table-responsive-sm table-bordered">
                                <thead>
                                <tr>
                                    <th>编号</th>
                                    <th>账户</th>
                                    <th>昵称</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user['id'] }}</td>
                                        <td>{{ $user['username'] }}</td>
                                        <td>{{ $user['nickname'] }}</td>
                                        <td>
                                            <button class="btn btn-danger ladda-button destroyPlatformRoleUserButton" data-style="zoom-out" data-role-id="{{ $role['id'] }}" data-user-id="{{ $user['id'] }}">刪除</button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script>
        $(function(){
            $('.select2').select2({
                theme : "bootstrap",
                allowClear : true,
                placeholder : "请选择"
            });

            $('.destroyPlatformRoleUserButton').click(function () {
                let roleId = $(this).attr('data-role-id');
                let userId = $(this).attr('data-user-id');
                let $that = $(this);
                let ladda = Ladda.create(this).start();
                let turl = '{{ route('destroyPlatformRoleUser', ['roleId'=>'[rid]', 'userId'=>'[uid]']) }}';
                if(confirm('确认删除该角色？')){
                    z_ajax('delete', z_bind_url_params(turl, {'[rid]':roleId, '[uid]':userId}), null, function (response) {
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

            $('#createRoleUserSubmit').click(function () {
                let data = $('#createRoleUserForm').serializeJSON();
                let ladda = Ladda.create(this).start();
                z_ajax('post', '{{ route('storePlatformRoleUser', ['roleId'=>$role['id']]) }}', data, function (response) {
                    let data = response.data;
                    if(data.code === 0){
                        location.reload();
                    }else{
                        z_notify_fail(data.message ? data.message : '操作失败！');
                    }
                    ladda.stop();
                })
            });
        })
    </script>
@endpush