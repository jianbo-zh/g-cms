@extends('layouts.app_simple')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mx-4">
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <h1>注册</h1>
                            <p class="text-muted">Create your account</p>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                              <span class="input-group-text">
                                <i class="icon-user"></i>
                              </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Username" name="username">
                            </div>

                            {{--<div class="input-group mb-3">
                                <div class="input-group-prepend">
                              <span class="input-group-text">
                                <i class="icon-user"></i>
                              </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Nickname" name="nickname">
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="icon-screen-smartphone"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Phone" name="phone">
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">@</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Email" name="email">
                            </div>--}}

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="icon-lock"></i>
                                </span>
                                </div>
                                <input type="password" class="form-control" placeholder="Password" name="password">
                            </div>

                            <div class="input-group mb-4">
                                <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="icon-lock"></i>
                                </span>
                                </div>
                                <input type="password" class="form-control" placeholder="Repeat password" name="password_confirmation">
                            </div>

                            <button type="submit" class="btn btn-block btn-success">创建账户</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection