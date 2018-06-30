@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="card card-accent-info">
                        <div class="card-header">
                            个人中心
                        </div>
                        <div class="card-body row">
                            <div class="col-md-8">
                                <form action="{{ route('updateProfile') }}" method="post" id="profile-form">
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
                                        <label class="col-md-2 col-form-label text-right" for="avatar">头像</label>
                                        <div class="col-md-9">
                                            <input type="file" class="form-control" id="avatar" name="avatar" value="{{ $user['avatar'] }}" />
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
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="form-group-actions col-md-9">
                                    <button class="btn btn-success btn-ladda ladda-button" data-style="zoom-out" id="update-profile">
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
    <script src="{{ asset('js/page/profile.js') }}"></script>
@endpush