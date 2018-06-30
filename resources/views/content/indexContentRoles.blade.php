@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> 应用角色列表
                        </div>
                        <div class="card-body">
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
                                            <a href="{{ route('indexContentRoleUsers', ['appId'=>$appId, 'roleId'=>$role['id']]) }}" class="btn btn-primary">用户</a>
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