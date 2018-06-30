@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="card card-accent-info">
                        <div class="card-header">
                            编辑应用用户
                        </div>
                        <div class="card-body row">
                            <div class="col-md-8">
                                <form id="updateAppUserForm">
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label text-right" for="username">账号</label>
                                        <div class="col-md-9">
                                            <input class="form-control" id="username" disabled value="{{ $user['username'] }}" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label text-right" for="nickname">昵称</label>
                                        <div class="col-md-9">
                                            <input class="form-control" id="nickname" name="nickname" value="{{ $user['nickname'] }}" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label text-right" for="phone">手机号</label>
                                        <div class="col-md-9">
                                            <input class="form-control" id="phone" name="phone" value="{{ $user['phone'] }}" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label text-right" for="email">邮箱</label>
                                        <div class="col-md-9">
                                            <input type="email" class="form-control" id="email" name="email" value="{{ $user['email'] }}" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label text-right" for="state">状态</label>
                                        <div class="col-md-9">
                                            <label class="switch switch-label switch-outline-primary-alt">
                                                <input type="checkbox" class="switch-input" @if($user['state']==1) checked="checked" @endif name="state" value="1">
                                                <span class="switch-slider" data-checked="On" data-unchecked="Off"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label text-right" for="password">密码</label>
                                        <div class="col-md-9">
                                            <input type="password" class="form-control" id="password" name="password" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label text-right" for="password_confirmation">确认密码</label>
                                        <div class="col-md-9">
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-4">
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="form-group-actions col-md-9">
                                    <button class="btn btn-success btn-ladda ladda-button" data-style="zoom-out" id="updateAppUserSubmit">
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
            $('#updateAppUserSubmit').click(function(){
                let ladda = Ladda.create(this).start();
                let formData = $('#updateAppUserForm').serializeJSON();

                z_ajax('put', '{{ route('updateAppUser', ['appId'=>$appId, 'userId'=>$user['id']]) }}', formData, function (response) {
                    let data = response.data;
                    if(data.code === 0){
                        z_notify_success(data.message ? data.message : '操作成功！', function () {
                            location.href = '{{ route('indexAppUsers', ['appId'=>$appId]) }}';
                        });
                    }else{
                        z_notify_fail(data.message ? data.message : '操作失败！');
                    }
                    ladda.stop();
                })
            });
        })
    </script>
@endpush