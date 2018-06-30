@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> 操作列表
                        </div>
                        <div class="card-body">
                            <form method="post" action="" class="form-inline table-search" id="createStateOperationForm">
                                <div class="form-group">
                                    <label>操作</label>
                                    <select class="form-control select2" style="width: 10rem;" name="operationId">
                                        @foreach($notBelongOperations as $operation)
                                            <option value="{{ $operation['id'] }}">{{ $operation['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-actions">
                                    <button type="button" class="btn btn-primary ladda-button" id="createStateOperationSubmit">添加</button>
                                </div>
                            </form>
                            <table class="table table-responsive-sm table-bordered">
                                <thead>
                                <tr>
                                    <th>编号</th>
                                    <th>名称</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($operations as $operation)
                                    <tr>
                                        <td>{{ $operation['id'] }}</td>
                                        <td>{{ $operation['name'] }}</td>
                                        <td>
                                            <button class="btn btn-danger ladda-button destroyStateOperationButton" data-style="zoom-out" data-id="{{ $operation['id'] }}">刪除</button>
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

            $('.destroyStateOperationButton').click(function () {
                let operationId = $(this).attr('data-id');
                let $that = $(this);
                let ladda = Ladda.create(this).start();
                let turl = '{{ route('destroyThingStateOperation', ['appId'=>$appId, 'thingId'=>$thingId, 'stateId'=>$stateId, 'operationId'=>'[operationId]']) }}';
                if(confirm('确认删除该操作？')){
                    z_ajax('delete', z_bind_url_params(turl, {'[operationId]':operationId}), null, function (response) {
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

            $('#createStateOperationSubmit').click(function () {
                let data = $('#createStateOperationForm').serializeJSON();
                let ladda = Ladda.create(this).start();
                z_ajax('post', '{{ route('storeThingStateOperation', ['appId'=>$appId, 'thingId'=>$thingId, 'stateId'=>$stateId]) }}', data, function (response) {
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