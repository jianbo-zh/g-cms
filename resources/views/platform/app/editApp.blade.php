@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> 编辑应用
                        </div>
                        <div class="card-body">
                            <form method="post" class="form-horizontal" id="updateAppForm">
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label" for="state">名称</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="name" name="name" value="{{ $app['name'] }}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label" for="description">描述</label>
                                    <div class="col-md-6">
                                        <textarea rows="3" class="row-span form-control" id="description" name="description">{{ $app['description'] }}</textarea>
                                    </div>
                                </div>
                                @can('platform')
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label" for="userId">管理员</label>
                                        <div class="col-md-6">
                                            <select class="form-control select2" id="userId" name="userId">
                                                @foreach($users as $user)
                                                    <option value="{{ $user['id'] }}" @if($user['id']==$app['userId']) selected="selected" @endif>{{ $user['username'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endcan
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">状态</label>
                                    <div class="col-md-6">
                                        <label class="switch switch-label switch-outline-primary-alt">
                                            <input type="checkbox" class="switch-input" @if($app['state'] === 1) checked="checked" @endif name="state" value="1">
                                            <span class="switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="form-actions col-md-10">
                                    <button class="btn btn-primary btn-ladda ladda-button" data-style="zoom-out" id="updateAppSubmit">
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

@push('styles')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script>
        $(function () {
            $('.select2').select2({
                theme : "bootstrap",
                allowClear : false,
                placeholder : "请选择"
            });

            $('#updateAppSubmit').click(function () {
                let formData = $('#updateAppForm').serializeJSON();
                let ladda = Ladda.create(this).start();
                z_ajax('put', '{{ route("updateApp", ['appId'=>$app['id']]) }}', formData, function (response) {
                    let data = response.data;
                    if(data.code === 0){
                        z_notify_success(data.message ? data.message : '操作成功！', function () {
                            location.href = '{{ route("indexApps") }}';
                        });
                    }else{
                        z_notify_fail(data.message ? data.message : '操作失败！');
                    }
                    ladda.stop();
                });
            });
        });
    </script>
@endpush