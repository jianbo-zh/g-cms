@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="card card-accent-info">
                        <div class="card-header">
                            创建事物消息
                        </div>
                        <div class="card-body row">
                            <div class="col-md-8">
                                <form id="createThingMessageForm">
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label text-right" for="stateId">状态</label>
                                        <div class="col-md-9">
                                            <select class="form-control" name="stateId" id="stateId">
                                                @foreach($states as $state)
                                                    <option value="{{ $state['id'] }}">{{ $state['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label text-right" for="receiverValue">被通知人</label>
                                        <div class="col-md-9">
                                            <select class="form-control" name="receiverValue" id="receiverValue">
                                                @foreach($roles as $role)
                                                    <option value="{{ $role['id'] }}">{{ $role['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label text-right" for="content">消息内容</label>
                                        <div class="col-md-9">
                                            <textarea rows="3" class="form-control" name="content"></textarea>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="form-group-actions col-md-9">
                                    <button class="btn btn-success btn-ladda ladda-button" data-style="zoom-out" id="createThingMessageSubmit">
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
            $('#createThingMessageSubmit').click(function(){
                let ladda = Ladda.create(this).start();
                let data = $('#createThingMessageForm').serializeJSON();
                z_ajax('post', '{{ route('storeThingMessage', ['appId'=>$appId, 'thingId'=>$thingId]) }}', data, function (response) {
                    let data = response.data;
                    if(data.code === 0){
                        z_notify_success(data.message ? data.message : '操作成功！', function () {
                            location.href = '{{ route('indexThingMessages', ['appId'=>$appId, 'thingId'=>$thingId]) }}';
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